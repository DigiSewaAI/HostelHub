<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Hostel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Support\Facades\Storage;

class RoomsController extends Controller
{
    /**
     * कोठाहरूको सूची देखाउनुहोस्
     */
    public function index()
    {
        $rooms = Room::with('hostel')->latest()->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * नयाँ कोठा सिर्जना गर्ने फारम देखाउनुहोस्
     */
    public function create()
    {
        $hostels = Hostel::all();
        return view('admin.rooms.create', compact('hostels'));
    }

    /**
     * नयाँ कोठा डाटाबेसमा सुरक्षित राख्नुहोस्
     */
    public function store(StoreRoomRequest $request)
    {
        // Validate data using StoreRoomRequest (automatic)
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('room_images', 'public');
            $validated['image'] = $imagePath;
        }

        // Create room with Nepali success message
        Room::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'कोठा सफलतापूर्वक थपियो!');
    }

    /**
     * विशिष्ट कोठाको विवरण देखाउनुहोस्
     */
    public function show(Room $room)
    {
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * कोठा सम्पादन गर्ने फारम देखाउनुहोस्
     */
    public function edit(Room $room)
    {
        $hostels = Hostel::all();
        return view('admin.rooms.edit', compact('room', 'hostels'));
    }

    /**
     * डाटाबेसमा कोठा अपडेट गर्नुहोस्
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        // Validate data using UpdateRoomRequest (automatic)
        $validated = $request->validated();

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('room_images', 'public');
            $validated['image'] = $imagePath;
        }

        // Update room with Nepali success message
        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'कोठा सफलतापूर्वक अपडेट गरियो!');
    }

    /**
     * डाटाबेसबाट कोठा हटाउनुहोस्
     */
    public function destroy(Room $room)
    {
        // Delete associated image
        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'कोठा सफलतापूर्वक हटाइयो!');
    }

    /**
     * कोठा उपलब्धता जाँच गर्नुहोस्
     */
    public function availability()
    {
        $rooms = Room::with('hostel')->get();
        return view('admin.rooms.availability', compact('rooms'));
    }
}
