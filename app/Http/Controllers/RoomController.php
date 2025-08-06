<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource for public website.
     */
    public function index()
    {
        // सही तरिकाले उपलब्ध कोठाहरू प्राप्त गर्ने (SQL 1054 त्रुटि समाधान गर्ने)
        $rooms = Room::withCount('students')
            ->where('status', 'available')
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
        $room->loadCount('students');
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
            'room_number' => 'required|string|max:20|unique:rooms,room_number,'.$room->id,
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
        // कोठामा विद्यार्थीहरू छन् भने हटाउन नदिने
        if ($room->students()->count() > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'यो कोठामा विद्यार्थीहरू छन्, हटाउन सकिँदैन!');
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
        $currentOccupancy = $room->students()->count();

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
}
