<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\Hostel;
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

        // Get hostels managed by this user in current organization
        $hostelIds = Hostel::where('organization_id', $organizationId)
            ->pluck('id');

        $query = BookingRequest::with(['hostel', 'room'])
            ->whereIn('hostel_id', $hostelIds)
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by hostel
        if ($request->has('hostel_id') && $request->hostel_id) {
            $query->where('hostel_id', $request->hostel_id);
        }

        $bookingRequests = $query->paginate(15);
        $hostels = Hostel::where('organization_id', $organizationId)->get();

        $stats = [
            'total' => BookingRequest::whereIn('hostel_id', $hostelIds)->count(),
            'pending' => BookingRequest::whereIn('hostel_id', $hostelIds)->where('status', 'pending')->count(),
            'approved' => BookingRequest::whereIn('hostel_id', $hostelIds)->where('status', 'approved')->count(),
            'rejected' => BookingRequest::whereIn('hostel_id', $hostelIds)->where('status', 'rejected')->count(),
        ];

        return view('owner.booking-requests.index', compact(
            'bookingRequests',
            'hostels',
            'stats'
        ));
    }

    /**
     * Show specific booking request
     */
    public function show(BookingRequest $bookingRequest)
    {
        $this->authorizeAccess($bookingRequest);

        $bookingRequest->load(['hostel', 'room']);

        return view('owner.booking-requests.show', compact('bookingRequest'));
    }

    /**
     * Approve booking request
     */
    public function approve(Request $request, BookingRequest $bookingRequest)
    {
        $this->authorizeAccess($bookingRequest);

        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        try {
            $bookingRequest->approve($request->admin_notes);

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

            // TODO: Send approval notification to guest

            return redirect()->route('owner.booking-requests.index')
                ->with('success', 'बुकिंग अनुरोध सफलतापूर्वक स्वीकृत गरियो');
        } catch (\Exception $e) {
            \Log::error('Approve booking error: ' . $e->getMessage());
            return back()->with('error', 'बुकिंग स्वीकृत गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Reject booking request
     */
    public function reject(Request $request, BookingRequest $bookingRequest)
    {
        $this->authorizeAccess($bookingRequest);

        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        try {
            $bookingRequest->reject($request->admin_notes);

            // TODO: Send rejection notification to guest

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
        $organizationId = session('current_organization_id');
        $hostelIds = Hostel::where('organization_id', $organizationId)->pluck('id');

        return [
            'pending_count' => BookingRequest::whereIn('hostel_id', $hostelIds)
                ->where('status', 'pending')
                ->count()
        ];
    }

    /**
     * Authorize access to booking request
     */
    private function authorizeAccess(BookingRequest $bookingRequest)
    {
        $organizationId = session('current_organization_id');
        $hostelIds = Hostel::where('organization_id', $organizationId)->pluck('id');

        if (!$hostelIds->contains($bookingRequest->hostel_id)) {
            abort(403, 'तपाईंसँग यो बुकिंग अनुरोध हेर्ने अनुमति छैन');
        }
    }
}
