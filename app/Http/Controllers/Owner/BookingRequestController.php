<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\Hostel;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingRequestController extends Controller
{
    /**
     * Display a listing of booking requests.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $organizationId = session('current_organization_id');

        // ✅ CRITICAL FIX: Get hostels managed by this user (owner) in current organization
        $hostelIds = Hostel::where('organization_id', $organizationId)
            ->where('owner_id', $user->id)
            ->pluck('id');

        // ✅ FIXED: Query both BookingRequest AND Booking models
        $bookingRequestsQuery = BookingRequest::with(['hostel', 'room'])
            ->whereIn('hostel_id', $hostelIds);

        $bookingsQuery = Booking::with(['hostel', 'room', 'user'])
            ->whereIn('hostel_id', $hostelIds)
            ->where('status', 'pending');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $bookingRequestsQuery->where('status', $request->status);
            $bookingsQuery->where('status', $request->status);
        }

        // Filter by hostel
        if ($request->has('hostel_id') && $request->hostel_id) {
            $bookingRequestsQuery->where('hostel_id', $request->hostel_id);
            $bookingsQuery->where('hostel_id', $request->hostel_id);
        }

        $bookingRequests = $bookingRequestsQuery->latest()->get();
        $bookings = $bookingsQuery->latest()->get();

        // Combine both types of requests
        $allRequests = $bookingRequests->merge($bookings)->sortByDesc('created_at');

        $hostels = Hostel::where('organization_id', $organizationId)
            ->where('owner_id', $user->id)
            ->get();

        $stats = [
            'total' => $allRequests->count(),
            'pending' => $allRequests->where('status', 'pending')->count(),
            'approved' => $allRequests->where('status', 'approved')->count(),
            'rejected' => $allRequests->where('status', 'rejected')->count(),
        ];

        return view('owner.booking-requests.index', compact(
            'allRequests',
            'hostels',
            'stats'
        ));
    }

    /**
     * Show specific booking request - FIXED VERSION
     */
    public function show($id)
    {
        $user = Auth::user();
        $organizationId = session('current_organization_id');

        // ✅ CRITICAL FIX: Get hostels managed by this user
        $hostelIds = Hostel::where('organization_id', $organizationId)
            ->where('owner_id', $user->id)
            ->pluck('id');

        // ✅ FIXED: Find in both models WITH hostel filtering
        $bookingRequest = BookingRequest::whereIn('hostel_id', $hostelIds)->find($id);

        if (!$bookingRequest) {
            $bookingRequest = Booking::whereIn('hostel_id', $hostelIds)->find($id);
        }

        if (!$bookingRequest) {
            abort(404, 'बुकिंग अनुरोध फेला परेन वा तपाईंसँग यसलाई हेर्ने अनुमति छैन');
        }

        $bookingRequest->load(['hostel', 'room']);

        return view('owner.booking-requests.show', compact('bookingRequest'));
    }

    /**
     * Approve booking request - FIXED VERSION
     */
    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        $organizationId = session('current_organization_id');

        // ✅ CRITICAL FIX: Get hostels managed by this user
        $hostelIds = Hostel::where('organization_id', $organizationId)
            ->where('owner_id', $user->id)
            ->pluck('id');

        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        // ✅ FIXED: Find in both models WITH hostel filtering
        $bookingRequest = BookingRequest::whereIn('hostel_id', $hostelIds)->find($id);
        $isBooking = false;

        if (!$bookingRequest) {
            $bookingRequest = Booking::whereIn('hostel_id', $hostelIds)->find($id);
            $isBooking = true;
        }

        if (!$bookingRequest) {
            abort(404, 'बुकिंग अनुरोध फेला परेन वा तपाईंसँग यसलाई स्वीकृत गर्ने अनुमति छैन');
        }

        try {
            if ($isBooking) {
                // Handle Booking approval
                $bookingRequest->update([
                    'status' => 'approved',
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'admin_notes' => $request->admin_notes
                ]);

                // Update room occupancy
                if ($bookingRequest->room_id) {
                    $room = $bookingRequest->room;
                    $room->current_occupancy += 1;
                    $room->available_beds = max(0, $room->capacity - $room->current_occupancy);

                    if ($room->current_occupancy >= $room->capacity) {
                        $room->status = 'occupied';
                    } else {
                        $room->status = 'partially_available';
                    }

                    $room->save();
                }
            } else {
                // Handle BookingRequest approval
                $bookingRequest->update([
                    'status' => 'approved',
                    'admin_notes' => $request->admin_notes,
                    'approved_at' => now(),
                    'approved_by' => $user->id
                ]);

                // Update room occupancy if specific room is assigned
                if ($bookingRequest->room_id) {
                    $room = $bookingRequest->room;
                    $room->current_occupancy += 1;
                    $room->available_beds = max(0, $room->capacity - $room->current_occupancy);

                    if ($room->current_occupancy >= $room->capacity) {
                        $room->status = 'occupied';
                    } else {
                        $room->status = 'partially_available';
                    }

                    $room->save();
                }
            }

            return redirect()->route('owner.booking-requests.index')
                ->with('success', 'बुकिंग अनुरोध सफलतापूर्वक स्वीकृत गरियो');
        } catch (\Exception $e) {
            \Log::error('Approve booking error: ' . $e->getMessage());
            return back()->with('error', 'बुकिंग स्वीकृत गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Reject booking request - FIXED VERSION
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $organizationId = session('current_organization_id');

        // ✅ CRITICAL FIX: Get hostels managed by this user
        $hostelIds = Hostel::where('organization_id', $organizationId)
            ->where('owner_id', $user->id)
            ->pluck('id');

        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        // ✅ FIXED: Find in both models WITH hostel filtering
        $bookingRequest = BookingRequest::whereIn('hostel_id', $hostelIds)->find($id);
        $isBooking = false;

        if (!$bookingRequest) {
            $bookingRequest = Booking::whereIn('hostel_id', $hostelIds)->find($id);
            $isBooking = true;
        }

        if (!$bookingRequest) {
            abort(404, 'बुकिंग अनुरोध फेला परेन वा तपाईंसँग यसलाई अस्वीकृत गर्ने अनुमति छैन');
        }

        try {
            if ($isBooking) {
                // Handle Booking rejection
                $bookingRequest->update([
                    'status' => 'rejected',
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'rejection_reason' => $request->admin_notes
                ]);
            } else {
                // Handle BookingRequest rejection
                $bookingRequest->update([
                    'status' => 'rejected',
                    'admin_notes' => $request->admin_notes,
                    'rejected_at' => now(),
                    'approved_by' => $user->id
                ]);
            }

            return redirect()->route('owner.booking-requests.index')
                ->with('success', 'बुकिंग अनुरोध सफलतापूर्वक अस्वीकृत गरियो');
        } catch (\Exception $e) {
            \Log::error('Reject booking error: ' . $e->getMessage());
            return back()->with('error', 'बुकिंग अस्वीकृत गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Get booking request counts for dashboard
     */
    public function getCounts()
    {
        $user = Auth::user();
        $organizationId = session('current_organization_id');

        $hostelIds = Hostel::where('organization_id', $organizationId)
            ->where('owner_id', $user->id)
            ->pluck('id');

        $bookingRequestsCount = BookingRequest::whereIn('hostel_id', $hostelIds)
            ->where('status', 'pending')
            ->count();

        $bookingsCount = Booking::whereIn('hostel_id', $hostelIds)
            ->where('status', 'pending')
            ->count();

        return [
            'pending_count' => $bookingRequestsCount + $bookingsCount
        ];
    }
}
