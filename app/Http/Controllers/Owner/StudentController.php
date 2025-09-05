<?php

namespace App\Http\Controllers\Owner;

use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::where('hostel_id', auth()->user()->hostel_id)->get();
        return view('owner.students.index', compact('students'));
    }

    public function create()
    {
        $availableRooms = Room::where('hostel_id', auth()->user()->hostel_id)
            ->where('status', 'available')
            ->get();
        return view('owner.students.create', compact('availableRooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'college' => 'required|string',
            'course' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'admission_date' => 'required|date',
            'expected_departure_date' => 'required|date|after:admission_date',
            'guardian_name' => 'required|string',
            'guardian_phone' => 'required|string',
            'guardian_relationship' => 'required|string',
        ]);

        // Room status परिवर्तन गर्नुहोस्
        $room = Room::find($request->room_id);
        if ($room->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो कोठा प्रयोग गर्ने अनुमति छैन');
        }

        $room->status = 'occupied';
        $room->save();

        Student::create([
            'hostel_id' => auth()->user()->hostel_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'college' => $request->college,
            'course' => $request->course,
            'room_id' => $request->room_id,
            'admission_date' => $request->admission_date,
            'expected_departure_date' => $request->expected_departure_date,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
            'guardian_relationship' => $request->guardian_relationship,
        ]);

        return redirect()->route('owner.students.index')
            ->with('success', 'विद्यार्थी सफलतापूर्वक थपियो!');
    }

    public function show(Student $student)
    {
        if ($student->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो विद्यार्थी हेर्ने अनुमति छैन');
        }

        return view('owner.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        if ($student->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो विद्यार्थी सम्पादन गर्ने अनुमति छैन');
        }

        $availableRooms = Room::where('hostel_id', auth()->user()->hostel_id)
            ->where('status', 'available')
            ->orWhere('id', $student->room_id)
            ->get();

        return view('owner.students.edit', compact('student', 'availableRooms'));
    }

    public function update(Request $request, Student $student)
    {
        if ($student->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो विद्यार्थी सम्पादन गर्ने अनुमति छैन');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'phone' => 'required|string',
            'college' => 'required|string',
            'course' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'admission_date' => 'required|date',
            'expected_departure_date' => 'required|date|after:admission_date',
            'guardian_name' => 'required|string',
            'guardian_phone' => 'required|string',
            'guardian_relationship' => 'required|string',
        ]);

        // Room status परिवर्तन गर्नुहोस् (यदि कोठा परिवर्तन भएमा)
        if ($student->room_id != $request->room_id) {
            // पुरानो कोठा उपलब्ध बनाउनुहोस्
            $oldRoom = Room::find($student->room_id);
            $oldRoom->status = 'available';
            $oldRoom->save();

            // नयाँ कोठा व्यस्त बनाउनुहोस्
            $newRoom = Room::find($request->room_id);
            if ($newRoom->hostel_id != auth()->user()->hostel_id) {
                abort(403, 'तपाईंसँग यो कोठा प्रयोग गर्ने अनुमति छैन');
            }
            $newRoom->status = 'occupied';
            $newRoom->save();
        }

        $student->update($request->all());

        return redirect()->route('owner.students.index')
            ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो!');
    }

    public function destroy(Student $student)
    {
        if ($student->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो विद्यार्थी हटाउने अनुमति छैन');
        }

        // Room status उपलब्ध बनाउनुहोस्
        $room = $student->room;
        $room->status = 'available';
        $room->save();

        $student->delete();

        return redirect()->route('owner.students.index')
            ->with('success', 'विद्यार्थी सफलतापूर्वक हटाइयो!');
    }
}
