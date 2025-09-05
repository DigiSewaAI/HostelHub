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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the students with search and filter.
     */
    public function index(Request $request)
    {
        $query = Student::query()->with(['hostel', 'room']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('hostel_id')) {
            $query->where('hostel_id', $request->hostel_id);
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $students = $query->latest()->paginate(10)->appends($request->except('page'));

        $hostels = Hostel::all();
        $rooms = Room::all();

        return view('admin.students.index', compact('students', 'hostels', 'rooms'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $hostels = Hostel::all();
        $rooms = Room::where('status', 'available')->with('hostel')->get();
        $users = User::whereDoesntHave('student')->get();
        $colleges = College::all(); // ✅ Add this

        return view('admin.students.create', compact('hostels', 'rooms', 'users', 'colleges'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('students', 'public');
        }

        $student = Student::create($data);

        if (isset($data['room_id'])) {
            Room::find($data['room_id'])->update(['status' => 'occupied']);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'विद्यार्थी सफलतापूर्वक दर्ता गरियो');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $student->load(['hostel', 'room', 'payments', 'meals']);
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $student->load(['hostel', 'room']);

        $hostels = Hostel::all();
        $rooms = Room::where('status', 'available')
            ->orWhere('id', $student->room_id)
            ->get();

        // ✅ Edit मा पनि users चाहिन्छ भने (optional)
        $users = User::all(); // वा whereDoesntHave + $student->user_id

        return view('admin.students.edit', compact('student', 'hostels', 'rooms', 'users'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }
            $data['image'] = $request->file('image')->store('students', 'public');
        }

        if ($student->room_id != $data['room_id']) {
            Room::find($student->room_id)->update(['status' => 'available']);
            Room::find($data['room_id'])->update(['status' => 'occupied']);
        }

        $student->update($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        if ($student->image) {
            Storage::disk('public')->delete($student->image);
        }

        if ($student->room_id) {
            Room::find($student->room_id)->update(['status' => 'available']);
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'विद्यार्थी रेकर्ड सफलतापूर्वक मेटाइयो');
    }
}
