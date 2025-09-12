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
            $rooms = Room::where('hostel_id', auth()->user()->hostel_id)
                ->where('status', 'available')
                ->get();
            $users = User::whereDoesntHave('student')->get();
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
                'college' => 'required|string',
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

        // Update room status
        if (isset($validatedData['room_id'])) {
            Room::find($validatedData['room_id'])->update(['status' => 'occupied']);
        }

        // Role-based redirect with appropriate message
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('students.index')
                ->with('success', 'विद्यार्थी सफलतापूर्वक दर्ता गरियो');
        } else {
            return redirect()->route('students.index')
                ->with('success', 'विद्यार्थी सफलतापूर्वक थपियो!');
        }
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        // Authorization
        if (auth()->user()->hasRole('owner')) {
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
        if (auth()->user()->hasRole('owner')) {
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
            $rooms = Room::where('hostel_id', auth()->user()->hostel_id)
                ->where(function ($query) use ($student) {
                    $query->where('status', 'available')
                        ->orWhere('id', $student->room_id);
                })
                ->with('hostel')
                ->get();
            $users = User::all();
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
        if (auth()->user()->hasRole('owner')) {
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
                'college' => 'required|string',
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
            // Free the old room
            if ($student->room_id) {
                Room::find($student->room_id)->update(['status' => 'available']);
            }

            // Occupy the new room
            if ($validatedData['room_id']) {
                Room::find($validatedData['room_id'])->update(['status' => 'occupied']);

                // Update hostel_id for owner
                if (auth()->user()->hasRole('owner')) {
                    $room = Room::find($validatedData['room_id']);
                    $validatedData['hostel_id'] = $room->hostel_id;
                }
            }
        }

        $student->update($validatedData);

        // Role-based redirect
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('students.index')
                ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो');
        } else {
            return redirect()->route('students.index')
                ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो!');
        }
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // Authorization
        if (auth()->user()->hasRole('owner')) {
            $room = $student->room;
            if (!$room || $room->hostel_id != auth()->user()->hostel_id) {
                abort(403, 'तपाईंसँग यो विद्यार्थी हटाउने अनुमति छैन');
            }
        }

        // Delete image (for admin only)
        if (auth()->user()->hasRole('admin') && $student->image) {
            Storage::disk('public')->delete($student->image);
        }

        // Update room status
        if ($student->room_id) {
            Room::find($student->room_id)->update(['status' => 'available']);
        }

        $student->delete();

        // Role-based redirect
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('students.index')
                ->with('success', 'विद्यार्थी रेकर्ड सफलतापूर्वक मेटाइयो');
        } else {
            return redirect()->route('students.index')
                ->with('success', 'विद्यार्थी सफलतापूर्वक हटाइयो!');
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
}
