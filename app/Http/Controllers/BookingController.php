<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'notes' => 'nullable|string|max:500'
        ]);

        $room = Room::findOrFail($request->room_id);
        $hostel = $room->hostel;
        $organization = $hostel->organization;
        $subscription = $organization->subscription;

        // Check room availability
        if (!$room->is_available) {
            return back()->with('error', 'यो कोठा अहिले उपलब्ध छैन।');
        }

        // Determine booking status based on subscription plan
        $status = $subscription && $subscription->requiresManualBookingApproval()
            ? Booking::STATUS_PENDING
            : Booking::STATUS_APPROVED;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $request->room_id,
            'hostel_id' => $hostel->id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'status' => $status,
            'amount' => $room->price,
            'payment_status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Auto-approve for Pro and Enterprise plans
        if ($status === Booking::STATUS_APPROVED) {
            $booking->approve(Auth::id());
            // Send notification to student
            $message = 'तपाईंको कोठा बुकिंग स्वतः स्वीकृत गरिएको छ।';
        } else {
            // Send notification to hostel manager for approval
            $message = 'तपाईंको कोठा बुकिंग सफलतापूर्वक पेश गरिएको छ। म्यानेजरद्वारा स्वीकृतिपछि तपाईंलाई सूचित गरिनेछ।';
        }

        return redirect()->route('bookings.my')->with('success', $message);
    }

    /**
     * Approve a booking
     */
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);

        // Check if user has permission to approve (hostel manager or admin)
        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->hasRole('hostel_manager')) {
            return back()->with('error', 'तपाईंसँग यो बुकिंग स्वीकृत गर्ने अनुमति छैन।');
        }

        if ($booking->approve($user->id)) {
            // Send notification to student
            return back()->with('success', 'बुकिंग सफलतापूर्वक स्वीकृत गरियो।');
        }

        return back()->with('error', 'बुकिंग स्वीकृत गर्दा त्रुटि भयो।');
    }

    /**
     * Reject a booking
     */
    public function reject(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->hasRole('hostel_manager')) {
            return back()->with('error', 'तपाईंसँग यो बुकिंग अस्वीकृत गर्ने अनुमति छैन।');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($booking->reject($user->id, $request->rejection_reason)) {
            // Send notification to student
            return back()->with('success', 'बुकिंग सफलतापूर्वक अस्वीकृत गरियो।');
        }

        return back()->with('error', 'बुकिंग अस्वीकृत गर्दा त्रुटि भयो।');
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

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
     * Show user's bookings
     */
    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['room', 'hostel', 'approvedBy'])
            ->latest()
            ->paginate(10);

        return view('bookings.my', compact('bookings'));
    }

    /**
     * Show bookings pending approval
     */
    public function pendingApprovals()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $bookings = Booking::pending()
                ->with(['room', 'hostel', 'user'])
                ->latest()
                ->paginate(10);
        } else if ($user->hasRole('hostel_manager')) {
            $hostelIds = Hostel::where('owner_id', $user->id)->pluck('id');
            $bookings = Booking::pending()
                ->whereIn('hostel_id', $hostelIds)
                ->with(['room', 'hostel', 'user'])
                ->latest()
                ->paginate(10);
        } else {
            return back()->with('error', 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन।');
        }

        return view('bookings.pending', compact('bookings'));
    }
}
