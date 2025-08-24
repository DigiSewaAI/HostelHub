<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource for public website.
     */
    // app/Http/Controllers/Public/RoomController.php

    public function index()
    {
        $query = Room::withCount(['students' => function ($q) {
            $q->where('status', 'active');
        }]);

        // Filter by type (single, double, dorm)
        if (in_array(request('type'), ['single', 'double', 'dorm'])) {
            $query->where('type', request('type'));
        }

        $rooms = $query->where('status', 'available')
            ->having('students_count', '<', 'capacity')
            ->orderBy('price')
            ->paginate(12);

        return view('public.rooms', compact('rooms'));
    }

    /**
     * Display a listing of the resource for admin panel.
     */
    public function adminIndex()
    {
        $rooms = Room::withCount('students')
            ->latest()
            ->paginate(10);

        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_number' => 'required|string|max:20|unique:rooms',
            'floor' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,maintenance',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Room::create($request->all());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'कोठा सफलतापूर्वक थपियो!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->loadCount(['students' => function (Builder $query) {
            $query->where('status', 'active');
        }]);

        $availableCapacity = $room->capacity - $room->students_count;

        return view('public.rooms.show', compact('room', 'availableCapacity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'room_number' => 'required|string|max:20|unique:rooms,room_number,' . $room->id,
            'floor' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,maintenance',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $room->update($request->all());

        // कोठाको स्थिति अपडेट गर्ने
        $this->updateRoomStatus($room);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'कोठा सफलतापूर्वक अपडेट गरियो!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        // कोठामा सक्रिय विद्यार्थीहरू छन् भने हटाउन नदिने
        $activeStudentsCount = $room->students()->where('status', 'active')->count();

        if ($activeStudentsCount > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'यो कोठामा सक्रिय विद्यार्थीहरू छन्, हटाउन सकिँदैन!');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'कोठा सफलतापूर्वक हटाइयो!');
    }

    /**
     * Update room status based on current occupancy
     */
    private function updateRoomStatus(Room $room)
    {
        $currentOccupancy = $room->students()->where('status', 'active')->count();

        if ($currentOccupancy >= $room->capacity) {
            $room->update(['status' => 'occupied']);
        } elseif ($currentOccupancy == 0) {
            $room->update(['status' => 'available']);
        }
        // Maintenance status is managed separately
    }

    /**
     * Public method to get rooms with available capacity
     */
    public function publicIndex()
    {
        return $this->index();
    }

    /**
     * Display user's booking history
     *
     * @return \Illuminate\View\View
     */
    public function myBookings()
    {
        // प्रमाणित प्रयोगकर्ताको बुकिङहरू पुनःप्राप्त गर्ने
        $user = Auth::user();

        // Error handling for cases where user might not exist
        if (!$user) {
            return redirect()->route('login')->with('error', 'कृपया लगइन गर्नुहोस्।');
        }

        $bookings = Booking::where('user_id', $user->id)
            ->with(['room', 'hostel']) // सम्बन्धित डेटा
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookings.my-bookings', compact('bookings'));
    }
}
