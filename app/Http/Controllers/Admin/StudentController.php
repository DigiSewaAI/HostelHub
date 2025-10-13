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

        // Search and filters (common for both roles)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
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
        if (auth()->user()->hasRole('admin')) {
            $hostels = Hostel::all();
            $rooms = Room::where('status', 'available')->with('hostel')->get();
            $users = User::whereDoesntHave('student')->get();
            $colleges = College::all();

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

            $colleges = College::all();

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
    public function store(Request $request)
    {
        // Role-based validation and data handling
        if (auth()->user()->hasRole('admin')) {
            $validatedData = $request->validate((new StoreStudentRequest)->rules());
        } else {
            // 🔥 CRITICAL SAFETY CHECK: Ensure owner has hostel_id set
            $userHostelId = auth()->user()->hostel_id;
            if (!$userHostelId) {
                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्। होस्टेल बिना विद्यार्थी दर्ता गर्न सकिँदैन।');
            }

            $validatedData = $request->validate([
                'user_id' => 'nullable|integer|min:0', // ✅ FIXED: Allow 0 as valid value
                'name' => 'required|string|max:255',
                'email' => 'nullable|email',
                'phone' => 'required|string|max:15',
                'college_id' => 'required',
                'other_college' => 'nullable|string|max:255',
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'guardian_name' => 'required|string|max:255',
                'guardian_contact' => 'required|string|max:15',
                'guardian_relation' => 'required|string|max:100',
                'room_id' => 'nullable|exists:rooms,id',
                'admission_date' => 'required|date',
                'status' => 'required|in:pending,approved,active,inactive',
                'payment_status' => 'required|in:pending,paid,unpaid',
                'address' => 'required|string|max:500',
                'guardian_address' => 'required|string|max:500',
            ]);

            try {
                // ✅ FIXED: Handle user_id - convert 0 to NULL to avoid foreign key constraint
                $validatedData['user_id'] = ($validatedData['user_id'] == 0) ? null : $validatedData['user_id'];

                // ✅ FIXED: Add missing field student_id (avoid SQL error)
                $validatedData['student_id'] = null;

                // ✅ FIXED: CORRECTED - Handle college selection properly
                if ($request->college_id == 'others' && $request->filled('other_college')) {
                    $validatedData['college'] = $request->other_college;
                    $validatedData['college_id'] = null; // "others" भएकोले college_id null गर्नुहोस्
                } else {
                    $validatedData['college_id'] = $request->college_id;
                    $college = College::find($request->college_id);
                    $validatedData['college'] = $college->name ?? 'Unknown College'; // Regular college को नाम पनि राख्नुहोस्
                }

                // Remove temporary field only
                unset($validatedData['other_college']);
                // ❌ college_id लाई unset नगर्नुहोस् - यो टेबलमा चाहिन्छ

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
                // ✅ FIXED: Error handle गर्नुहोस्
                Log::error('Student creation error: ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'विद्यार्थी दर्ता गर्दा त्रुटि भयो: ' . $e->getMessage());
            }
        }

        // Handle image upload (for admin only)
        if (auth()->user()->hasRole('admin') && $request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('students', 'public');
        }

        // Admin side student creation
        try {
            // ✅ FIXED: Handle user_id for admin side too - convert 0 to NULL
            $validatedData['user_id'] = ($validatedData['user_id'] == 0) ? null : $validatedData['user_id'];

            // ✅ FIXED: CORRECTED - Handle college selection for admin side too
            if ($request->college_id == 'others' && $request->filled('other_college')) {
                $validatedData['college'] = $request->other_college;
                $validatedData['college_id'] = null;
            } else {
                $validatedData['college_id'] = $request->college_id;
                $college = College::find($request->college_id);
                $validatedData['college'] = $college->name ?? 'Unknown College';
            }

            // Remove temporary field
            unset($validatedData['other_college']);

            // ✅ FIXED: Add missing field student_id for admin side too
            $validatedData['student_id'] = null;

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
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        // Authorization
        if (auth()->user()->hasRole('hostel_manager')) {
            $room = $student->room;
            if (!$room || $room->hostel_id != auth()->user()->hostel_id) {
                abort(403, 'तपाईंसँग यो विद्यार्थी हेर्ने अनुमति छैन');
            }
        }

        $student->load(['user', 'room.hostel', 'payments', 'meals']);

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
        // Authorization
        if (auth()->user()->hasRole('hostel_manager')) {
            $room = $student->room;
            if (!$room || $room->hostel_id != auth()->user()->hostel_id) {
                abort(403, 'तपाईंसँग यो विद्यार्थी सम्पादन गर्ने अनुमति छैन');
            }
        }

        if (auth()->user()->hasRole('admin')) {
            $student->load(['user', 'room.hostel']);
            $hostels = Hostel::all();
            $rooms = Room::where('status', 'available')
                ->orWhere('id', $student->room_id)
                ->with('hostel')
                ->get();
            $users = User::all();
            $colleges = College::all();

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

            // FIXED: Only show users from the same hostel with student role for owner
            $users = User::where('hostel_id', $userHostelId)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'student');
                })
                ->where(function ($query) use ($student) {
                    $query->whereDoesntHave('student')
                        ->orWhereHas('student', function ($q) use ($student) {
                            $q->where('id', $student->id);
                        });
                })
                ->get();

            $colleges = College::all();

            return view('owner.students.edit', compact('student', 'hostels', 'rooms', 'users', 'colleges'));
        }
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        // Authorization
        if (auth()->user()->hasRole('hostel_manager')) {
            $room = $student->room;
            if (!$room || $room->hostel_id != auth()->user()->hostel_id) {
                abort(403, 'तपाईंसँग यो विद्यार्थी सम्पादन गर्ने अनुमति छैन');
            }
        }

        // Role-based validation
        if (auth()->user()->hasRole('admin')) {
            $validatedData = $request->validate((new UpdateStudentRequest)->rules());
        } else {
            // 🔥 CRITICAL SAFETY CHECK: Ensure owner has hostel_id set
            $userHostelId = auth()->user()->hostel_id;
            if (!$userHostelId) {
                return redirect()->route('owner.hostels.index')
                    ->with('error', 'कृपया पहिले आफ्नो होस्टेल सेटअप गर्नुहोस्।');
            }

            $validatedData = $request->validate([
                'user_id' => 'nullable|integer|min:0', // ✅ FIXED: Allow 0 as valid value
                'name' => 'required|string|max:255',
                'email' => 'nullable|email',
                'phone' => 'required|string|max:15',
                'college_id' => 'required',
                'other_college' => 'nullable|string|max:255',
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'guardian_name' => 'required|string|max:255',
                'guardian_contact' => 'required|string|max:15',
                'guardian_relation' => 'required|string|max:100',
                'room_id' => 'nullable|exists:rooms,id',
                'admission_date' => 'required|date',
                'status' => 'required|in:pending,approved,active,inactive',
                'payment_status' => 'required|in:pending,paid,unpaid',
                'address' => 'required|string|max:500',
                'guardian_address' => 'required|string|max:500',
            ]);

            try {
                // ✅ FIXED: Handle user_id - convert 0 to NULL to avoid foreign key constraint
                $validatedData['user_id'] = ($validatedData['user_id'] == 0) ? null : $validatedData['user_id'];

                // ✅ FIXED: CORRECTED - Handle college selection properly
                if ($request->college_id == 'others' && $request->filled('other_college')) {
                    $validatedData['college'] = $request->other_college;
                    $validatedData['college_id'] = null;
                } else {
                    $validatedData['college_id'] = $request->college_id;
                    $college = College::find($request->college_id);
                    $validatedData['college'] = $college->name ?? 'Unknown College';
                }

                // Remove temporary field only
                unset($validatedData['other_college']);

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

        // Admin side update
        try {
            // ✅ FIXED: Handle user_id for admin side too - convert 0 to NULL
            $validatedData['user_id'] = ($validatedData['user_id'] == 0) ? null : $validatedData['user_id'];

            // ✅ FIXED: CORRECTED - Handle college selection for admin side too
            if ($request->college_id == 'others' && $request->filled('other_college')) {
                $validatedData['college'] = $request->other_college;
                $validatedData['college_id'] = null;
            } else {
                $validatedData['college_id'] = $request->college_id;
                $college = College::find($request->college_id);
                $validatedData['college'] = $college->name ?? 'Unknown College';
            }

            // Remove temporary field
            unset($validatedData['other_college']);

            // Handle image upload (for admin only)
            if (auth()->user()->hasRole('admin') && $request->hasFile('image')) {
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
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // Authorization
        if (auth()->user()->hasRole('hostel_manager')) {
            $room = $student->room;
            if (!$room || $room->hostel_id != auth()->user()->hostel_id) {
                abort(403, 'तपाईंसँग यो विद्यार्थी हटाउने अनुमति छैन');
            }
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

        // Apply filters if any
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
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
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student || $mealMenu->hostel_id != $student->hostel_id) {
            abort(403, 'तपाईंसँग यो मेनु हेर्ने अनुमति छैन');
        }

        return view('student.meal-menu-show', compact('student', 'mealMenu'));
    }
}
