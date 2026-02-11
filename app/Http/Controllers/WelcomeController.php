<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Student;
use App\Models\User;
use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendBookingEmail;

class WelcomeController extends Controller
{
    /**
     * ✅ UPDATED: Show welcome page with all bookings (pending + approved) for user email
     */
    public function showWelcome()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return view('welcome')->with('info', 'कृपया लगइन गर्नुहोस् वा खाता दर्ता गर्नुहोस्।');
            }

            // ✅ विद्यार्थी भएमा
            $isStudent = $user->hasRole('student');
            $studentProfile = $user->student;
            $bookings = collect();
            $pendingCount = 0;

            // ✅ विद्यार्थी हो भने
            if ($isStudent) {
                if ($studentProfile) {
                    // होस्टेल छ भने
                    if ($studentProfile->hostel_id) {
                        $hostel = Hostel::find($studentProfile->hostel_id);
                        $hostelName = $hostel ? $hostel->name : null;
                    } else {
                        $hostelName = null;
                    }

                    // विद्यार्थीका बुकिंगहरू
                    $bookings = Booking::where('student_id', $studentProfile->id)
                        ->orWhere('guest_email', $user->email)
                        ->with(['hostel', 'room'])
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $pendingCount = $bookings->where('status', 'pending')->count();
                }
            }
            // विद्यार्थी होइन भने
            else {
                // गेस्ट बुकिंगहरू
                $bookings = Booking::where('guest_email', $user->email)
                    ->orWhere('user_id', $user->id)
                    ->with(['hostel', 'room'])
                    ->orderBy('created_at', 'desc')
                    ->get();

                $pendingCount = $bookings->where('status', 'pending')->count();
            }

            Log::info('Welcome page accessed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_student' => $isStudent,
                'pending_count' => $pendingCount
            ]);

            return view('student.welcome', compact(
                'user',
                'bookings',
                'isStudent',
                'studentProfile',
                'pendingCount'
            ));
        } catch (\Exception $e) {
            Log::error('Welcome page error: ' . $e->getMessage());

            // Emergency fallback
            return view('student.welcome', [
                'isStudent' => false,
                'bookings' => collect(),
                'pendingCount' => 0,
                'studentProfile' => null
            ])->with('error', 'वेलकम पेज लोड गर्दा त्रुटि भयो।');
        }
    }


    /**
     * ✅ UPDATED: Convert guest booking to student booking with auto student creation
     */
    public function convertGuestBooking(Request $request, $bookingId)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'कृपया पहिले लगइन गर्नुहोस्।');
            }

            // ✅ Find guest booking by email (more flexible)
            $booking = Booking::where('id', $bookingId)
                ->where(function ($query) use ($user) {
                    $query->where('guest_email', $user->email)
                        ->orWhere('email', $user->email);
                })
                ->where('is_guest_booking', true)
                ->first();

            if (!$booking) {
                return back()->with('error', 'बुकिंग फेला परेन वा यो तपाईंको इमेलसँग मेल खाँदैन।');
            }

            // ✅ Check if user already has student profile
            $student = $user->student;

            // ✅ If no student profile, create one automatically
            if (!$student) {
                $student = $this->createStudentFromUser($user, $booking);

                if (!$student) {
                    throw new \Exception('विद्यार्थी प्रोफाइल सिर्जना गर्न असफल।');
                }

                // Assign student role
                $user->assignRole('student');
            }

            // ✅ Update booking with user and student info
            $booking->update([
                'user_id' => $user->id,
                'student_id' => $student->id,
                'is_guest_booking' => false,
                'email' => $user->email
            ]);

            // ✅ If booking is approved, ensure student has correct room/hostel
            if ($booking->status === Booking::STATUS_APPROVED && $booking->room_id) {
                $this->updateStudentRoomAssignment($student, $booking);
            }

            DB::commit();

            Log::info('Guest booking converted successfully', [
                'user_id' => $user->id,
                'booking_id' => $bookingId,
                'student_id' => $student->id,
                'booking_status' => $booking->status
            ]);

            // ✅ Send confirmation email
            try {
                dispatch(new SendBookingEmail($booking, false, 'converted'));
            } catch (\Exception $e) {
                Log::error('Conversion email failed: ' . $e->getMessage());
            }

            return redirect()->route('bookings.my')
                ->with('success', 'बुकिंग सफलतापूर्वक तपाईंको खातामा जोडियो! विद्यार्थी प्रोफाइल पनि सिर्जना गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Booking conversion failed: ' . $e->getMessage());

            return back()->with('error', 'बुकिंग रूपान्तरण असफल: ' . $e->getMessage());
        }
    }

    /**
     * ✅ NEW: Auto-create student profile from user and booking data
     */
    private function createStudentFromUser(User $user, Booking $booking)
    {
        try {
            // Generate unique student ID
            $studentId = 'STU' . time() . rand(100, 999);

            $studentData = [
                'user_id' => $user->id,
                'student_id' => $studentId,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'Not provided',
                'hostel_id' => $booking->hostel_id,
                'organization_id' => $booking->organization_id,
                'status' => 'active',
                'admission_date' => now(),
                'payment_status' => 'pending',
            ];

            // If booking has room assigned, set it
            if ($booking->room_id) {
                $studentData['room_id'] = $booking->room_id;
            }

            return Student::create($studentData);
        } catch (\Exception $e) {
            Log::error('Student auto-creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * ✅ NEW: Update student room assignment when booking is approved
     */
    private function updateStudentRoomAssignment(Student $student, Booking $booking)
    {
        try {
            $updateData = [
                'hostel_id' => $booking->hostel_id,
                'room_id' => $booking->room_id,
                'organization_id' => $booking->organization_id
            ];

            $student->update($updateData);

            Log::info('Student room assignment updated', [
                'student_id' => $student->id,
                'room_id' => $booking->room_id,
                'hostel_id' => $booking->hostel_id
            ]);
        } catch (\Exception $e) {
            Log::error('Student room update failed: ' . $e->getMessage());
        }
    }

    /**
     * ✅ UPDATED: Complete student registration with detailed info
     */
    public function completeStudentRegistration(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|max:100|unique:students,student_id',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:20',
            'guardian_relation' => 'required|string|max:100',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'course' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:5'
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            // Get the first pending booking to extract hostel info
            $pendingBooking = Booking::where('guest_email', $user->email)
                ->where('status', Booking::STATUS_PENDING)
                ->first();

            // Create student profile
            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'guardian_name' => $request->guardian_name,
                'guardian_contact' => $request->guardian_contact,
                'guardian_relation' => $request->guardian_relation,
                'guardian_address' => $request->address,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'course' => $request->course,
                'year' => $request->year,
                'hostel_id' => $pendingBooking ? $pendingBooking->hostel_id : null,
                'organization_id' => $pendingBooking ? $pendingBooking->organization_id : null,
                'status' => 'active',
                'admission_date' => now(),
            ]);

            // Assign student role
            $user->assignRole('student');

            // ✅ UPDATED: Convert ALL guest bookings for this email to student bookings
            $updatedBookings = Booking::where('guest_email', $user->email)
                ->where('is_guest_booking', true)
                ->update([
                    'user_id' => $user->id,
                    'student_id' => $student->id,
                    'is_guest_booking' => false,
                    'email' => $user->email
                ]);

            DB::commit();

            Log::info('Student registration completed with auto-booking conversion', [
                'user_id' => $user->id,
                'student_id' => $student->id,
                'bookings_converted' => $updatedBookings
            ]);

            return redirect()->route('student.dashboard')
                ->with('success', 'तपाईंको विद्यार्थी प्रोफाइल सफलतापूर्वक दर्ता गरियो र ' . $updatedBookings . ' बुकिंगहरू जोडियो!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Student registration failed: ' . $e->getMessage());

            return back()->with('error', 'दर्ता प्रक्रिया असफल: ' . $e->getMessage());
        }
    }

    /**
     * ✅ NEW: Quick student registration for users with pending bookings
     */
    public function quickStudentRegistration(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'course' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            // Generate student ID
            $studentId = 'STU' . time() . rand(100, 999);

            // Get booking info for hostel assignment
            $pendingBooking = Booking::where('guest_email', $user->email)
                ->where('status', Booking::STATUS_PENDING)
                ->first();

            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => $studentId,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $request->phone,
                'course' => $request->course,
                'year' => 1, // Default first year
                'hostel_id' => $pendingBooking ? $pendingBooking->hostel_id : null,
                'organization_id' => $pendingBooking ? $pendingBooking->organization_id : null,
                'status' => 'active',
                'admission_date' => now(),
            ]);

            // Assign student role
            $user->assignRole('student');

            // Convert all guest bookings
            $updatedBookings = Booking::where('guest_email', $user->email)
                ->update([
                    'user_id' => $user->id,
                    'student_id' => $student->id,
                    'is_guest_booking' => false,
                    'email' => $user->email
                ]);

            DB::commit();

            return redirect()->route('student.dashboard')
                ->with('success', 'त्वरित दर्ता सफल! ' . $updatedBookings . ' बुकिंगहरू जोडियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'त्वरित दर्ता असफल: ' . $e->getMessage());
        }
    }

    /**
     * ✅ UPDATED: Check if user has pending bookings (API endpoint)
     */
    public function checkPendingBookings()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['has_pending' => false]);
            }

            $pendingCount = Booking::where('guest_email', $user->email)
                ->where('status', Booking::STATUS_PENDING)
                ->where('is_guest_booking', true)
                ->count();

            $totalBookings = Booking::where('guest_email', $user->email)
                ->orWhere('user_id', $user->id)
                ->count();

            return response()->json([
                'has_pending' => $pendingCount > 0,
                'pending_count' => $pendingCount,
                'total_bookings' => $totalBookings,
                'is_student' => $user->hasRole('student')
            ]);
        } catch (\Exception $e) {
            Log::error('Check pending bookings error: ' . $e->getMessage());
            return response()->json(['has_pending' => false]);
        }
    }

    /**
     * ✅ NEW: Get booking statistics for dashboard
     */
    public function getBookingStats()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([]);
            }

            $stats = [
                'total' => Booking::where('guest_email', $user->email)
                    ->orWhere('user_id', $user->id)
                    ->count(),
                'pending' => Booking::where('guest_email', $user->email)
                    ->where('status', Booking::STATUS_PENDING)
                    ->where('is_guest_booking', true)
                    ->count(),
                'approved' => Booking::where(function ($query) use ($user) {
                    $query->where('guest_email', $user->email)
                        ->orWhere('user_id', $user->id);
                })
                    ->where('status', Booking::STATUS_APPROVED)
                    ->count(),
                'rejected' => Booking::where(function ($query) use ($user) {
                    $query->where('guest_email', $user->email)
                        ->orWhere('user_id', $user->id);
                })
                    ->where('status', Booking::STATUS_REJECTED)
                    ->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Booking stats error: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}
