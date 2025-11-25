<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Store a new booking (Email dispatch COMPLETELY REMOVED)
     */
    public function store(Request $request)
    {
        // ✅ FIXED: Better guest detection
        $isGuest = !Auth::check();

        // ✅ FIXED: Updated validation with after_or_equal
        $validationRules = [
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'nullable|date|after:check_in_date',
            'notes' => 'nullable|string|max:500',
        ];

        // ✅ FIXED: Add guest fields validation if it's a guest booking
        if ($isGuest) {
            $validationRules['guest_name'] = 'required|string|max:255';
            $validationRules['guest_email'] = 'required|email|max:255';
            $validationRules['guest_phone'] = 'required|string|max:20';
        } else {
            // ✅ FIXED: For authenticated users, use these fields from form
            $validationRules['name'] = 'sometimes|string|max:255';
            $validationRules['phone'] = 'sometimes|string|max:20';
            $validationRules['email'] = 'sometimes|email|max:255';
        }

        $validatedData = $request->validate($validationRules);

        $room = Room::findOrFail($request->room_id);
        $hostel = $room->hostel;
        $organization = $room->hostel->organization;

        // ✅ FIXED: Get subscription correctly - use the relationship, not the method
        $subscription = $organization ? $organization->currentSubscription : null;

        Log::info('🔍 ROOM AVAILABILITY CHECK', [
            'room_id' => $room->id,
            'room_number' => $room->room_number,
            'status' => $room->status,
            'available_beds' => $room->available_beds,
            'capacity' => $room->capacity,
            'current_occupancy' => $room->current_occupancy
        ]);

        // ✅ FIXED: SIMPLIFIED availability check - Use the room's own available_beds
        if ($room->available_beds <= 0) {
            Log::warning('❌ Room not available - available_beds <= 0', [
                'room_id' => $room->id,
                'available_beds' => $room->available_beds
            ]);
            return back()->withInput()->with('error', 'यो कोठा अहिले उपलब्ध छैन। कृपया अर्को कोठा छान्नुहोस्।');
        }

        // ✅ FIXED: Check room status
        if ($room->status !== 'उपलब्ध' && $room->status !== 'available') {
            Log::warning('❌ Room status not available', [
                'room_id' => $room->id,
                'status' => $room->status
            ]);
            return back()->withInput()->with('error', 'यो कोठा अहिले उपलब्ध छैन। कृपया अर्को कोठा छान्नुहोस्।');
        }

        // ✅ FIXED: SIMPLIFIED booking conflicts check
        if ($request->check_in_date && $request->check_out_date) {
            $conflictingBookings = $room->bookings()
                ->where('status', Booking::STATUS_APPROVED) // ✅ ONLY approved bookings matter
                ->where(function ($query) use ($request) {
                    $query->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                        ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('check_in_date', '<=', $request->check_in_date)
                                ->where('check_out_date', '>=', $request->check_out_date);
                        });
                })
                ->count();

            if ($conflictingBookings > 0) {
                Log::warning('❌ Room has conflicting bookings', [
                    'room_id' => $room->id,
                    'conflicting_count' => $conflictingBookings
                ]);
                return back()->withInput()->with('error', 'यो कोठा उक्त मितिहरूमा पहिले नै बुक गरिएको छ। कृपया अर्को मिति वा कोठा छान्नुहोस्।');
            }
        }

        // ✅ FIXED: For students: Check active bookings limit
        if (Auth::check() && !$isGuest) {
            $student = Auth::user()->student;
            $activeBookingsCount = Booking::where('student_id', $student->id)
                ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_APPROVED])
                ->count();

            if ($activeBookingsCount >= 3) {
                return back()->with('error', 'तपाईंसँग ३ वटा सक्रिय बुकिंग छन्। नयाँ बुकिंग गर्न अगाडिको बुकिंग समाप्त हुनुपर्छ।');
            }
        }

        // ✅ FIXED: Determine booking status based on subscription plan
        $status = $subscription && method_exists($subscription, 'requiresManualBookingApproval') && $subscription->requiresManualBookingApproval()
            ? Booking::STATUS_PENDING
            : Booking::STATUS_APPROVED;

        DB::beginTransaction();

        try {
            $bookingData = [
                'room_id' => $request->room_id,
                'hostel_id' => $hostel->id,
                'organization_id' => $organization ? $organization->id : null,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'status' => $status,
                'amount' => $room->price,
                'payment_status' => 'pending',
                'notes' => $request->notes,
                'booking_date' => now(),
            ];

            // ✅ FIXED: Set user/student fields for authenticated users with form data
            if (Auth::check() && !$isGuest) {
                $student = Auth::user()->student;
                $bookingData['student_id'] = $student->id;
                $bookingData['user_id'] = Auth::id();
                $bookingData['email'] = $request->email ?? Auth::user()->email;
                $bookingData['is_guest_booking'] = false;
                // ✅ FIXED: Store name and phone from form for authenticated users
                $bookingData['guest_name'] = $request->name ?? Auth::user()->name;
                $bookingData['guest_phone'] = $request->phone;
            } else {
                // ✅ FIXED: Set guest fields for guest bookings
                $bookingData['guest_name'] = $request->guest_name;
                $bookingData['guest_email'] = $request->guest_email;
                $bookingData['guest_phone'] = $request->guest_phone;
                $bookingData['is_guest_booking'] = true;
                $bookingData['email'] = $request->guest_email;
            }

            $booking = Booking::create($bookingData);

            // ✅ FIXED: Auto-approve for Pro and Enterprise plans
            if ($status === Booking::STATUS_APPROVED) {
                $booking->update([
                    'approved_by' => Auth::check() ? Auth::id() : null,
                    'approved_at' => now()
                ]);

                // ✅ FIXED: Update room availability - ONLY if booking is approved
                $room->available_beds = $room->available_beds - 1;
                if ($room->available_beds <= 0) {
                    $room->status = 'अनुपलब्ध';
                }
                $room->save();

                Log::info('✅ Room availability updated after approval', [
                    'room_id' => $room->id,
                    'new_available_beds' => $room->available_beds,
                    'new_status' => $room->status
                ]);

                $message = $isGuest
                    ? 'तपाईंको कोठा बुकिंग स्वतः स्वीकृत गरिएको छ।'
                    : 'तपाईंको कोठा बुकिंग स्वतः स्वीकृत गरिएको छ।';

                // ✅ FIXED: AUTOMATIC PAYMENT REDIRECT ONLY FOR STUDENTS
                if (Auth::check() && !$isGuest) {
                    $payment = Payment::create([
                        'organization_id' => $organization ? $organization->id : null,
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
                    ? 'तपाईंको कोठा बुकिंग सफलतापूर्वक पेश गरिएको छ। म्यानेजरद्वारा स्वीकृतिपछि तपाईंलाई सूचित गरिनेछ।'
                    : 'तपाईंको कोठा बुकिंग सफलतापूर्वक पेश गरिएको छ। म्यानेजरद्वारा स्वीकृतिपछि तपाईंलाई सूचित गरिनेछ।';
            }

            // ❌❌❌ COMPLETELY REMOVED: Email dispatch code
            Log::info('✅ Booking created successfully without email. Booking ID: ' . $booking->id);

            DB::commit();

            // ✅ FIXED: Redirect based on user type to NEW success route
            if (Auth::check() && !$isGuest) {
                return redirect()->route('bookings.my')->with('success', $message);
            } else {
                return redirect()->route('frontend.booking.success', $booking->id)
                    ->with('success', $message)
                    ->with('booking_id', $booking->id)
                    ->with('guest_email', $request->guest_email ?? $request->email);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Booking store error: ' . $e->getMessage());
            return back()->with('error', 'बुकिंग सिर्जना गर्दा त्रुटि भयो। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    public function createFromGallery($slug)
    {
        $hostel = Hostel::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $organization = $hostel->organization;

        // ✅ FIXED: CORRECTED room query - Only count APPROVED bookings for availability
        $availableRooms = Room::where('hostel_id', $hostel->id)
            ->where('status', 'उपलब्ध')
            ->with('hostel')
            ->get()
            ->map(function ($room) {
                // ✅ FIXED: Calculate actual available beds based on APPROVED bookings
                $approvedBookingsCount = $room->bookings()
                    ->where('status', Booking::STATUS_APPROVED)
                    ->count();

                $actualAvailableBeds = $room->capacity - $approvedBookingsCount;

                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'type' => $room->type,
                    'nepali_type' => $room->nepali_type,
                    'capacity' => $room->capacity,
                    'available_beds' => $actualAvailableBeds, // ✅ Use calculated available beds
                    'price' => $room->price,
                    'label' => "{$room->nepali_type} - कोठा {$room->room_number} (उपलब्ध: {$actualAvailableBeds}, रु {$room->price})"
                ];
            })->filter(function ($room) {
                // Only include rooms with available beds
                return $room['available_beds'] > 0;
            });

        $isGuest = !Auth::check();
        $selectedRoom = null;

        // If room_id is provided in query, pre-fill it
        if (request()->has('room_id')) {
            $selectedRoom = Room::find(request('room_id'));
            // ✅ FIXED: Calculate available beds for selected room too
            if ($selectedRoom) {
                $approvedBookingsCount = $selectedRoom->bookings()
                    ->where('status', Booking::STATUS_APPROVED)
                    ->count();
                $selectedRoom->available_beds = $selectedRoom->capacity - $approvedBookingsCount;
            }
        }

        // ✅ ADDED: Required variables for the form.blade.php
        $datesLocked = false;
        $checkIn = null;
        $checkOut = null;

        return view('frontend.booking.form', compact(
            'availableRooms',
            'organization',
            'isGuest',
            'hostel',
            'selectedRoom',
            'datesLocked',
            'checkIn',
            'checkOut'
        ));
    }

    /**
     * Approve a booking (Email dispatch COMPLETELY REMOVED)
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

            // ✅ FIXED: CORRECTED availability check - Only count APPROVED bookings
            $approvedBookingsCount = $booking->room->bookings()
                ->where('status', Booking::STATUS_APPROVED)
                ->count();

            $actualAvailableBeds = $booking->room->capacity - $approvedBookingsCount;

            if ($booking->room->status !== 'उपलब्ध' || $actualAvailableBeds <= 0) {
                return back()->with('error', 'यो कोठा अहिले उपलब्ध छैन।');
            }

            $booking->update([
                'status' => Booking::STATUS_APPROVED,
                'approved_by' => $user->id,
                'approved_at' => now()
            ]);

            // ✅ FIXED: CORRECTED room update - Only update status, not available_beds
            $room = $booking->room;
            $approvedBookingsCount = $room->bookings()
                ->where('status', Booking::STATUS_APPROVED)
                ->count();

            $actualAvailableBeds = $room->capacity - $approvedBookingsCount;

            if ($actualAvailableBeds <= 0) {
                $room->status = 'अनुपलब्ध';
            } else {
                $room->status = 'उपलब्ध';
            }
            $room->save();

            // ✅ AUTO-CREATE STUDENT RECORD IF GUEST BOOKING HAS USER
            if ($booking->is_guest_booking && $booking->user_id) {
                try {
                    $user = $booking->user;
                    Student::createFromBooking($booking, $user);
                } catch (\Exception $e) {
                    Log::error('Student creation from booking failed: ' . $e->getMessage());
                }
            }

            // ❌❌❌ COMPLETELY REMOVED: Email dispatch code
            Log::info('Booking approved without email. Booking ID: ' . $booking->id);

            DB::commit();

            return back()->with('success', 'बुकिंग सफलतापूर्वक स्वीकृत गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'बुकिंग स्वीकृत गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Reject a booking (Email dispatch COMPLETELY REMOVED)
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

            // ❌❌❌ COMPLETELY REMOVED: Email dispatch code
            Log::info('Booking rejected without email. Booking ID: ' . $booking->id);

            DB::commit();

            return back()->with('success', 'बुकिंग सफलतापूर्वक अस्वीकृत गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'बुकिंग अस्वीकृत गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a booking (Updated for room availability)
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

            // ✅ FIXED: CORRECTED room availability update - Recalculate based on approved bookings
            $room = $booking->room;
            $approvedBookingsCount = $room->bookings()
                ->where('status', Booking::STATUS_APPROVED)
                ->count();

            $actualAvailableBeds = $room->capacity - $approvedBookingsCount;

            if ($actualAvailableBeds > 0) {
                $room->status = 'उपलब्ध';
            } else {
                $room->status = 'अनुपलब्ध';
            }
            $room->save();

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

        // ✅ FIXED: CORRECTED room query - Only count APPROVED bookings for availability
        $availableRooms = Room::where('organization_id', $organization->id)
            ->where('status', 'उपलब्ध')
            ->with('hostel')
            ->get()
            ->map(function ($room) {
                // Calculate actual available beds based on APPROVED bookings
                $approvedBookingsCount = $room->bookings()
                    ->where('status', Booking::STATUS_APPROVED)
                    ->count();

                $actualAvailableBeds = $room->capacity - $approvedBookingsCount;

                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'type' => $room->type,
                    'nepali_type' => $room->nepali_type,
                    'capacity' => $room->capacity,
                    'available_beds' => $actualAvailableBeds, // ✅ Use calculated available beds
                    'price' => $room->price,
                    'label' => "{$room->nepali_type} - कोठा {$room->room_number} (उपलब्ध: {$actualAvailableBeds}, रु {$room->price})"
                ];
            })->filter(function ($room) {
                // Only include rooms with available beds
                return $room['available_beds'] > 0;
            });

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
        return view('frontend.booking.success', compact('booking'));
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

    /**
     * ✅ NEW: Get available rooms for specific dates
     */
    public function getAvailableRooms(Request $request, $hostelId)
    {
        try {
            $hostel = Hostel::findOrFail($hostelId);

            $checkIn = $request->get('check_in');
            $checkOut = $request->get('check_out');

            // Base query for available rooms
            $roomsQuery = $hostel->rooms()
                ->where('status', 'उपलब्ध');

            // If dates provided, check room availability for those dates
            if ($checkIn && $checkOut) {
                $roomsQuery->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                    $q->where('status', Booking::STATUS_APPROVED) // ✅ ONLY approved bookings block availability
                        ->where(function ($bookingQuery) use ($checkIn, $checkOut) {
                            $bookingQuery->whereBetween('check_in_date', [$checkIn, $checkOut])
                                ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                                ->orWhere(function ($subQuery) use ($checkIn, $checkOut) {
                                    $subQuery->where('check_in_date', '<=', $checkIn)
                                        ->where('check_out_date', '>=', $checkOut);
                                });
                        });
                });
            }

            $rooms = $roomsQuery->get()->map(function ($room) {
                // ✅ FIXED: Calculate actual available beds based on APPROVED bookings
                $approvedBookingsCount = $room->bookings()
                    ->where('status', Booking::STATUS_APPROVED)
                    ->count();

                $actualAvailableBeds = $room->capacity - $approvedBookingsCount;

                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'type' => $room->type,
                    'nepali_type' => $room->nepali_type,
                    'capacity' => $room->capacity,
                    'available_beds' => $actualAvailableBeds, // ✅ Use calculated available beds
                    'price' => $room->price,
                    'formatted_price' => 'रु ' . number_format($room->price),
                    'description' => $room->description,
                    'label' => "{$room->nepali_type} - कोठा {$room->room_number} (उपलब्ध: {$actualAvailableBeds}, रु {$room->price})"
                ];
            })->filter(function ($room) {
                // Only include rooms with available beds
                return $room['available_beds'] > 0;
            });

            return response()->json([
                'success' => true,
                'rooms' => $rooms,
                'hostel' => [
                    'name' => $hostel->name,
                    'id' => $hostel->id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get available rooms error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'कोठा डाटा लोड गर्न असफल'
            ], 500);
        }
    }

    /**
     * ✅ NEW: Check room availability for specific dates
     */
    public function checkRoomAvailability(Request $request, $roomId)
    {
        try {
            $room = Room::findOrFail($roomId);

            $checkIn = $request->get('check_in');
            $checkOut = $request->get('check_out');

            if (!$checkIn || !$checkOut) {
                return response()->json([
                    'success' => false,
                    'message' => 'कृपया चेक-इन र चेक-आउट मिति प्रदान गर्नुहोस्'
                ], 422);
            }

            // ✅ FIXED: CORRECTED availability check - Only count APPROVED bookings
            $approvedBookingsCount = $room->bookings()
                ->where('status', Booking::STATUS_APPROVED)
                ->count();

            $actualAvailableBeds = $room->capacity - $approvedBookingsCount;

            if ($room->status !== 'उपलब्ध' || $actualAvailableBeds <= 0) {
                return response()->json([
                    'success' => false,
                    'available' => false,
                    'message' => 'यो कोठा अहिले उपलब्ध छैन'
                ]);
            }

            // ✅ FIXED: Check for booking conflicts - Only count APPROVED bookings
            $conflictingBookings = $room->bookings()
                ->where('status', Booking::STATUS_APPROVED) // ✅ ONLY approved bookings block availability
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in_date', '<=', $checkIn)
                                ->where('check_out_date', '>=', $checkOut);
                        });
                })
                ->count();

            $isAvailable = $conflictingBookings === 0;

            return response()->json([
                'success' => true,
                'available' => $isAvailable,
                'message' => $isAvailable
                    ? 'कोठा उपलब्ध छ'
                    : 'यो कोठा उक्त मितिहरूमा पहिले नै बुक गरिएको छ',
                'room' => [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'type' => $room->nepali_type,
                    'price' => $room->price,
                    'formatted_price' => 'रु ' . number_format($room->price)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Check room availability error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'उपलब्धता जाँच गर्दा त्रुटि'
            ], 500);
        }
    }
}
