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

        \Log::info('RoomController create method accessed', [
            'user_id' => $user->id,
            'user_roles' => $user->getRoleNames()->toArray()
        ]);

        // ✅ ENHANCED: More specific role check
        if ($user->hasRole('admin')) {
            $hostels = Hostel::all();
            \Log::info('Admin accessing room create form', ['hostels_count' => $hostels->count()]);
            return view('admin.rooms.create', compact('hostels'));
        }

        // ✅ ADDED: Explicit check for other roles to prevent fall-through
        if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                \Log::warning('Organization not found for user', ['user_id' => $user->id]);
                return redirect()->route('owner.rooms.index')
                    ->with('error', 'तपाईंको संस्था फेला परेन');
            }

            $hostels = $organization->hostels;
            \Log::info('Owner/Manager accessing room create form', [
                'organization_id' => $organization->id,
                'hostels_count' => $hostels->count()
            ]);
            return view('owner.rooms.create', compact('hostels'));
        }

        \Log::warning('Unauthorized access to room create', [
            'user_id' => $user->id,
            'roles' => $user->getRoleNames()->toArray()
        ]);
        abort(403, 'Unauthorized action.');
    }

    /**
     * Store a newly created room in database.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        DB::beginTransaction();

        try {
            // ✅ FIXED: Unified validation rules for both admin and owner
            $validatedData = $request->validate([
                'hostel_id' => 'required|exists:hostels,id',
                'room_number' => 'required|string|max:50|unique:rooms,room_number,NULL,id,hostel_id,' . $request->hostel_id,
                'type' => 'required|in:1 seater,2 seater,3 seater,4 seater,साझा कोठा',
                'capacity' => 'required|integer|min:1|max:20',
                'current_occupancy' => 'required|integer|min:0|lte:capacity',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:500',
                'status' => 'required|in:available,partially_available,occupied,maintenance,उपलब्ध,व्यस्त,मर्मत सम्भार,आंशिक उपलब्ध',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            // ✅ FIXED: Normalize status to English values
            $validatedData['status'] = $this->normalizeStatus($validatedData['status']);

            // ✅ NEW: Server-side type-capacity validation
            $validationError = $this->validateTypeCapacity($validatedData['type'], $validatedData['capacity']);
            if ($validationError) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $validationError);
            }

            // Check permissions for hostel_manager
            if ($user->hasRole('hostel_manager')) {
                $organization = $user->organizations()->wherePivot('role', 'owner')->first();

                if (!$organization) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'तपाईंको संस्था फेला परेन');
                }

                $hostel = $organization->hostels()->where('id', $request->hostel_id)->first();
                if (!$hostel) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'तपाईंसँग यो होस्टलमा कोठा सिर्जना गर्ने अनुमति छैन');
                }
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('room_images', 'public');
                $validatedData['image'] = $imagePath;
            }

            // ✅ FIXED: Auto-set gallery category based on room type
            $validatedData['gallery_category'] = $this->getGalleryCategoryFromType($validatedData['type']);

            // Create room using validated data
            $room = Room::create($validatedData);

            // Update hostel room counts
            $room->hostel->updateRoomCounts();

            DB::commit();

            $route = $user->hasRole('admin') ? 'admin.rooms.index' : 'owner.rooms.index';
            return redirect()->route($route)
                ->with('success', 'कोठा सफलतापूर्वक थपियो!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Room creation error: ' . $e->getMessage());
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

        DB::beginTransaction();

        try {
            $oldHostelId = $room->hostel_id;

            // ✅ FIXED: Unified validation rules
            $validatedData = $request->validate([
                'hostel_id' => 'required|exists:hostels,id',
                'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $room->id . ',id,hostel_id,' . $request->hostel_id,
                'type' => 'required|in:1 seater,2 seater,3 seater,4 seater,साझा कोठा',
                'capacity' => 'required|integer|min:1|max:20',
                'current_occupancy' => 'required|integer|min:0|lte:capacity',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:500',
                'status' => 'required|in:available,partially_available,occupied,maintenance,उपलब्ध,व्यस्त,मर्मत सम्भार,आंशिक उपलब्ध',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            // ✅ FIXED: Normalize status to English values
            $validatedData['status'] = $this->normalizeStatus($validatedData['status']);

            // ✅ NEW: Server-side type-capacity validation
            $validationError = $this->validateTypeCapacity($validatedData['type'], $validatedData['capacity']);
            if ($validationError) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $validationError);
            }

            // Check permissions for hostel_manager
            if ($user->hasRole('hostel_manager')) {
                $organization = $user->organizations()->wherePivot('role', 'owner')->first();

                if (!$organization) {
                    abort(403, 'तपाईंको संस्था फेला परेन');
                }

                $hostelIds = $organization->hostels->pluck('id');
                if (!in_array($room->hostel_id, $hostelIds->toArray())) {
                    abort(403, 'तपाईंसँग यो कोठा अपडेट गर्ने अनुमति छैन');
                }

                $hostel = $organization->hostels()->where('id', $request->hostel_id)->first();
                if (!$hostel) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'तपाईंसँग यो होस्टलमा कोठा अपडेट गर्ने अनुमति छैन');
                }
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($room->image) {
                    Storage::disk('public')->delete($room->image);
                }

                // Save new image
                $imagePath = $request->file('image')->store('room_images', 'public');
                $validatedData['image'] = $imagePath;
            }

            // ✅ FIXED: Auto-set gallery category based on room type
            $validatedData['gallery_category'] = $this->getGalleryCategoryFromType($validatedData['type']);

            $room->update($validatedData);

            // Update hostel room counts
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

        DB::beginTransaction();

        try {
            // Store hostel info before deletion for updating counts
            $hostel = $room->hostel;

            // Check if user has permission to delete this room
            if ($user->hasRole('hostel_manager')) {
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

            // Delete related image
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }

            $room->delete();

            // Update hostel room counts after room deletion
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
                ->orWhereHas('hostel', function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%")
                        ->orWhere('address', 'like', "%$query%");
                })
                ->with('hostel')
                ->latest()
                ->paginate(10);

            return view('admin.rooms.index', compact('rooms'));
        } elseif ($user->hasRole('hostel_manager')) {
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
                        ->orWhere('status', 'like', "%$query%");
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

        DB::beginTransaction();

        try {
            // Check if user has permission to change status of this room
            if ($user->hasRole('hostel_manager')) {
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
                'new_status' => 'required|in:available,partially_available,occupied,maintenance,उपलब्ध,व्यस्त,मर्मत सम्भार,आंशिक उपलब्ध'
            ], [
                'new_status.required' => 'नयाँ स्थिति अनिवार्य छ',
                'new_status.in' => 'अमान्य स्थिति चयन गरिएको छ'
            ]);

            // Normalize status to English
            $normalizedStatus = $this->normalizeStatus($request->new_status);

            $room->update(['status' => $normalizedStatus]);

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

            $columns = array('होस्टल', 'कोठा नम्बर', 'प्रकार', 'क्षमता', 'वर्तमान अधिभोग', 'मूल्य', 'स्थिति');

            $callback = function () use ($rooms, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($rooms as $room) {
                    $row = [
                        $room->hostel->name ?? 'N/A',
                        $room->room_number,
                        $this->getRoomTypeText($room->type),
                        $room->capacity,
                        $room->current_occupancy ?? 0,
                        $room->price,
                        $this->getStatusText($room->status)
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
     * ✅ NEW: Helper method to validate type-capacity consistency
     */
    private function validateTypeCapacity($type, $capacity)
    {
        $typeCapacityRules = [
            '1 seater' => 1,
            '2 seater' => 2,
            '3 seater' => 3,
            '4 seater' => 4,
            'साझा कोठा' => 'custom'
        ];

        if (array_key_exists($type, $typeCapacityRules)) {
            $expectedCapacity = $typeCapacityRules[$type];

            if ($expectedCapacity === 'custom') {
                // Shared room must have capacity >= 5
                if ($capacity < 5) {
                    return 'साझा कोठाको लागि क्षमता कम्तिमा 5 हुनुपर्छ';
                }
            } else {
                // Fixed capacity rooms must match exactly
                if ($capacity != $expectedCapacity) {
                    $nepaliTypes = [
                        '1 seater' => '१ सिटर कोठा',
                        '2 seater' => '२ सिटर कोठा',
                        '3 seater' => '३ सिटर कोठा',
                        '4 seater' => '४ सिटर कोठा'
                    ];

                    return "{$nepaliTypes[$type]} को लागि क्षमता {$expectedCapacity} हुनुपर्छ";
                }
            }
        }

        return null; // No error
    }

    /**
     * ✅ NEW: Helper method to normalize status input to English
     */
    private function normalizeStatus($status)
    {
        $map = [
            'उपलब्ध' => 'available',
            'आंशिक उपलब्ध' => 'partially_available',
            'व्यस्त' => 'occupied',
            'मर्मत सम्भार' => 'maintenance',
        ];

        return $map[$status] ?? $status;
    }

    /**
     * ✅ NEW: Helper method to get gallery category from room type
     */
    private function getGalleryCategoryFromType($roomType): string
    {
        switch ($roomType) {
            case '1 seater':
                return '1 seater';
            case '2 seater':
                return '2 seater';
            case '3 seater':
                return '3 seater';
            case '4 seater':
            case 'साझा कोठा':
                return '4 seater';
            default:
                return '4 seater';
        }
    }

    /**
     * Helper method to get room type in Nepali
     */
    private function getRoomTypeText($type)
    {
        switch ($type) {
            case '1 seater':
                return 'एकल कोठा';
            case '2 seater':
                return 'दोहोरो कोठा';
            case '3 seater':
                return 'तीन कोठा';
            case '4 seater':
            case 'साझा कोठा':
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
            case 'partially_available':
                return 'आंशिक उपलब्ध';
            case 'occupied':
                return 'व्यस्त';
            case 'maintenance':
                return 'मर्मत सम्भार';
            default:
                return $status;
        }
    }
}
