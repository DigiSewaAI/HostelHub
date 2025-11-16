<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\User;
use App\Models\College;
use App\Models\MealMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the students with search and filter.
     */
    public function index(Request $request)
    {
        // Role-based query
        if (auth()->user()->hasRole('admin')) {
            $query = Student::query()->with(['user', 'room']);
            $hostels = Hostel::all();
            $rooms = Room::all();
        } else {
            // For hostel managers, only show their hostel's students
            $query = Student::whereHas('room', function ($q) {
                $q->where('hostel_id', auth()->user()->hostel_id);
            })->with(['user', 'room']);
            $hostels = Hostel::where('id', auth()->user()->hostel_id)->get();
            $rooms = Room::where('hostel_id', auth()->user()->hostel_id)->get();
        }

        // ✅ FIXED: SQL Injection prevention in search
        if ($request->filled('search')) {
            $search = $request->search;
            $safeSearch = '%' . addcslashes($search, '%_') . '%'; // Escape wildcards

            $query->whereHas('user', function ($q) use ($safeSearch) {
                $q->where('name', 'like', $safeSearch)
                    ->orWhere('email', 'like', $safeSearch);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('hostel_id') && auth()->user()->hasRole('admin')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('hostel_id', $request->hostel_id);
            });
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $students = $query->latest()->paginate(10)->appends($request->except('page'));

        // Return appropriate view based on role
        if (auth()->user()->hasRole('admin')) {
            return view('admin.students.index', compact('students', 'hostels', 'rooms'));
        } else {
            return view('owner.students.index', compact('students', 'hostels', 'rooms'));
        }
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        // ✅ FIXED: Authorization check for hostel managers
        if (auth()->user()->hasRole('hostel_manager')) {
            if (!auth()->user()->hostel_id) {
                Log::warning('Hostel manager tried to create student but hostel_id is not set', [
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name
                ]);
                abort(403, 'तपाईंसँग विद्यार्थी सिर्जना गर्ने अनुमति छैन। पहिले होस्टेल सेटअप गर्नुहोस्।');
            }
        }

        if (auth()->user()->hasRole('admin')) {
            $hostels = Hostel::all();
            $rooms = Room::where('status', 'available')->with('hostel')->get();
            $users = User::whereDoesntHave('student')->get();

            // ✅ FIXED: Get distinct colleges ordered by name to prevent duplicates in dropdown
            $colleges = College::select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();

            return view('admin.students.create', compact('hostels', 'rooms', 'users', 'colleges'));
        } else {
            // 🔥 CRITICAL SAFETY CHECK: Ensure owner has hostel_id set
            $userHostelId = auth()->user()->hostel_id;

            if (!$userHostelId) {
                Log::warning('Owner tried to create student but hostel_id is not set', [
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name
                ]);

                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्। होस्टेल बिना विद्यार्थी दर्ता गर्न सकिँदैन।');
            }

            $hostels = Hostel::where('id', $userHostelId)->get();

            // FIXED: Get ALL rooms for the owner's hostel (not just available ones)
            $rooms = Room::where('hostel_id', $userHostelId)
                ->with('hostel')
                ->orderBy('room_number')
                ->get();

            // FIXED: Only show users from the same hostel with student role
            $users = User::where('hostel_id', $userHostelId)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'student');
                })
                ->whereDoesntHave('student')
                ->get();

            // ✅ FIXED: Get distinct colleges ordered by name to prevent duplicates in dropdown
            $colleges = College::select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();

            // 🔥 LOG FOR DEBUGGING
            Log::info('Student creation page loaded for owner', [
                'user_id' => auth()->id(),
                'hostel_id' => $userHostelId,
                'rooms_count' => $rooms->count(),
                'users_count' => $users->count()
            ]);

            return view('owner.students.create', compact('hostels', 'rooms', 'users', 'colleges'));
        }
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        // ✅ FIXED: Mass Assignment protection - use validated data only
        $validatedData = $request->validated();

        // Role-based data handling
        if (auth()->user()->hasRole('admin')) {
            // Admin side processing
            try {
                // ✅ FIXED: Handle user_id for admin side - convert 0 to NULL
                $validatedData['user_id'] = ($validatedData['user_id'] == 0) ? null : $validatedData['user_id'];

                // ✅ FIXED: CORRECTED - Handle college selection properly for admin
                if ($request->college_id == 'others' && $request->filled('other_college')) {
                    // ✅ FIXED: Use firstOrCreate to prevent duplicate colleges
                    $college = College::firstOrCreate([
                        'name' => $request->other_college
                    ]);

                    $validatedData['college_id'] = $college->id;
                    $validatedData['college'] = $college->name;
                } else {
                    // Use existing college
                    $validatedData['college_id'] = $request->college_id;
                    $college = College::find($request->college_id);
                    $validatedData['college'] = $college->name ?? 'Unknown College';
                }

                // ✅ FIXED: Map guardian_phone to guardian_contact for database
                $validatedData['guardian_contact'] = $request->guardian_phone;

                // Remove temporary fields
                unset($validatedData['other_college']);
                unset($validatedData['guardian_phone']);

                // ✅ FIXED: Add missing field student_id for admin side
                $validatedData['student_id'] = null;

                // ✅ FIXED: File upload security for admin
                if ($request->hasFile('image')) {
                    $validatedData['image'] = $request->file('image')->store('students', 'public');
                }

                $student = Student::create($validatedData);

                // Update room status only if room is assigned and was available
                if (isset($validatedData['room_id'])) {
                    $room = Room::find($validatedData['room_id']);
                    if ($room && $room->status == 'available') {
                        $room->update(['status' => 'occupied']);
                    }
                }

                return redirect()->route('admin.students.index')
                    ->with('success', 'विद्यार्थी सफलतापूर्वक दर्ता गरियो');
            } catch (\Exception $e) {
                Log::error('Student creation error (admin): ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'विद्यार्थी दर्ता गर्दा त्रुटि भयो: ' . $e->getMessage());
            }
        } else {
            // Owner side processing
            $userHostelId = auth()->user()->hostel_id;
            if (!$userHostelId) {
                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्। होस्टेल बिना विद्यार्थी दर्ता गर्न सकिँदैन।');
            }

            try {
                // ✅ FIXED: Handle user_id - convert 0 to NULL to avoid foreign key constraint
                $validatedData['user_id'] = ($validatedData['user_id'] == 0) ? null : $validatedData['user_id'];

                // ✅ FIXED: CORRECTED - Handle college selection properly for owner
                if ($request->college_id == 'others' && $request->filled('other_college')) {
                    // ✅ FIXED: Use firstOrCreate to prevent duplicate colleges
                    $college = College::firstOrCreate([
                        'name' => $request->other_college
                    ]);

                    $validatedData['college_id'] = $college->id;
                    $validatedData['college'] = $college->name;
                } else {
                    // Use existing college
                    $validatedData['college_id'] = $request->college_id;
                    $college = College::find($request->college_id);
                    $validatedData['college'] = $college->name ?? 'Unknown College';
                }

                // ✅ FIXED: Map guardian_phone to guardian_contact for database
                $validatedData['guardian_contact'] = $request->guardian_phone;

                // Remove temporary fields
                unset($validatedData['other_college']);
                unset($validatedData['guardian_phone']);

                // ✅ FIXED: Add missing field student_id
                $validatedData['student_id'] = null;

                // ✅ FIXED: Add organization_id for owner
                $validatedData['organization_id'] = auth()->user()->organization_id;

                // ✅ FIXED: Add hostel_id for owner
                if ($request->filled('room_id')) {
                    $room = Room::find($request->room_id);
                    $validatedData['hostel_id'] = $room->hostel_id;

                    // 🔥 SAFETY CHECK: Ensure room belongs to owner's hostel
                    if ($room->hostel_id !== $userHostelId) {
                        return back()->with('error', 'चयन गरिएको कोठा तपाईंको होस्टेलको होइन।');
                    }
                } else {
                    $validatedData['hostel_id'] = $userHostelId;
                }

                // ✅ Create new student safely
                $student = Student::create($validatedData);

                // Update room status only if room is assigned and was available
                if (isset($validatedData['room_id'])) {
                    $room = Room::find($validatedData['room_id']);
                    if ($room && $room->status == 'available') {
                        $room->update(['status' => 'occupied']);
                    }
                }

                return redirect()->route('owner.students.index')
                    ->with('success', 'विद्यार्थी सफलतापूर्वक दर्ता गरियो!');
            } catch (\Exception $e) {
                Log::error('Student creation error: ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'विद्यार्थी दर्ता गर्दा त्रुटि भयो: ' . $e->getMessage());
            }
        }
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        // ✅ FIXED: Enhanced authorization check
        if (auth()->user()->hasRole('hostel_manager')) {
            $room = $student->room;
            if (!$room || $room->hostel_id != auth()->user()->hostel_id) {
                abort(403, 'तपाईंसँग यो विद्यार्थी हेर्ने अनुमति छैन');
            }
        }

        // Additional authorization for owners
        if (auth()->user()->hasRole('owner') && $student->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो विद्यार्थी हेर्ने अनुमति छैन');
        }

        // ✅ FIXED: Remove 'meals' relationship to avoid SQL error
        $student->load(['user', 'room.hostel', 'payments']);

        if (auth()->user()->hasRole('admin')) {
            return view('admin.students.show', compact('student'));
        } else {
            return view('owner.students.show', compact('student'));
        }
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        // ✅ FIXED: Enhanced authorization check
        if (auth()->user()->hasRole('hostel_manager')) {
            $room = $student->room;
            if (!$room || $room->hostel_id != auth()->user()->hostel_id) {
                abort(403, 'तपाईंसँग यो विद्यार्थी सम्पादन गर्ने अनुमति छैन');
            }
        }

        // Additional authorization for owners
        if (auth()->user()->hasRole('owner') && $student->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो विद्यार्थी सम्पादन गर्ने अनुमति छैन');
        }

        if (auth()->user()->hasRole('admin')) {
            $student->load(['user', 'room.hostel']);
            $hostels = Hostel::all();
            $rooms = Room::where('status', 'available')
                ->orWhere('id', $student->room_id)
                ->with('hostel')
                ->get();
            $users = User::all();

            // ✅ FIXED: Get distinct colleges ordered by name to prevent duplicates in dropdown
            $colleges = College::select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();

            return view('admin.students.edit', compact('student', 'hostels', 'rooms', 'users', 'colleges'));
        } else {
            // 🔥 CRITICAL SAFETY CHECK: Ensure owner has hostel_id set
            $userHostelId = auth()->user()->hostel_id;
            if (!$userHostelId) {
                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्।');
            }

            $student->load(['user', 'room.hostel']);
            $hostels = Hostel::where('id', $userHostelId)->get();

            // FIXED: Get ALL rooms for the current owner's hostel
            $rooms = Room::where('hostel_id', $userHostelId)
                ->with('hostel')
                ->orderBy('room_number')
                ->get();

            // ✅ FIXED: Simplify user query for better results
            $users = User::where('hostel_id', $userHostelId)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'student');
                })
                ->get();

            // ✅ FIXED: Get distinct colleges ordered by name to prevent duplicates in dropdown
            $colleges = College::select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();

            // 🔥 LOG FOR DEBUGGING
            Log::info('Student edit page loaded for owner', [
                'user_id' => auth()->id(),
                'hostel_id' => $userHostelId,
                'rooms_count' => $rooms->count(),
                'users_count' => $users->count(),
                'student_id' => $student->id
            ]);

            return view('owner.students.edit', compact('student', 'hostels', 'rooms', 'users', 'colleges'));
        }
    }

    /**
     * Update the specified student in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        // ✅ FIXED: Enhanced authorization check
        if (auth()->user()->hasRole('hostel_manager')) {
            $room = $student->room;
            if (!$room || $room->hostel_id != auth()->user()->hostel_id) {
                abort(403, 'तपाईंसँग यो विद्यार्थी सम्पादन गर्ने अनुमति छैन');
            }
        }

        // Additional authorization for owners
        if (auth()->user()->hasRole('owner') && $student->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो विद्यार्थी सम्पादन गर्ने अनुमति छैन');
        }

        // ✅ FIXED: Mass Assignment protection - use validated data only
        $validatedData = $request->validated();

        // Role-based processing
        if (auth()->user()->hasRole('admin')) {
            // Admin side update
            try {
                // ✅ FIXED: Handle user_id for admin side - convert 0 to NULL
                $validatedData['user_id'] = ($validatedData['user_id'] == 0) ? null : $validatedData['user_id'];

                // ✅ FIXED: CORRECTED - Handle college selection for admin side
                if ($request->college_id == 'others' && $request->filled('other_college')) {
                    // ✅ FIXED: Use firstOrCreate to prevent duplicate colleges
                    $college = College::firstOrCreate([
                        'name' => $request->other_college
                    ]);

                    $validatedData['college_id'] = $college->id;
                    $validatedData['college'] = $college->name;
                } else {
                    // Use existing college
                    $validatedData['college_id'] = $request->college_id;
                    $college = College::find($request->college_id);
                    $validatedData['college'] = $college->name ?? 'Unknown College';
                }

                // ✅ FIXED: Map guardian_phone to guardian_contact for database
                $validatedData['guardian_contact'] = $request->guardian_phone;

                // Remove temporary fields
                unset($validatedData['other_college']);
                unset($validatedData['guardian_phone']);

                // ✅ FIXED: File upload security for admin
                if ($request->hasFile('image')) {
                    // Validate file type and size
                    $request->validate([
                        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                    ]);

                    if ($student->image) {
                        Storage::disk('public')->delete($student->image);
                    }
                    $validatedData['image'] = $request->file('image')->store('students', 'public');
                }

                // Handle room change for admin
                if ($student->room_id != $validatedData['room_id']) {
                    // Free the old room if it exists
                    if ($student->room_id) {
                        $oldRoom = Room::find($student->room_id);
                        // Only mark as available if no other students are in this room
                        $otherStudentsInRoom = Student::where('room_id', $student->room_id)
                            ->where('id', '!=', $student->id)
                            ->count();
                        if ($otherStudentsInRoom == 0) {
                            $oldRoom->update(['status' => 'available']);
                        }
                    }

                    // Occupy the new room if assigned
                    if ($validatedData['room_id']) {
                        $newRoom = Room::find($validatedData['room_id']);
                        $newRoom->update(['status' => 'occupied']);

                        // Update hostel_id for admin
                        $validatedData['hostel_id'] = $newRoom->hostel_id;
                    }
                }

                $student->update($validatedData);

                return redirect()->route('admin.students.index')
                    ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो');
            } catch (\Exception $e) {
                Log::error('Student update error (admin): ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'विद्यार्थी अद्यावधिक गर्दा त्रुटि भयो: ' . $e->getMessage());
            }
        } else {
            // Owner side update
            $userHostelId = auth()->user()->hostel_id;
            if (!$userHostelId) {
                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्।');
            }

            try {
                // ✅ FIXED: Handle user_id - convert 0 to NULL to avoid foreign key constraint
                $validatedData['user_id'] = ($validatedData['user_id'] == 0) ? null : $validatedData['user_id'];

                // ✅ FIXED: CORRECTED - Handle college selection properly for owner
                if ($request->college_id == 'others' && $request->filled('other_college')) {
                    // ✅ FIXED: Use firstOrCreate to prevent duplicate colleges
                    $college = College::firstOrCreate([
                        'name' => $request->other_college
                    ]);

                    $validatedData['college_id'] = $college->id;
                    $validatedData['college'] = $college->name;
                } else {
                    // Use existing college
                    $validatedData['college_id'] = $request->college_id;
                    $college = College::find($request->college_id);
                    $validatedData['college'] = $college->name ?? 'Unknown College';
                }

                // ✅ FIXED: Map guardian_phone to guardian_contact for database
                $validatedData['guardian_contact'] = $request->guardian_phone;

                // Remove temporary fields
                unset($validatedData['other_college']);
                unset($validatedData['guardian_phone']);

                // ✅ FIXED: Add organization_id for owner
                $validatedData['organization_id'] = auth()->user()->organization_id;

                // 🔥 SAFETY CHECK: Ensure room belongs to owner's hostel if room_id is provided
                if ($request->filled('room_id')) {
                    $room = Room::find($request->room_id);
                    if ($room && $room->hostel_id !== $userHostelId) {
                        return back()->with('error', 'चयन गरिएको कोठा तपाईंको होस्टेलको होइन।');
                    }
                }

                // Handle room change
                if ($student->room_id != $validatedData['room_id']) {
                    // Free the old room if it exists
                    if ($student->room_id) {
                        $oldRoom = Room::find($student->room_id);
                        // Only mark as available if no other students are in this room
                        $otherStudentsInRoom = Student::where('room_id', $student->room_id)
                            ->where('id', '!=', $student->id)
                            ->count();
                        if ($otherStudentsInRoom == 0) {
                            $oldRoom->update(['status' => 'available']);
                        }
                    }

                    // Occupy the new room if assigned
                    if ($validatedData['room_id']) {
                        $newRoom = Room::find($validatedData['room_id']);
                        $newRoom->update(['status' => 'occupied']);

                        // Update hostel_id for owner
                        $validatedData['hostel_id'] = $newRoom->hostel_id;
                    } else {
                        // If no room is assigned, set hostel_id to owner's hostel
                        $validatedData['hostel_id'] = $userHostelId;
                    }
                }

                $student->update($validatedData);

                return redirect()->route('owner.students.index')
                    ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो!');
            } catch (\Exception $e) {
                Log::error('Student update error: ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'विद्यार्थी अद्यावधिक गर्दा त्रुटि भयो: ' . $e->getMessage());
            }
        }
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // ✅ FIXED: Enhanced authorization check
        if (auth()->user()->hasRole('hostel_manager')) {
            $room = $student->room;
            if (!$room || $room->hostel_id != auth()->user()->hostel_id) {
                abort(403, 'तपाईंसँग यो विद्यार्थी हटाउने अनुमति छैन');
            }
        }

        // Additional authorization for owners
        if (auth()->user()->hasRole('owner') && $student->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो विद्यार्थी हटाउने अनुमति छैन');
        }

        // Delete image (for admin only)
        if (auth()->user()->hasRole('admin') && $student->image) {
            Storage::disk('public')->delete($student->image);
        }

        // Update room status if this was the only student in the room
        if ($student->room_id) {
            $room = Room::find($student->room_id);
            $otherStudentsInRoom = Student::where('room_id', $student->room_id)
                ->where('id', '!=', $student->id)
                ->count();
            if ($otherStudentsInRoom == 0) {
                $room->update(['status' => 'available']);
            }
        }

        $student->delete();

        // Role-based redirect
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.students.index')
                ->with('success', 'विद्यार्थी रेकर्ड सफलतापूर्वक मेटाइयो');
        } else {
            return redirect()->route('owner.students.index')
                ->with('success', 'विद्यार्थी सफलतापूर्वक हटाइयो!');
        }
    }

    /**
     * Export students to CSV
     */
    public function exportCSV(Request $request)
    {
        // Role-based query
        if (auth()->user()->hasRole('admin')) {
            $query = Student::query()->with(['user', 'room.hostel']);
        } else {
            $query = Student::whereHas('room', function ($q) {
                $q->where('hostel_id', auth()->user()->hostel_id);
            })->with(['user', 'room.hostel']);
        }

        // ✅ FIXED: SQL Injection prevention in search
        if ($request->filled('search')) {
            $search = $request->search;
            $safeSearch = '%' . addcslashes($search, '%_') . '%';

            $query->whereHas('user', function ($q) use ($safeSearch) {
                $q->where('name', 'like', $safeSearch)
                    ->orWhere('email', 'like', $safeSearch);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->get();

        $fileName = 'students_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'College', 'Room', 'Hostel', 'Status', 'Payment Status', 'Admission Date']);

        foreach ($students as $student) {
            fputcsv($handle, [
                $student->id,
                $student->name,
                $student->email,
                $student->phone,
                $student->college,
                $student->room ? $student->room->room_number : 'N/A',
                $student->room && $student->room->hostel ? $student->room->hostel->name : 'N/A',
                $student->status,
                $student->payment_status,
                $student->admission_date ? $student->admission_date->format('Y-m-d') : 'N/A'
            ]);
        }

        fclose($handle);

        return response()->streamDownload(function () use ($handle) {
            //
        }, $fileName, $headers);
    }

    /**
     * Search students
     */
    public function search(Request $request)
    {
        // Role-based query
        if (auth()->user()->hasRole('admin')) {
            $query = Student::query()->with(['user', 'room.hostel']);
        } else {
            $query = Student::whereHas('room', function ($q) {
                $q->where('hostel_id', auth()->user()->hostel_id);
            })->with(['user', 'room.hostel']);
        }

        // ✅ FIXED: SQL Injection prevention in search
        if ($request->filled('search')) {
            $search = $request->search;
            $safeSearch = '%' . addcslashes($search, '%_') . '%';

            $query->whereHas('user', function ($q) use ($safeSearch) {
                $q->where('name', 'like', $safeSearch)
                    ->orWhere('email', 'like', $safeSearch);
            });
        }

        $students = $query->paginate(10);

        if (auth()->user()->hasRole('admin')) {
            return view('admin.students.index', compact('students'));
        } else {
            return view('owner.students.index', compact('students'));
        }
    }

    /**
     * Student dashboard for student role
     */
    public function studentDashboard()
    {
        // ✅ FIXED: Authorization - only students can access their own dashboard
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return view('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        $todayMeal = null;
        if ($student->hostel_id) {
            $todayMeal = MealMenu::where('hostel_id', $student->hostel_id)
                ->where('day', now()->format('l'))
                ->first();
        }

        return view('student.dashboard', compact('student', 'todayMeal'));
    }

    /**
     * Student profile for student role
     */
    public function profile()
    {
        // ✅ FIXED: Authorization - only students can access their own profile
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        return view('student.profile', compact('student'));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
    {
        // ✅ FIXED: Authorization - only students can update their own profile
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        $validatedData = $request->validate([
            'phone' => 'required|string|max:15',
            'college' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:15',
            'guardian_relation' => 'required|string|max:100',
            'guardian_address' => 'required|string|max:500',
        ]);

        $student->update($validatedData);

        return redirect()->route('student.profile')->with('success', 'प्रोफाइल सफलतापूर्वक अद्यावधिक गरियो');
    }

    /**
     * Student payments
     */
    public function payments()
    {
        // ✅ FIXED: Authorization - only students can access their own payments
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        $payments = $student->payments()->latest()->get();

        return view('student.payments', compact('student', 'payments'));
    }

    /**
     * Student meal menus
     */
    public function mealMenus()
    {
        // ✅ FIXED: Authorization - only students can access meal menus
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student || !$student->hostel_id) {
            return redirect()->route('student.dashboard')->with('error', 'विद्यार्थी वा हस्टेल फेला परेन');
        }

        $mealMenus = MealMenu::where('hostel_id', $student->hostel_id)->get();

        return view('student.meal-menus', compact('student', 'mealMenus'));
    }

    /**
     * Show specific meal menu
     */
    public function showMealMenu(MealMenu $mealMenu)
    {
        // ✅ FIXED: Authorization - only students can access their hostel's meal menus
        if (!auth()->user()->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student || $mealMenu->hostel_id != $student->hostel_id) {
            abort(403, 'तपाईंसँग यो मेनु हेर्ने अनुमति छैन');
        }

        return view('student.meal-menu-show', compact('student', 'mealMenu'));
    }
}
