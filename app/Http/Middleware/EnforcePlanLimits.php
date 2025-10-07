<?php

namespace App\Http\Middleware;

use App\Models\Hostel;
use App\Models\Student;
use App\Models\Room;
use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforcePlanLimits
{
    public function handle(Request $request, Closure $next, $type = null): Response
    {
        // Skip for non-authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Admin लाई सबै permission
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Owner को लागि मात्र limit check
        if ($user->hasRole('owner')) {
            $owner = $user->owner;

            if (!$owner) {
                return $next($request);
            }

            $subscription = $owner->subscription;

            if (!$subscription) {
                return $this->redirectWithError($type, 'तपाईंको कुनै सक्रिय सदस्यता छैन। कृपया सदस्यता सक्रिय गर्नुहोस्।');
            }

            // Check hostel limits
            if ($type === 'hostel' || $request->routeIs('owner.hostels.create') || $request->routeIs('owner.hostels.store')) {
                $currentHostels = Hostel::where('owner_id', $owner->id)->count();
                $hostelLimit = $subscription->hostel_limit ?? 1;

                if ($currentHostels >= $hostelLimit) {
                    $message = "तपाईंको सदस्यता योजनामा {$hostelLimit} होस्टेल मात्र सिर्जना गर्न सकिन्छ। (हाल {$currentHostels} होस्टेल छन्)";

                    if ($request->routeIs('owner.hostels.create')) {
                        return redirect()->route('owner.hostels.index')->with('error', $message);
                    } else {
                        return redirect()->back()->with('error', $message);
                    }
                }
            }

            // Check student limits
            if ($type === 'student' || $request->routeIs('owner.students.create') || $request->routeIs('owner.students.store')) {
                $studentsCount = Student::whereHas('user', function ($query) use ($owner) {
                    $query->whereHas('bookings', function ($q) use ($owner) {
                        $q->whereHas('room.hostel', function ($hostelQuery) use ($owner) {
                            $hostelQuery->where('owner_id', $owner->id);
                        });
                    });
                })->count();

                $studentLimit = $subscription->student_limit ?? 50;

                if ($studentsCount >= $studentLimit) {
                    $message = "तपाईंको सदस्यता योजनामा {$studentLimit} विद्यार्थीहरू मात्र दर्ता गर्न सकिन्छ। (हाल {$studentsCount} विद्यार्थीहरू छन्)";

                    if ($request->routeIs('owner.students.create')) {
                        return redirect()->route('owner.students.index')->with('error', $message);
                    } else {
                        return redirect()->back()->with('error', $message);
                    }
                }
            }

            // Check room limits
            if ($type === 'room' || $request->routeIs('owner.rooms.create') || $request->routeIs('owner.rooms.store')) {
                $hostelId = $request->route('hostel') ?? $request->hostel_id;
                $roomsCount = 0;

                if ($hostelId) {
                    // Specific hostel को लागि room count
                    $roomsCount = Room::where('hostel_id', $hostelId)->count();
                    $roomPerHostelLimit = $subscription->room_per_hostel_limit ?? 20;

                    if ($roomsCount >= $roomPerHostelLimit) {
                        $message = "तपाईंको सदस्यता योजनामा प्रति होस्टेल {$roomPerHostelLimit} कोठाहरू मात्र सिर्जना गर्न सकिन्छ। (हाल {$roomsCount} कोठाहरू छन्)";

                        if ($request->routeIs('owner.rooms.create')) {
                            return redirect()->route('owner.rooms.index', ['hostel' => $hostelId])->with('error', $message);
                        } else {
                            return redirect()->back()->with('error', $message);
                        }
                    }
                } else {
                    // Total rooms limit
                    $totalRoomsCount = Room::whereHas('hostel', function ($query) use ($owner) {
                        $query->where('owner_id', $owner->id);
                    })->count();

                    $totalRoomLimit = $subscription->total_room_limit ?? 100;

                    if ($totalRoomsCount >= $totalRoomLimit) {
                        $message = "तपाईंको सदस्यता योजनामा {$totalRoomLimit} कोठाहरू मात्र सिर्जना गर्न सकिन्छ। (हाल {$totalRoomsCount} कोठाहरू छन्)";

                        if ($request->routeIs('owner.rooms.create')) {
                            return redirect()->route('owner.rooms.index')->with('error', $message);
                        } else {
                            return redirect()->back()->with('error', $message);
                        }
                    }
                }
            }

            // Check booking limits
            if ($type === 'booking' || $request->routeIs('owner.bookings.approve') || $request->routeIs('owner.bookings.store')) {
                $currentBookings = $owner->bookings()->whereIn('status', ['approved', 'active'])->count();
                $bookingLimit = $subscription->booking_limit ?? 50;

                if ($currentBookings >= $bookingLimit) {
                    $message = "तपाईंको सदस्यता योजनामा {$bookingLimit} सक्रिय बुकिंगहरू मात्र राख्न सकिन्छ। (हाल {$currentBookings} सक्रिय बुकिंगहरू छन्)";

                    if ($request->routeIs('owner.bookings.approve')) {
                        return redirect()->route('owner.bookings.pending')->with('error', $message);
                    } else {
                        return redirect()->back()->with('error', $message);
                    }
                }
            }
        }

        // Student को लागि पनि केही limits check गर्न सकिन्छ
        if ($user->hasRole('student')) {
            // Example: Student को लागि active bookings limit
            if ($type === 'booking' || $request->routeIs('student.bookings.create') || $request->routeIs('student.bookings.store')) {
                $student = $user->student;
                $activeBookings = $student->bookings()->whereIn('status', ['approved', 'active'])->count();
                $maxActiveBookings = 3; // Student को लागि maximum active bookings

                if ($activeBookings >= $maxActiveBookings) {
                    $message = "तपाईंसँग {$maxActiveBookings} सक्रिय बुकिंगहरू छन्। नयाँ बुकिंग गर्न अघिल्लो बुकिंगहरू समाप्त हुनुपर्छ।";

                    if ($request->routeIs('student.bookings.create')) {
                        return redirect()->route('student.bookings.index')->with('error', $message);
                    } else {
                        return redirect()->back()->with('error', $message);
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Redirect with appropriate error message based on type
     */
    private function redirectWithError($type, $message)
    {
        switch ($type) {
            case 'hostel':
                return redirect()->route('owner.subscription.limits')->with('error', $message);

            case 'booking':
                return redirect()->route('owner.bookings.pending')->with('error', $message);

            case 'room':
                return redirect()->route('owner.rooms.index')->with('error', $message);

            case 'student':
                return redirect()->route('owner.students.index')->with('error', $message);

            default:
                return redirect()->route('owner.subscription.limits')->with('error', $message);
        }
    }

    /**
     * Check if subscription can add more hostels
     */
    private function canAddMoreHostels($subscription, $currentCount)
    {
        $limit = $subscription->hostel_limit ?? 1;
        return $currentCount < $limit;
    }

    /**
     * Check if subscription can add more students
     */
    private function canAddMoreStudents($subscription, $currentCount)
    {
        $limit = $subscription->student_limit ?? 50;
        return $currentCount < $limit;
    }

    /**
     * Check if subscription can add more rooms
     */
    private function canAddMoreRooms($subscription, $currentCount, $isPerHostel = false)
    {
        if ($isPerHostel) {
            $limit = $subscription->room_per_hostel_limit ?? 20;
        } else {
            $limit = $subscription->total_room_limit ?? 100;
        }
        return $currentCount < $limit;
    }

    /**
     * Check if subscription can have more bookings
     */
    private function canHaveMoreBookings($subscription, $currentCount)
    {
        $limit = $subscription->booking_limit ?? 50;
        return $currentCount < $limit;
    }
}
