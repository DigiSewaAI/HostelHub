<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StudentsExport;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel; // Added Excel Facade import

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $students = Student::with('room')
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rooms = Room::where('status', 'available')
            ->orWhere('status', 'occupied')
            ->get();

        return view('admin.students.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $room = Room::findOrFail($request->room_id);
        if ($room->students()->count() >= $room->capacity) {
            return back()->withErrors(['room_id' => 'यो कोठामा ठाउँ छैन!'])->withInput();
        }

        Student::create($request->validated());

        if ($room->students()->count() + 1 == $room->capacity) {
            $room->update(['status' => 'occupied']);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'विद्यार्थी सफलतापूर्वक थपियो!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): View
    {
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        $rooms = Room::where('status', '!=', 'maintenance')
            ->get();

        return view('admin.students.edit', compact('student', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        if ($request->room_id && $request->room_id != $student->room_id) {
            $newRoom = Room::findOrFail($request->room_id);

            if ($newRoom->students()->count() >= $newRoom->capacity) {
                return back()->withErrors(['room_id' => 'यो कोठामा ठाउँ छैन!'])->withInput();
            }

            $oldRoom = $student->room;
            $student->update($request->validated());

            if ($oldRoom->students()->count() < $oldRoom->capacity) {
                $oldRoom->update(['status' => 'available']);
            }

            if ($newRoom->students()->count() + 1 == $newRoom->capacity) {
                $newRoom->update(['status' => 'occupied']);
            }
        } else {
            $student->update($request->validated());
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'विद्यार्थी अपडेट गरियो!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): RedirectResponse
    {
        $room = $student->room;
        $student->delete();

        if ($room->students()->count() < $room->capacity) {
            $room->update(['status' => 'available']);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'विद्यार्थी हटाइयो!');
    }

    /**
     * Export students data to Excel
     */
    public function export()
    {
        return Excel::download(
            new StudentsExport,
            'विद्यार्थीहरू-'.now()->format('Y-m-d').'.xlsx'
        );
    }
}
