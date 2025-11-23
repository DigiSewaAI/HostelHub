<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Student;
use App\Jobs\SendBookingEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Store a new booking (Updated for guest bookings)
     */
    public function store(Request $request)
    {
        // ✅ Determine if it's a guest booking
        $isGuest = !Auth::check() || $request->has('is_guest_booking');

        // ✅ Validation rules for both guest and student
        $validationRules = [
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'notes' => 'nullable|string|max:500',
            'emergency_contact' => 'required|string|max:15'
        ];

        // ✅ Add guest fields validation if it's a guest booking
        if ($isGuest) {
            $validationRules['guest_name'] = 'required|string|max:255';
            $validationRules['guest_email'] = 'required|email|max:255';
            $validationRules['guest_phone'] = 'required|string|max:20';
        }

        $request->validate($validationRules);

        $room = Room::findOrFail($request->room_id);
        $hostel = $room->hostel;
        $organization = $hostel->organization;
        $subscription = $organization->currentSubscription();

        // ✅ Check room availability
        if (!$room->is_available) {
            return back()->with('error', 'यो कोठा अहिले उपलब्ध छैन।');
        }

        // ✅ For students: Check active bookings limit
        if (Auth::check() && !$isGuest) {
            $student = Auth::user()->student;
            $activeBookingsCount = Booking::where('student_id', $student->id)
                ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_APPROVED])
                ->count();

            if ($activeBookingsCount >= 3) {
                return back()->with('error', 'तपाईंसँग ३ वटा सक्रिय बुकिंग छन्। नयाँ बुकिंग गर्न अगाडिको बुकिंग समाप्त हुनुपर्छ।');
            }
        }

        // ✅ Determine booking status based on subscription plan
        $status = $subscription && $subscription->requiresManualBookingApproval()
            ? Booking::STATUS_PENDING
            : Booking::STATUS_APPROVED;

        DB::beginTransaction();

        try {
            $bookingData = [
                'room_id' => $request->room_id,
                'hostel_id' => $hostel->id,
                'organization_id' => $organization->id,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'status' => $status,
                'amount' => $room->price,
                'payment_status' => 'pending',
                'notes' => $request->notes,
                'emergency_contact' => $request->emergency_contact,
                'booking_date' => now(),
            ];

            // ✅ Set user/student fields for authenticated users
            if (Auth::check() && !$isGuest) {
                $student = Auth::user()->student;
                $bookingData['student_id'] = $student->id;
                $bookingData['user_id'] = Auth::id();
                $bookingData['email'] = Auth::user()->email;
                $bookingData['is_guest_booking'] = false;
            } else {
                // ✅ Set guest fields for guest bookings
                $bookingData['guest_name'] = $request->guest_name;
                $bookingData['guest_email'] = $request->guest_email;
                $bookingData['guest_phone'] = $request->guest_phone;
                $bookingData['is_guest_booking'] = true;
                $bookingData['email'] = $request->guest_email;
            }

            $booking = Booking::create($bookingData);

            // ✅ Auto-approve for Pro and Enterprise plans
            if ($status === Booking::STATUS_APPROVED) {
                $booking->update([
                    'approved_by' => Auth::check() ? Auth::id() : null,
                    'approved_at' => now()
                ]);

                // Update room status
                $room->update(['is_available' => false]);

                $message = $isGuest
                    ? 'तपाईंको कोठा बुकिंग स्वतः स्वीकृत गरिएको छ। तपाईंलाई इमेल पठाइएको छ।'
                    : 'तपाईंको कोठा बुकिंग स्वतः स्वीकृत गरिएको छ।';

                // ✅ AUTOMATIC PAYMENT REDIRECT ONLY FOR STUDENTS
                if (Auth::check() && !$isGuest) {
                    $payment = Payment::create([
                        'organization_id' => $organization->id,
                        'user_id' => Auth::id(),
                        'booking_id' => $booking->id,
                        'amount' => $room->price,
                        'payment_method' => 'pending',
                        'purpose' => 'booking',
                        'status' => 'pending',
                        'payment_date' => now(),
                        'metadata' => [
                            'auto_created' => true,
                            'booking_created_at' => now()->toISOString()
                        ]
                    ]);

                    // Redirect to payment checkout for students
                    return redirect()->route('payment.checkout', [
                        'amount' => $room->price,
                        'purpose' => 'booking',
                        'booking_id' => $booking->id,
                        'payment_id' => $payment->id
                    ])->with('success', 'बुकिंग सफल भयो! कृपया भुक्तानी पूरा गर्नुहोस्।');
                }
            } else {
                $message = $isGuest
                    ? 'तपाईंको कोठा बुकिंग सफलतापूर्वक पेश गरिएको छ। म्यानेजरद्वारा स्वीकृतिपछि तपाईंलाई इमेलमा सूचित गरिनेछ।'
                    : 'तपाईंको कोठा बुकिंग सफलतापूर्वक पेश गरिएको छ। म्यानेजरद्वारा स्वीकृतिपछि तपाईंलाई सूचित गरिनेछ।';
            }

            // ✅ SEND EMAIL NOTIFICATION FOR BOTH GUEST AND STUDENT
            try {
                dispatch(new SendBookingEmail($booking, $isGuest));
            } catch (\Exception $e) {
                Log::error('Email dispatch failed: ' . $e->getMessage());
            }

            DB::commit();

            // ✅ Redirect based on user type
            if (Auth::check() && !$isGuest) {
                return redirect()->route('bookings.my')->with('success', $message);
            } else {
                return redirect()->route('booking.success', $booking->id)
                    ->with('success', $message)
                    ->with('booking_id', $booking->id)
                    ->with('guest_email', $request->guest_email);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'बुकिंग सिर्जना गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Approve a booking (Updated for guest conversion)
     */
    public function approve($id)
    {
        DB::beginTransaction();

        try {
            $booking = Booking::findOrFail($id);
            $user = Auth::user();

            // Check if user has permission to approve (hostel manager or admin)
            if (!$user->hasRole('admin') && !$user->hasRole('hostel_manager')) {
                return back()->with('error', 'तपाईंसँग यो बुकिंग स्वीकृत गर्ने अनुमति छैन।');
            }

            // If hostel manager, check if they manage this hostel
            if ($user->hasRole('hostel_manager')) {
                $managedHostels = Hostel::where('owner_id', $user->id)->pluck('id');
                if (!$managedHostels->contains($booking->hostel_id)) {
                    return back()->with('error', 'तपाईंले यो होस्टलको बुकिंग स्वीकृत गर्न सक्नुहुन्न।');
                }
            }

            // Check room availability
            if (!$booking->room->is_available) {
                return back()->with('error', 'यो कोठा अहिले उपलब्ध छैन।');
            }

            $booking->update([
                'status' => Booking::STATUS_APPROVED,
                'approved_by' => $user->id,
                'approved_at' => now()
            ]);

            // Update room status
            $booking->room()->update(['is_available' => false]);

            // ✅ AUTO-CREATE STUDENT RECORD IF GUEST BOOKING HAS USER
            if ($booking->is_guest_booking && $booking->user_id) {
                try {
                    $user = $booking->user;
                    Student::createFromBooking($booking, $user);
                } catch (\Exception $e) {
                    Log::error('Student creation from booking failed: ' . $e->getMessage());
                }
            }

            // ✅ SEND APPROVAL EMAIL
            try {
                dispatch(new SendBookingEmail($booking, $booking->is_guest_booking, 'approved'));
            } catch (\Exception $e) {
                Log::error('Approval email failed: ' . $e->getMessage());
            }

            DB::commit();

            return back()->with('success', 'बुकिंग सफलतापूर्वक स्वीकृत गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'बुकिंग स्वीकृत गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Reject a booking (Updated for email notifications)
     */
    public function reject(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $booking = Booking::findOrFail($id);
            $user = Auth::user();

            // Check if user has permission to reject (hostel manager or admin)
            if (!$user->hasRole('admin') && !$user->hasRole('hostel_manager')) {
                return back()->with('error', 'तपाईंसँग यो बुकिंग अस्वीकृत गर्ने अनुमति छैन।');
            }

            // If hostel manager, check if they manage this hostel
            if ($user->hasRole('hostel_manager')) {
                $managedHostels = Hostel::where('owner_id', $user->id)->pluck('id');
                if (!$managedHostels->contains($booking->hostel_id)) {
                    return back()->with('error', 'तपाईंले यो होस्टलको बुकिंग अस्वीकृत गर्न सक्नुहुन्न।');
                }
            }

            $request->validate([
                'rejection_reason' => 'required|string|max:500'
            ]);

            $booking->update([
                'status' => Booking::STATUS_REJECTED,
                'approved_by' => $user->id,
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ]);

            // ✅ SEND REJECTION EMAIL
            try {
                dispatch(new SendBookingEmail($booking, $booking->is_guest_booking, 'rejected'));
            } catch (\Exception $e) {
                Log::error('Rejection email failed: ' . $e->getMessage());
            }

            DB::commit();

            return back()->with('success', 'बुकिंग सफलतापूर्वक अस्वीकृत गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'बुकिंग अस्वीकृत गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a booking (No changes needed)
     */
    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('student_id', Auth::user()->student->id)->findOrFail($id);

        // Check if booking can be cancelled
        if (!$booking->check_in_date->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'यो बुकिङ रद्द गर्न सकिँदैन। चेक-इन मिति भएको छ।'
            ], 422);
        }

        if ($booking->status === Booking::STATUS_CANCELLED) {
            return response()->json([
                'success' => false,
                'message' => 'यो बुकिङ पहिले नै रद्द गरिसकिएको छ।'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'cancelled_by' => Auth::id(),
                'notes' => $request->reason ? 'रद्द गर्ने कारण: ' . $request->reason : $booking->notes
            ]);

            // Make room available again
            $booking->room()->update(['is_available' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'बुकिङ सफलतापूर्वक रद्द गरियो।'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'बुकिङ रद्द गर्दा त्रुटि: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show user's bookings (Updated to include guest bookings)
     */
    public function myBookings()
    {
        $user = Auth::user();

        // ✅ Get both student bookings and guest bookings by email
        $bookings = Booking::where(function ($query) use ($user) {
            $query->where('student_id', $user->student->id)
                ->orWhere('email', $user->email);
        })
            ->with(['room', 'hostel', 'approvedBy'])
            ->latest()
            ->paginate(10);

        return view('bookings.my', compact('bookings'));
    }

    /**
     * Show bookings pending approval (Updated for guest bookings)
     */
    public function pendingApprovals()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $bookings = Booking::where('status', Booking::STATUS_PENDING)
                ->with(['room', 'hostel', 'user'])
                ->latest()
                ->paginate(10);
        } else if ($user->hasRole('hostel_manager')) {
            $hostelIds = Hostel::where('owner_id', $user->id)->pluck('id');
            $bookings = Booking::where('status', Booking::STATUS_PENDING)
                ->whereIn('hostel_id', $hostelIds)
                ->with(['room', 'hostel', 'user'])
                ->latest()
                ->paginate(10);
        } else {
            return back()->with('error', 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन।');
        }

        return view('bookings.pending', compact('bookings'));
    }

    /**
     * Show booking creation form (Updated for guest bookings)
     */
    public function create()
    {
        $organization = Organization::find(session('selected_organization_id'));

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->with('error', 'कृपया पहिले संस्था चयन गर्नुहोस्।');
        }

        $availableRooms = Room::where('organization_id', $organization->id)
            ->where('is_available', true)
            ->with('hostel')
            ->get();

        $isGuest = !Auth::check();

        return view('bookings.create', compact('availableRooms', 'organization', 'isGuest'));
    }

    /**
     * Show single booking details (Updated for guest access)
     */
    public function show($id)
    {
        $booking = Booking::with(['room.hostel', 'student.user', 'approvedBy'])->findOrFail($id);

        // ✅ Authorization check - allow guest access by email
        $user = Auth::user();
        if ($user->hasRole('student')) {
            $isUsersBooking = $booking->student_id == $user->student->id ||
                $booking->email == $user->email;
            if (!$isUsersBooking) {
                abort(403, 'तपाईंले यो बुकिंग हेर्न पाउनुहुन्न।');
            }
        }

        if ($user->hasRole('hostel_manager')) {
            $managedHostels = Hostel::where('owner_id', $user->id)->pluck('id');
            if (!$managedHostels->contains($booking->hostel_id)) {
                abort(403, 'तपाईंले यो बुकिंग हेर्न पाउनुहुन्न।');
            }
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Get bookings for specific hostel (Owner) - No changes needed
     */
    public function hostelBookings($hostelId)
    {
        $user = Auth::user();

        if (!$user->hasRole('hostel_manager')) {
            return back()->with('error', 'अनुमति छैन।');
        }

        // Verify the hostel belongs to the owner
        $hostel = Hostel::where('id', $hostelId)
            ->where('owner_id', $user->id)
            ->firstOrFail();

        $bookings = Booking::where('hostel_id', $hostelId)
            ->with(['student.user', 'room'])
            ->latest()
            ->paginate(15);

        return view('owner.bookings.hostel', compact('bookings', 'hostel'));
    }

    /**
     * ✅ NEW: Show guest booking success page
     */
    public function guestBookingSuccess($id)
    {
        $booking = Booking::findOrFail($id);
        return view('bookings.guest-success', compact('booking'));
    }

    /**
     * ✅ NEW: Convert guest booking to student booking
     */
    public function convertToStudentBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $user = Auth::user();

        // Check if booking belongs to this user's email
        if ($booking->guest_email !== $user->email) {
            return back()->with('error', 'यो बुकिंग तपाईंको इमेलसँग मेल खाँदैन।');
        }

        DB::beginTransaction();
        try {
            // Convert guest booking to student booking
            $booking->update([
                'user_id' => $user->id,
                'student_id' => $user->student->id,
                'is_guest_booking' => false,
                'email' => $user->email
            ]);

            // If booking is approved, create student record
            if ($booking->status === Booking::STATUS_APPROVED) {
                Student::createFromBooking($booking, $user);
            }

            DB::commit();
            return redirect()->route('bookings.my')
                ->with('success', 'बुकिंग सफलतापूर्वक तपाईंको खातामा जोडियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'बुकिंग रूपान्तरण गर्दा त्रुटि: ' . $e->getMessage());
        }
    }
}
