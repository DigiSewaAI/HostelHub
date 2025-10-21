<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    /**
     * Public room listing for website visitors
     */
    public function index()
    {
        // ✅ FIXED: Remove 'amenities' from with()
        $query = Room::with(['hostel']) // Remove 'amenities'
            ->where('status', 'available')
            ->whereHas('hostel', function ($query) {
                $query->where('status', 'active');
            });

        // Filter by room type
        if (request()->has('type') && in_array(request('type'), ['single', 'double', 'shared'])) {
            $query->where('type', request('type'));
        }

        // Filter by price range
        if (request()->has('min_price')) {
            $query->where('price', '>=', request('min_price'));
        }
        if (request()->has('max_price')) {
            $query->where('price', '<=', request('max_price'));
        }

        // ✅ FIXED: Change 'location' to 'city' (as per your hostel table)
        if (request()->has('location')) {
            $query->whereHas('hostel', function ($q) {
                $q->where('city', 'like', '%' . request('location') . '%');
            });
        }

        $rooms = $query->orderBy('price')->paginate(12);

        return view('frontend.rooms.rooms', compact('rooms'));
    }

    /**
     * Public room details page
     */
    public function show($id)
    {
        // ✅ FIXED: Remove 'amenities' from with()
        $room = Room::with(['hostel', 'reviews.user']) // Remove 'amenities'
            ->where('status', 'available')
            ->whereHas('hostel', function ($query) {
                $query->where('status', 'active');
            })
            ->findOrFail($id);

        // Calculate available capacity
        $currentOccupancy = $room->students()->where('status', 'active')->count();
        $availableCapacity = $room->capacity - $currentOccupancy;

        // Get related rooms
        $relatedRooms = Room::where('hostel_id', $room->hostel_id)
            ->where('id', '!=', $room->id)
            ->where('status', 'available')
            ->with('hostel')
            ->take(4)
            ->get();

        return view('frontend.rooms.show', compact('room', 'availableCapacity', 'relatedRooms'));
    }

    /**
     * Room search functionality for public
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'type' => 'nullable|in:single,double,shared',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0'
        ]);

        $query = Room::with('hostel')
            ->where('status', 'available')
            ->whereHas('hostel', function ($q) {
                $q->where('status', 'active');
            });

        // Search in room number and description
        $query->where(function ($q) use ($request) {
            $q->where('room_number', 'like', '%' . $request->query . '%')
                ->orWhere('description', 'like', '%' . $request->query . '%')
                ->orWhereHas('hostel', function ($q2) use ($request) {
                    // ✅ FIXED: Change 'location' to 'city'
                    $q2->where('name', 'like', '%' . $request->query . '%')
                        ->orWhere('city', 'like', '%' . $request->query . '%')
                        ->orWhere('address', 'like', '%' . $request->query . '%');
                });
        });

        // Apply filters
        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $rooms = $query->orderBy('price')->paginate(12);
        $searchQuery = $request->query;

        return view('frontend.rooms.search', compact('rooms', 'searchQuery'));
    }

    /**
     * Check room availability
     */
    public function checkAvailability(Request $request, Room $room)
    {
        $request->validate([
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in'
        ]);

        $isAvailable = !$room->bookings()
            ->where(function ($query) use ($request) {
                $query->whereBetween('check_in', [$request->check_in, $request->check_out])
                    ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('check_in', '<=', $request->check_in)
                            ->where('check_out', '>=', $request->check_out);
                    });
            })
            ->whereIn('status', ['confirmed', 'pending'])
            ->exists();

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'कोठा उपलब्ध छ' : 'कोठा उपलब्ध छैन'
        ]);
    }

    /**
     * Display user's booking history (for authenticated users)
     */
    public function myBookings()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'कृपया लगइन गर्नुहोस्।');
        }

        $bookings = Booking::where('user_id', $user->id)
            ->with(['room.hostel'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.bookings.index', compact('bookings'));
    }

    /**
     * Get rooms by hostel
     */
    public function byHostel(Hostel $hostel)
    {
        // ✅ FIXED: Only show rooms from active hostels
        if ($hostel->status !== 'active') {
            abort(404, 'यो होस्टेल हाल उपलब्ध छैन');
        }

        // ✅ FIXED: Remove 'amenities' from with()
        $rooms = Room::where('hostel_id', $hostel->id)
            ->where('status', 'available')
            ->with('hostel') // Remove 'amenities'
            ->orderBy('price')
            ->paginate(12);

        return view('frontend.rooms.by-hostel', compact('rooms', 'hostel'));
    }

    /**
     * Owner room listing (for owner dashboard)
     */
    public function ownerIndex()
    {
        try {
            $organizationId = session('current_organization_id');

            // ✅ FIXED: Use 'status' column instead of 'is_active'
            $rooms = Room::where('status', 'available')
                ->whereHas('hostel', function ($query) use ($organizationId) {
                    $query->where('organization_id', $organizationId)
                        ->where('status', 'active');
                })
                ->with(['hostel'])
                ->paginate(10);

            return view('owner.rooms.rooms', compact('rooms'));
        } catch (\Exception $e) {
            Log::error('Room index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Rooms load गर्न असफल भयो');
        }
    }

    /**
     * Student room listing (for student dashboard)
     */
    public function studentIndex()
    {
        try {
            $rooms = Room::where('status', 'available')
                ->whereHas('hostel', function ($query) {
                    $query->where('status', 'active');
                })
                ->with(['hostel'])
                ->paginate(10);

            return view('student.rooms.rooms', compact('rooms'));
        } catch (\Exception $e) {
            Log::error('Student room index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Rooms load गर्न असफल भयो');
        }
    }

    /**
     * Student room details
     */
    public function studentShow($id)
    {
        // ✅ FIXED: Remove 'amenities' from with()
        $room = Room::with(['hostel']) // Remove 'amenities'
            ->where('status', 'available')
            ->whereHas('hostel', function ($query) {
                $query->where('status', 'active');
            })
            ->findOrFail($id);

        return view('student.rooms.show', compact('room'));
    }
}
