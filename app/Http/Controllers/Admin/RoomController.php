<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Hostel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms.
     */
    public function index()
    {
        $rooms = Room::with('hostel')->latest()->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        $hostels = Hostel::all();
        return view('admin.rooms.create', compact('hostels'));
    }

    /**
     * Store a newly created room in database.
     */
    public function store(StoreRoomRequest $request)
    {
        try {
            $validated = $request->validated();

            // Upload image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('room_images', 'public');
                $validated['image'] = $imagePath;
            }

            Room::create($validated);

            return redirect()->route('admin.rooms.index')
                ->with('success', 'कोठा सफलतापूर्वक थपियो!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'कोठा थप्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room)
    {
        $room->load('hostel');
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        $hostels = Hostel::all();
        return view('admin.rooms.edit', compact('room', 'hostels'));
    }

    /**
     * Update the specified room in database.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        try {
            $validated = $request->validated();

            // Update image
            if ($request->hasFile('image')) {
                // Delete old image
                if ($room->image) {
                    Storage::disk('public')->delete($room->image);
                }

                // Save new image
                $imagePath = $request->file('image')->store('room_images', 'public');
                $validated['image'] = $imagePath;
            }

            $room->update($validated);

            return redirect()->route('admin.rooms.index')
                ->with('success', 'कोठा सफलतापूर्वक अपडेट गरियो!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'कोठा अपडेट गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified room from database.
     */
    public function destroy(Room $room)
    {
        try {
            // Delete related image
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }

            $room->delete();

            return redirect()->route('admin.rooms.index')
                ->with('success', 'कोठा सफलतापूर्वक हटाइयो!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'कोठा हटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Check room availability.
     */
    public function availability()
    {
        $rooms = Room::with('hostel')->get();
        return view('admin.rooms.availability', compact('rooms'));
    }

    /**
     * Search rooms.
     */
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:2'
        ], [
            'search.required' => 'खोज शब्द आवश्यक छ',
            'search.min' => 'खोज शब्द कम्तिमा २ अक्षरको हुनुपर्छ'
        ]);

        $query = $request->input('search');

        $rooms = Room::where('room_number', 'like', "%$query%")
            ->orWhere('type', 'like', "%$query%")
            ->orWhere('status', 'like', "%$query%")
            ->orWhereHas('hostel', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->with('hostel')
            ->paginate(10);

        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Change room status.
     */
    public function changeStatus(Request $request, Room $room)
    {
        $request->validate([
            'new_status' => 'required|in:उपलब्ध,बुक भएको,रिङ्गोट'
        ], [
            'new_status.required' => 'नयाँ स्थिति अनिवार्य छ',
            'new_status.in' => 'अमान्य स्थिति चयन गरिएको छ'
        ]);

        try {
            $room->update(['status' => $request->new_status]);

            return redirect()->back()
                ->with('success', 'कोठाको स्थिति सफलतापूर्वक परिवर्तन गरियो!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'स्थिति परिवर्तन गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Export rooms to CSV.
     */
    public function exportCSV()
    {
        try {
            $fileName = 'rooms_' . date('Y-m-d') . '.csv';
            $rooms = Room::with('hostel')->get();

            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $columns = array('होस्टल', 'कोठा नम्बर', 'प्रकार', 'क्षमता', 'मूल्य', 'स्थिति');

            $callback = function () use ($rooms, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($rooms as $room) {
                    $row = [
                        $room->hostel->name,
                        $room->room_number,
                        $room->type,
                        $room->capacity,
                        $room->price,
                        $room->status
                    ];

                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'CSV निर्यात गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }
}
