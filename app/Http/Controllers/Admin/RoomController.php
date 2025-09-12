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
     * Display a listing of rooms based on user role.
     */
    public function index()
    {
        $user = auth()->user();

        // Check user role and fetch rooms accordingly
        if ($user->hasRole('admin')) {
            $rooms = Room::with('hostel')->latest()->paginate(10);
            return view('admin.rooms.index', compact('rooms'));
        } elseif ($user->hasRole('owner')) {
            $rooms = Room::where('hostel_id', $user->hostel_id)->get();
            return view('owner.rooms.index', compact('rooms'));
        } elseif ($user->hasRole('student')) {
            // Student view - typically read-only with availability status
            $rooms = Room::with('hostel')->where('status', 'available')->get();
            return view('student.rooms.index', compact('rooms'));
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $hostels = Hostel::all();
            return view('admin.rooms.create', compact('hostels'));
        } elseif ($user->hasRole('owner')) {
            return view('owner.rooms.create');
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Store a newly created room in database.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        try {
            if ($user->hasRole('admin')) {
                $validated = (new StoreRoomRequest)->validated();
            } elseif ($user->hasRole('owner')) {
                $validated = $request->validate([
                    'room_number' => 'required|string|unique:rooms,room_number,NULL,id,hostel_id,' . $user->hostel_id,
                    'type' => 'required|in:single,double,shared',
                    'capacity' => 'required|integer|min:1',
                    'price' => 'required|numeric|min:0',
                    'description' => 'nullable|string',
                    'status' => 'required|in:available,occupied,maintenance',
                ]);
                $validated['hostel_id'] = $user->hostel_id;
            } else {
                abort(403, 'Unauthorized action.');
            }

            // Upload image (for admin only, owner doesn't have image field in form)
            if ($user->hasRole('admin') && $request->hasFile('image')) {
                $imagePath = $request->file('image')->store('room_images', 'public');
                $validated['image'] = $imagePath;
            }

            Room::create($validated);

            $route = $user->hasRole('admin') ? 'admin.rooms.index' : 'owner.rooms.index';
            return redirect()->route($route)
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
        $user = auth()->user();

        // Check if user has permission to view this room
        if ($user->hasRole('owner') && $room->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो कोठा हेर्ने अनुमति छैन');
        }

        $room->load('hostel');

        // Return appropriate view based on role
        if ($user->hasRole('admin')) {
            return view('admin.rooms.show', compact('room'));
        } elseif ($user->hasRole('owner')) {
            return view('owner.rooms.show', compact('room'));
        } elseif ($user->hasRole('student')) {
            return view('student.rooms.show', compact('room'));
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        $user = auth()->user();

        // Check if user has permission to edit this room
        if ($user->hasRole('owner') && $room->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो कोठा सम्पादन गर्ने अनुमति छैन');
        }

        if ($user->hasRole('admin')) {
            $hostels = Hostel::all();
            return view('admin.rooms.edit', compact('room', 'hostels'));
        } elseif ($user->hasRole('owner')) {
            return view('owner.rooms.edit', compact('room'));
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Update the specified room in database.
     */
    public function update(Request $request, Room $room)
    {
        $user = auth()->user();

        // Check if user has permission to update this room
        if ($user->hasRole('owner') && $room->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो कोठा अपडेट गर्ने अनुमति छैन');
        }

        try {
            if ($user->hasRole('admin')) {
                $validated = (new UpdateRoomRequest)->validated();

                // Update image (admin only)
                if ($request->hasFile('image')) {
                    // Delete old image
                    if ($room->image) {
                        Storage::disk('public')->delete($room->image);
                    }

                    // Save new image
                    $imagePath = $request->file('image')->store('room_images', 'public');
                    $validated['image'] = $imagePath;
                }
            } elseif ($user->hasRole('owner')) {
                $validated = $request->validate([
                    'room_number' => 'required|string|unique:rooms,room_number,' . $room->id . ',id,hostel_id,' . $user->hostel_id,
                    'type' => 'required|in:single,double,shared',
                    'capacity' => 'required|integer|min:1',
                    'price' => 'required|numeric|min:0',
                    'description' => 'nullable|string',
                    'status' => 'required|in:available,occupied,maintenance',
                ]);
            } else {
                abort(403, 'Unauthorized action.');
            }

            $room->update($validated);

            $route = $user->hasRole('admin') ? 'admin.rooms.index' : 'owner.rooms.index';
            return redirect()->route($route)
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
        $user = auth()->user();

        // Check if user has permission to delete this room
        if ($user->hasRole('owner') && $room->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो कोठा हटाउने अनुमति छैन');
        }

        try {
            // Delete related image (admin only)
            if ($user->hasRole('admin') && $room->image) {
                Storage::disk('public')->delete($room->image);
            }

            $room->delete();

            $route = $user->hasRole('admin') ? 'admin.rooms.index' : 'owner.rooms.index';
            return redirect()->route($route)
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
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $rooms = Room::with('hostel')->get();
            return view('admin.rooms.availability', compact('rooms'));
        } elseif ($user->hasRole('student')) {
            $rooms = Room::with('hostel')->where('status', 'available')->get();
            return view('student.rooms.availability', compact('rooms'));
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Search rooms.
     */
    public function search(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'search' => 'required|string|min:2'
        ], [
            'search.required' => 'खोज शब्द आवश्यक छ',
            'search.min' => 'खोज शब्द कम्तिमा २ अक्षरको हुनुपर्छ'
        ]);

        $query = $request->input('search');

        if ($user->hasRole('admin')) {
            $rooms = Room::where('room_number', 'like', "%$query%")
                ->orWhere('type', 'like', "%$query%")
                ->orWhere('status', 'like', "%$query%")
                ->orWhereHas('hostel', function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%");
                })
                ->with('hostel')
                ->paginate(10);

            return view('admin.rooms.index', compact('rooms'));
        } elseif ($user->hasRole('owner')) {
            $rooms = Room::where('hostel_id', $user->hostel_id)
                ->where(function ($q) use ($query) {
                    $q->where('room_number', 'like', "%$query%")
                        ->orWhere('type', 'like', "%$query%")
                        ->orWhere('status', 'like', "%$query%");
                })
                ->paginate(10);

            return view('owner.rooms.index', compact('rooms'));
        } elseif ($user->hasRole('student')) {
            $rooms = Room::where('status', 'available')
                ->where(function ($q) use ($query) {
                    $q->where('room_number', 'like', "%$query%")
                        ->orWhere('type', 'like', "%$query%")
                        ->orWhereHas('hostel', function ($q2) use ($query) {
                            $q2->where('name', 'like', "%$query%");
                        });
                })
                ->with('hostel')
                ->paginate(10);

            return view('student.rooms.index', compact('rooms'));
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Change room status.
     */
    public function changeStatus(Request $request, Room $room)
    {
        $user = auth()->user();

        // Check if user has permission to change status of this room
        if ($user->hasRole('owner') && $room->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो कोठाको स्थिति परिवर्तन गर्ने अनुमति छैन');
        }

        $request->validate([
            'new_status' => 'required|in:उपलब्ध,बुक भएको,रिङ्गोट,available,occupied,maintenance'
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
        $user = auth()->user();

        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

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
