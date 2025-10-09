<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Hostel;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
        } elseif ($user->hasRole('hostel_manager')) {
            // FIX: Get organization and then hostels for owner
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                return view('owner.rooms.index', ['rooms' => collect()])
                    ->with('error', 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');
            $rooms = Room::whereIn('hostel_id', $hostelIds)
                ->with('hostel')
                ->latest()
                ->paginate(10);

            return view('owner.rooms.index', compact('rooms'));
        } elseif ($user->hasRole('student')) {
            // Student view - typically read-only with availability status
            $rooms = Room::with('hostel')->where('status', 'available')->paginate(10);
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
        } elseif ($user->hasRole('hostel_manager')) {
            // FIX: Get organization hostels for owner
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                return redirect()->route('owner.rooms.index')
                    ->with('error', 'तपाईंको संस्था फेला परेन');
            }

            $hostels = $organization->hostels;
            return view('owner.rooms.create', compact('hostels'));
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Store a newly created room in database.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // FIX: Use transaction for data consistency
        DB::beginTransaction();

        try {
            if ($user->hasRole('admin')) {
                // Use StoreRoomRequest for admin with full validation
                $validatedData = $request->validate([
                    'hostel_id' => 'required|exists:hostels,id',
                    'room_number' => 'required|string|max:50|unique:rooms,room_number,NULL,id,hostel_id,' . $request->hostel_id,
                    'type' => 'required|in:single,double,shared',
                    'capacity' => 'required|integer|min:1|max:10',
                    'price' => 'required|numeric|min:0',
                    'description' => 'nullable|string|max:500',
                    'status' => 'required|in:available,occupied,maintenance',
                    'floor' => 'nullable|string|max:20',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
                ]);

                // Upload image for admin
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('room_images', 'public');
                    $validatedData['image'] = $imagePath;
                }
            } elseif ($user->hasRole('hostel_manager')) {
                // FIX: Validate and check hostel ownership for owner
                $organization = $user->organizations()->wherePivot('role', 'owner')->first();

                if (!$organization) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'तपाईंको संस्था फेला परेन');
                }

                $validatedData = $request->validate([
                    'hostel_id' => 'required|exists:hostels,id',
                    'room_number' => 'required|string|max:50|unique:rooms,room_number,NULL,id,hostel_id,' . $request->hostel_id,
                    'type' => 'required|in:single,double,shared',
                    'capacity' => 'required|integer|min:1|max:10',
                    'price' => 'required|numeric|min:0',
                    'description' => 'nullable|string|max:500',
                    'status' => 'required|in:available,occupied,maintenance',
                ]);

                // Check if hostel belongs to owner's organization
                $hostel = $organization->hostels()->where('id', $request->hostel_id)->first();
                if (!$hostel) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'तपाईंसँग यो होस्टलमा कोठा सिर्जना गर्ने अनुमति छैन');
                }
            } else {
                abort(403, 'Unauthorized action.');
            }

            // Create room
            $room = Room::create($validatedData);

            // FIX: Update hostel room counts after room creation
            $room->hostel->updateRoomCounts();

            DB::commit();

            $route = $user->hasRole('admin') ? 'admin.rooms.index' : 'owner.rooms.index';
            return redirect()->route($route)
                ->with('success', 'कोठा सफलतापूर्वक थपियो!');
        } catch (\Exception $e) {
            DB::rollBack();
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
        if ($user->hasRole('hostel_manager')) {
            // FIX: Check if room belongs to owner's organization
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                abort(403, 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');
            if (!in_array($room->hostel_id, $hostelIds->toArray())) {
                abort(403, 'तपाईंसँग यो कोठा हेर्ने अनुमति छैन');
            }
        }

        $room->load('hostel', 'students');

        // Return appropriate view based on role
        if ($user->hasRole('admin')) {
            return view('admin.rooms.show', compact('room'));
        } elseif ($user->hasRole('hostel_manager')) {
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
        if ($user->hasRole('hostel_manager')) {
            // FIX: Check if room belongs to owner's organization
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                abort(403, 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');
            if (!in_array($room->hostel_id, $hostelIds->toArray())) {
                abort(403, 'तपाईंसँग यो कोठा सम्पादन गर्ने अनुमति छैन');
            }

            $hostels = $organization->hostels;
            return view('owner.rooms.edit', compact('room', 'hostels'));
        }

        if ($user->hasRole('admin')) {
            $hostels = Hostel::all();
            return view('admin.rooms.edit', compact('room', 'hostels'));
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Update the specified room in database.
     */
    public function update(Request $request, Room $room)
    {
        $user = auth()->user();

        // FIX: Use transaction for data consistency
        DB::beginTransaction();

        try {
            $oldHostelId = $room->hostel_id; // Store old hostel ID for updating counts

            // Check if user has permission to update this room
            if ($user->hasRole('hostel_manager')) {
                // FIX: Check if room belongs to owner's organization
                $organization = $user->organizations()->wherePivot('role', 'owner')->first();

                if (!$organization) {
                    abort(403, 'तपाईंको संस्था फेला परेन');
                }

                $hostelIds = $organization->hostels->pluck('id');
                if (!in_array($room->hostel_id, $hostelIds->toArray())) {
                    abort(403, 'तपाईंसँग यो कोठा अपडेट गर्ने अनुमति छैन');
                }

                $validatedData = $request->validate([
                    'hostel_id' => 'required|exists:hostels,id',
                    'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $room->id . ',id,hostel_id,' . $request->hostel_id,
                    'type' => 'required|in:single,double,shared',
                    'capacity' => 'required|integer|min:1|max:10',
                    'price' => 'required|numeric|min:0',
                    'description' => 'nullable|string|max:500',
                    'status' => 'required|in:available,occupied,maintenance',
                ]);

                // Check if new hostel belongs to owner's organization
                $hostel = $organization->hostels()->where('id', $request->hostel_id)->first();
                if (!$hostel) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'तपाईंसँग यो होस्टलमा कोठा अपडेट गर्ने अनुमति छैन');
                }
            } elseif ($user->hasRole('admin')) {
                $validatedData = $request->validate([
                    'hostel_id' => 'required|exists:hostels,id',
                    'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $room->id . ',id,hostel_id,' . $request->hostel_id,
                    'type' => 'required|in:single,double,shared',
                    'capacity' => 'required|integer|min:1|max:10',
                    'price' => 'required|numeric|min:0',
                    'description' => 'nullable|string|max:500',
                    'status' => 'required|in:available,occupied,maintenance',
                    'floor' => 'nullable|string|max:20',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
                ]);

                // Update image for admin only
                if ($request->hasFile('image')) {
                    // Delete old image if exists
                    if ($room->image) {
                        Storage::disk('public')->delete($room->image);
                    }

                    // Save new image
                    $imagePath = $request->file('image')->store('room_images', 'public');
                    $validatedData['image'] = $imagePath;
                }
            } else {
                abort(403, 'Unauthorized action.');
            }

            $room->update($validatedData);

            // FIX: Update hostel room counts after room update
            // If hostel changed, update both old and new hostel counts
            if ($oldHostelId != $room->hostel_id) {
                $oldHostel = Hostel::find($oldHostelId);
                if ($oldHostel) {
                    $oldHostel->updateRoomCounts();
                }
            }

            $room->hostel->updateRoomCounts();

            DB::commit();

            $route = $user->hasRole('admin') ? 'admin.rooms.index' : 'owner.rooms.index';
            return redirect()->route($route)
                ->with('success', 'कोठा सफलतापूर्वक अपडेट गरियो!');
        } catch (\Exception $e) {
            DB::rollBack();
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

        // FIX: Use transaction for data consistency
        DB::beginTransaction();

        try {
            // Store hostel info before deletion for updating counts
            $hostel = $room->hostel;

            // Check if user has permission to delete this room
            if ($user->hasRole('hostel_manager')) {
                // FIX: Check if room belongs to owner's organization
                $organization = $user->organizations()->wherePivot('role', 'owner')->first();

                if (!$organization) {
                    abort(403, 'तपाईंको संस्था फेला परेन');
                }

                $hostelIds = $organization->hostels->pluck('id');
                if (!in_array($room->hostel_id, $hostelIds->toArray())) {
                    abort(403, 'तपाईंसँग यो कोठा हटाउने अनुमति छैन');
                }
            }

            // Check if room has students before deletion
            if ($room->students()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'यो कोठामा विद्यार्थीहरू छन्। पहिले सबै विद्यार्थीहरू हटाउनुहोस्।');
            }

            // Delete related image (admin only)
            if ($user->hasRole('admin') && $room->image) {
                Storage::disk('public')->delete($room->image);
            }

            $room->delete();

            // FIX: Update hostel room counts after room deletion
            $hostel->updateRoomCounts();

            DB::commit();

            $route = $user->hasRole('admin') ? 'admin.rooms.index' : 'owner.rooms.index';
            return redirect()->route($route)
                ->with('success', 'कोठा सफलतापूर्वक हटाइयो!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'कोठा हटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Student view for rooms (read-only)
     */
    public function studentIndex()
    {
        $user = auth()->user();

        if (!$user->hasRole('student')) {
            abort(403, 'Unauthorized action.');
        }

        $rooms = Room::with('hostel')
            ->where('status', 'available')
            ->latest()
            ->paginate(10);

        return view('student.rooms.index', compact('rooms'));
    }

    /**
     * Student view for single room (read-only)
     */
    public function studentShow(Room $room)
    {
        $user = auth()->user();

        if (!$user->hasRole('student')) {
            abort(403, 'Unauthorized action.');
        }

        // Students can only view available rooms
        if ($room->status !== 'available') {
            abort(404, 'कोठा उपलब्ध छैन');
        }

        $room->load('hostel');
        return view('student.rooms.show', compact('room'));
    }

    /**
     * Student bookings
     */
    public function myBookings()
    {
        $user = auth()->user();

        if (!$user->hasRole('student')) {
            abort(403, 'Unauthorized action.');
        }

        // This would typically show student's room bookings
        return view('student.rooms.bookings');
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
                ->orWhere('floor', 'like', "%$query%")
                ->orWhereHas('hostel', function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%")
                        ->orWhere('address', 'like', "%$query%");
                })
                ->with('hostel')
                ->latest()
                ->paginate(10);

            return view('admin.rooms.index', compact('rooms'));
        } elseif ($user->hasRole('hostel_manager')) {
            // FIX: Search in owner's organization hostels
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                return view('owner.rooms.index', ['rooms' => collect()])
                    ->with('error', 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');
            $rooms = Room::whereIn('hostel_id', $hostelIds)
                ->where(function ($q) use ($query) {
                    $q->where('room_number', 'like', "%$query%")
                        ->orWhere('type', 'like', "%$query%")
                        ->orWhere('status', 'like', "%$query%")
                        ->orWhere('floor', 'like', "%$query%");
                })
                ->with('hostel')
                ->latest()
                ->paginate(10);

            return view('owner.rooms.index', compact('rooms'));
        } elseif ($user->hasRole('student')) {
            $rooms = Room::where('status', 'available')
                ->where(function ($q) use ($query) {
                    $q->where('room_number', 'like', "%$query%")
                        ->orWhere('type', 'like', "%$query%")
                        ->orWhereHas('hostel', function ($q2) use ($query) {
                            $q2->where('name', 'like', "%$query%")
                                ->orWhere('address', 'like', "%$query%");
                        });
                })
                ->with('hostel')
                ->latest()
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

        // FIX: Use transaction for data consistency
        DB::beginTransaction();

        try {
            // Check if user has permission to change status of this room
            if ($user->hasRole('hostel_manager')) {
                // FIX: Check if room belongs to owner's organization
                $organization = $user->organizations()->wherePivot('role', 'owner')->first();

                if (!$organization) {
                    abort(403, 'तपाईंको संस्था फेला परेन');
                }

                $hostelIds = $organization->hostels->pluck('id');
                if (!in_array($room->hostel_id, $hostelIds->toArray())) {
                    abort(403, 'तपाईंसँग यो कोठाको स्थिति परिवर्तन गर्ने अनुमति छैन');
                }
            }

            $request->validate([
                'new_status' => 'required|in:available,occupied,maintenance'
            ], [
                'new_status.required' => 'नयाँ स्थिति अनिवार्य छ',
                'new_status.in' => 'अमान्य स्थिति चयन गरिएको छ'
            ]);

            $room->update(['status' => $request->new_status]);

            // FIX: Update hostel room counts after status change
            $room->hostel->updateRoomCounts();

            DB::commit();

            return redirect()->back()
                ->with('success', 'कोठाको स्थिति सफलतापूर्वक परिवर्तन गरियो!');
        } catch (\Exception $e) {
            DB::rollBack();
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

            $columns = array('होस्टल', 'कोठा नम्बर', 'प्रकार', 'क्षमता', 'मूल्य', 'स्थिति', 'तल्ला');

            $callback = function () use ($rooms, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($rooms as $room) {
                    $row = [
                        $room->hostel->name ?? 'N/A',
                        $room->room_number,
                        $this->getRoomTypeText($room->type),
                        $room->capacity,
                        $room->price,
                        $this->getStatusText($room->status),
                        $room->floor ?? 'N/A'
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

    /**
     * Helper method to get room type in Nepali
     */
    private function getRoomTypeText($type)
    {
        switch ($type) {
            case 'single':
                return 'एकल कोठा';
            case 'double':
                return 'दोहोरो कोठा';
            case 'shared':
                return 'साझा कोठा';
            default:
                return $type;
        }
    }

    /**
     * Helper method to get status in Nepali
     */
    private function getStatusText($status)
    {
        switch ($status) {
            case 'available':
                return 'उपलब्ध';
            case 'occupied':
                return 'अधिभृत';
            case 'maintenance':
                return 'मर्मतमा';
            default:
                return $status;
        }
    }
}
