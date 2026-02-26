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
use Illuminate\Support\Facades\Auth;

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
        }

        // ✅ FIXED: For hostel_manager/owner, use direct owner_id relationship
        if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            // Get all hostel IDs where this user is the owner
            $hostelIds = Hostel::where('owner_id', $user->id)->pluck('id');

            // If no hostels found, show empty list with appropriate message
            if ($hostelIds->isEmpty()) {
                return view('owner.rooms.index', ['rooms' => collect()])
                    ->with('error', 'तपाईंसँग कुनै होस्टल छैन। पहिले होस्टल सिर्जना गर्नुहोस्।');
            }

            $rooms = Room::whereIn('hostel_id', $hostelIds)
                ->with('hostel')
                ->latest()
                ->paginate(10);

            return view('owner.rooms.index', compact('rooms'));
        }

        if ($user->hasRole('student')) {
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
            // ✅ FIXED: Use direct owner_id relationship to fetch hostels (bypass organization/session)
            $hostels = Hostel::where('owner_id', $user->id)->get();

            if ($hostels->isEmpty()) {
                \Log::warning('No hostels found for user', ['user_id' => $user->id]);
                return redirect()->route('owner.rooms.index')
                    ->with('error', 'तपाईंसँग कुनै होस्टल छैन। पहिले होस्टल सिर्जना गर्नुहोस्।');
            }

            \Log::info('Owner/Manager accessing room create form', [
                'user_id' => $user->id,
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

        // ✅ SECURITY FIX: Authorization check at the beginning
        if (!$user->hasAnyRole(['admin', 'hostel_manager', 'owner'])) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

        DB::beginTransaction();

        try {
            // ✅ SECURITY FIX: Use validated data only (mass assignment protection)
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

            // 🔥 FIXED: For hostel_manager/owner, check hostel ownership via owner_id, not organization
            if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                $hostel = Hostel::where('id', $request->hostel_id)
                    ->where('owner_id', $user->id)
                    ->first();

                if (!$hostel) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'तपाईंसँग यो होस्टलमा कोठा सिर्जना गर्ने अनुमति छैन');
                }
            }

            // ✅ SECURITY FIX: Enhanced file upload security
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $originalName = $image->getClientOriginalName();
                $safeName = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $originalName);
                $imagePath = $image->storeAs('room_images', time() . '_' . $safeName, 'public');
                $validatedData['image'] = $imagePath;
            }

            // ✅ FIXED: Auto-set gallery category based on room type
            $validatedData['gallery_category'] = $this->getGalleryCategoryFromType($validatedData['type']);

            // ✅ SECURITY FIX: Create room using ONLY validated data (mass assignment protection)
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

    public function syncSingle(Room $room)
    {
        try {
            // Calculate real occupancy
            $currentOccupancy = $room->students()
                ->whereIn('status', ['active', 'approved'])
                ->count();

            $availableBeds = $room->capacity - $currentOccupancy;

            // Update room status based on actual data
            if ($currentOccupancy == 0) {
                $status = 'available';
            } elseif ($currentOccupancy == $room->capacity) {
                $status = 'occupied';
            } else {
                $status = 'partially_available';
            }

            // Update the room
            $room->update([
                'current_occupancy' => $currentOccupancy,
                'available_beds' => $availableBeds,
                'status' => $status,
                'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'कोठा ' . $room->room_number . ' को डाटा सिंक सफल भयो!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'सिंक असफल: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room)
    {
        $user = auth()->user();

        // 🔥 Admin: सबै हेर्न सक्छ
        if ($user->hasRole('admin')) {
            $room->load('hostel', 'students');
            return view('admin.rooms.show', compact('room'));
        }

        // 🔥 Owner: आफ्नै होस्टलको कोठा मात्र
        if ($user->hasRole('owner')) {
            if (!$room->hostel || $room->hostel->owner_id !== $user->id) {
                abort(403, 'तपाईंसँग यो कोठा हेर्ने अनुमति छैन');
            }
            $room->load('hostel', 'students');
            return view('owner.rooms.show', compact('room'));
        }

        // 🔥 Hostel Manager: आफ्नो तोकिएको होस्टलको कोठा मात्र
        if ($user->hasRole('hostel_manager')) {
            if ($room->hostel_id !== $user->hostel_id) {
                abort(403, 'तपाईंसँग यो कोठा हेर्ने अनुमति छैन');
            }
            $room->load('hostel', 'students');
            return view('owner.rooms.show', compact('room'));
        }

        // 🔥 Student: आफू बसेको कोठा मात्र
        if ($user->hasRole('student')) {
            $isAssigned = $room->students()->where('user_id', $user->id)->exists();
            if (!$isAssigned) {
                abort(403, 'तपाईंसँग यो कोठा हेर्ने अनुमति छैन');
            }
            $room->load('hostel', 'students');
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

        // Admin लाई सधैं अनुमति
        if ($user->hasRole('admin')) {
            $hostels = Hostel::all();
            return view('admin.rooms.edit', compact('room', 'hostels'));
        }

        // Owner वा Hostel Manager लाई permission check
        if ($user->hasAnyRole(['owner', 'hostel_manager'])) {
            // Owner: आफ्नै hostel (owner_id) हो भने मात्र अनुमति
            if ($user->hasRole('owner')) {
                $allowed = $room->hostel->owner_id === $user->id;
            }
            // Hostel Manager: आफ्नो hostel_id सँग मेल खाए मात्र
            else { // hostel_manager
                $allowed = $room->hostel_id === $user->hostel_id;
            }

            if (!$allowed) {
                abort(403, 'तपाईंसँग यो कोठा सम्पादन गर्ने अनुमति छैन');
            }

            // Owner को लागि उपलब्ध hostels (उसको सबै होस्टल)
            $hostels = Hostel::where('owner_id', $user->id)->get();

            return view('owner.rooms.edit', compact('room', 'hostels'));
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Update the specified room in database.
     */
    public function update(Request $request, Room $room)
    {
        $user = auth()->user();

        // ✅ SECURITY FIX: Authorization check at the beginning
        if (!$user->hasAnyRole(['admin', 'hostel_manager', 'owner'])) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

        DB::beginTransaction();

        try {
            $oldHostelId = $room->hostel_id;

            // ✅ SECURITY FIX: Use validated data only (mass assignment protection)
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

            // ✅ Server-side type-capacity validation
            $validationError = $this->validateTypeCapacity($validatedData['type'], $validatedData['capacity']);
            if ($validationError) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $validationError);
            }

            // 🔥 FIXED: Unified ownership check for owner and hostel_manager (removed organization dependency)
            if (!$user->hasRole('admin')) {
                $allowed = false;

                if ($user->hasRole('owner')) {
                    // Owner: room must belong to one of his hostels
                    $allowed = $room->hostel && $room->hostel->owner_id === $user->id;
                    // Also check new hostel_id if changed
                    if ($allowed && $oldHostelId != $request->hostel_id) {
                        $newHostel = Hostel::find($request->hostel_id);
                        $allowed = $newHostel && $newHostel->owner_id === $user->id;
                    }
                } elseif ($user->hasRole('hostel_manager')) {
                    // Hostel Manager: room must belong to his assigned hostel
                    $allowed = $room->hostel_id === $user->hostel_id;
                    // Also check new hostel_id if changed
                    if ($allowed && $oldHostelId != $request->hostel_id) {
                        $allowed = $request->hostel_id == $user->hostel_id;
                    }
                }

                if (!$allowed) {
                    abort(403, 'तपाईंसँग यो कोठा अपडेट गर्ने अनुमति छैन');
                }
            }

            // ✅ SECURITY FIX: Enhanced file upload security
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($room->image) {
                    Storage::disk('public')->delete($room->image);
                }

                // Save new image with secure naming
                $image = $request->file('image');
                $originalName = $image->getClientOriginalName();
                $safeName = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $originalName);
                $imagePath = $image->storeAs('room_images', time() . '_' . $safeName, 'public');
                $validatedData['image'] = $imagePath;
            }

            // ✅ FIXED: Auto-set gallery category based on room type
            $validatedData['gallery_category'] = $this->getGalleryCategoryFromType($validatedData['type']);

            // ✅ SECURITY FIX: Update room using ONLY validated data
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

        // ✅ SECURITY FIX: Authorization check at the beginning
        if (!$user->hasAnyRole(['admin', 'hostel_manager', 'owner'])) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

        DB::beginTransaction();

        try {
            // Store hostel info before deletion for updating counts
            $hostel = $room->hostel;

            // 🔥 FIXED: Unified ownership check for owner and hostel_manager (removed organization dependency)
            if (!$user->hasRole('admin')) {
                $allowed = false;

                if ($user->hasRole('owner')) {
                    // Owner: room must belong to one of his hostels
                    $allowed = $hostel && $hostel->owner_id === $user->id;
                } elseif ($user->hasRole('hostel_manager')) {
                    // Hostel Manager: room must belong to his assigned hostel
                    $allowed = $room->hostel_id === $user->hostel_id;
                }

                if (!$allowed) {
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
            if ($hostel) {
                $hostel->updateRoomCounts();
            }

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

        // ✅ SECURITY FIX: SQL Injection prevention in search
        $safeQuery = '%' . addcslashes($query, '%_') . '%';

        if ($user->hasRole('admin')) {
            $rooms = Room::where('room_number', 'like', $safeQuery)
                ->orWhere('type', 'like', $safeQuery)
                ->orWhere('status', 'like', $safeQuery)
                ->orWhereHas('hostel', function ($q) use ($safeQuery) {
                    $q->where('name', 'like', $safeQuery)
                        ->orWhere('address', 'like', $safeQuery);
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
                ->where(function ($q) use ($safeQuery) {
                    $q->where('room_number', 'like', $safeQuery)
                        ->orWhere('type', 'like', $safeQuery)
                        ->orWhere('status', 'like', $safeQuery);
                })
                ->with('hostel')
                ->latest()
                ->paginate(10);

            return view('owner.rooms.index', compact('rooms'));
        } elseif ($user->hasRole('student')) {
            $rooms = Room::where('status', 'available')
                ->where(function ($q) use ($safeQuery) {
                    $q->where('room_number', 'like', $safeQuery)
                        ->orWhere('type', 'like', $safeQuery)
                        ->orWhereHas('hostel', function ($q2) use ($safeQuery) {
                            $q2->where('name', 'like', $safeQuery)
                                ->orWhere('address', 'like', $safeQuery);
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

        // ✅ SECURITY FIX: Authorization check at the beginning
        if (!$user->hasAnyRole(['admin', 'hostel_manager', 'owner'])) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

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
