<?php

namespace App\Http\Controllers\Owner;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomsController extends Controller
{
    public function index()
    {
        $rooms = Room::where('hostel_id', auth()->user()->hostel_id)->get();
        return view('owner.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('owner.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,NULL,id,hostel_id,' . auth()->user()->hostel_id,
            'type' => 'required|in:single,double,shared',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        Room::create([
            'hostel_id' => auth()->user()->hostel_id,
            'room_number' => $request->room_number,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('owner.rooms.index')
            ->with('success', 'कोठा सफलतापूर्वक थपियो!');
    }

    public function edit(Room $room)
    {
        if ($room->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो कोठा सम्पादन गर्ने अनुमति छैन');
        }

        return view('owner.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        if ($room->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो कोठा सम्पादन गर्ने अनुमति छैन');
        }

        $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,' . $room->id . ',id,hostel_id,' . auth()->user()->hostel_id,
            'type' => 'required|in:single,double,shared',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        $room->update($request->all());

        return redirect()->route('owner.rooms.index')
            ->with('success', 'कोठा सफलतापूर्वक अद्यावधिक गरियो!');
    }

    public function destroy(Room $room)
    {
        if ($room->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो कोठा हटाउने अनुमति छैन');
        }

        $room->delete();

        return redirect()->route('owner.rooms.index')
            ->with('success', 'कोठा सफलतापूर्वक हटाइयो!');
    }
}
