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
            $hostels = Hostel::where('id', auth()->user()->hostel_id)->get();

            // FIXED: Get ALL rooms for the owner's hostel (not just available ones)
            $rooms = Room::where('hostel_id', auth()->user()->hostel_id)
                ->with('hostel')
                ->orderBy('room_number')
                ->get();

            // FIXED: Only show users from the same hostel with student role
            $users = User::where('hostel_id', auth()->user()->hostel_id)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'student');
                })
                ->whereDoesntHave('student')
                ->get();

            $colleges = College::all();

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
            $validatedData = $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'phone' => 'required|string',
                'college_id' => 'required',
                'other_college' => 'nullable|string|max:255',
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'guardian_name' => 'required|string',
                'guardian_phone' => 'required|string',
                'guardian_relation' => 'required|string',
                'room_id' => 'nullable|exists:rooms,id',
                'admission_date' => 'required|date',
                'status' => 'required|in:pending,approved,active,inactive',
                'payment_status' => 'required|in:pending,paid',
                'address' => 'required|string',
                'guardian_address' => 'required|string',
            ]);

            // FIXED: Handle college selection (regular college or "Others" option)
            if ($request->college_id == 'others' && $request->filled('other_college')) {
                $validatedData['college'] = $request->other_college;
            } else {
                $college = College::find($request->college_id);
                $validatedData['college'] = $college->name ?? 'Unknown College';
            }

            // Remove temporary fields
            unset($validatedData['college_id']);
            unset($validatedData['other_college']);

            // Add hostel_id for owner
            if ($request->filled('room_id')) {
                $room = Room::find($request->room_id);
                $validatedData['hostel_id'] = $room->hostel_id;
            } else {
                $validatedData['hostel_id'] = auth()->user()->hostel_id;
            }
        }

        // Handle image upload (for admin only)
        if (auth()->user()->hasRole('admin') && $request->hasFile('image')) {
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

        // Role-based redirect with appropriate message
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.students.index')
                ->with('success', 'विद्यार्थी सफलतापूर्वक दर्ता गरियो');
        } else {
            return redirect()->route('owner.students.index')
                ->with('success', 'विद्यार्थी सफलतापूर्वक थपियो!');
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
            $student->load(['user', 'room.hostel']);
            $hostels = Hostel::where('id', auth()->user()->hostel_id)->get();

            // FIXED: Get ALL rooms for the current owner's hostel
            $rooms = Room::where('hostel_id', auth()->user()->hostel_id)
                ->with('hostel')
                ->orderBy('room_number')
                ->get();

            // FIXED: Only show users from the same hostel with student role for owner
            $users = User::where('hostel_id', auth()->user()->hostel_id)
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
            $validatedData = $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email,' . $student->id,
                'phone' => 'required|string',
                'college_id' => 'required',
                'other_college' => 'nullable|string|max:255',
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'guardian_name' => 'required|string',
                'guardian_phone' => 'required|string',
                'guardian_relation' => 'required|string',
                'room_id' => 'nullable|exists:rooms,id',
                'admission_date' => 'required|date',
                'status' => 'required|in:pending,approved,active,inactive',
                'payment_status' => 'required|in:pending,paid',
                'address' => 'required|string',
                'guardian_address' => 'required|string',
            ]);

            // FIXED: Handle college selection (regular college or "Others" option)
            if ($request->college_id == 'others' && $request->filled('other_college')) {
                $validatedData['college'] = $request->other_college;
            } else {
                $college = College::find($request->college_id);
                $validatedData['college'] = $college->name ?? 'Unknown College';
            }

            // Remove temporary fields
            unset($validatedData['college_id']);
            unset($validatedData['other_college']);
        }

        // Handle image upload (for admin only)
        if (auth()->user()->hasRole('admin') && $request->hasFile('image')) {
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }
            $validatedData['image'] = $request->file('image')->store('students', 'public');
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
                if (auth()->user()->hasRole('hostel_manager')) {
                    $validatedData['hostel_id'] = $newRoom->hostel_id;
                }
            } else {
                // If no room is assigned, set hostel_id to owner's hostel
                $validatedData['hostel_id'] = auth()->user()->hostel_id;
            }
        }

        $student->update($validatedData);

        // Role-based redirect
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.students.index')
                ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो');
        } else {
            return redirect()->route('owner.students.index')
                ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो!');
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
            'phone' => 'required|string',
            'college' => 'required|string',
            'address' => 'required|string',
            'guardian_name' => 'required|string',
            'guardian_phone' => 'required|string',
            'guardian_relation' => 'required|string',
            'guardian_address' => 'required|string',
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
