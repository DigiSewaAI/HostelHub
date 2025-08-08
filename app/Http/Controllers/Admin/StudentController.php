// app/Http/Controllers/Admin/StudentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['hostel', 'room'])->latest()->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $hostels = Hostel::all();
        $rooms = Room::where('status', 'available')->get();
        return view('admin.students.create', compact('hostels', 'rooms'));
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('students', 'public');
        }

        Student::create($data);

        // Update room status
        Room::find($data['room_id'])->update(['status' => 'occupied']);

        return redirect()->route('admin.students.index')
            ->with('success', 'विद्यार्थी सफलतापूर्वक दर्ता गरियो');
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $hostels = Hostel::all();
        $rooms = Room::where('status', 'available')
                    ->orWhere('id', $student->room_id)
                    ->get();

        return view('admin.students.edit', compact('student', 'hostels', 'rooms'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }
            $data['image'] = $request->file('image')->store('students', 'public');
        }

        // Update room status if changed
        if ($student->room_id != $data['room_id']) {
            Room::find($student->room_id)->update(['status' => 'available']);
            Room::find($data['room_id'])->update(['status' => 'occupied']);
        }

        $student->update($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'विद्यार्थी विवरण सफलतापूर्वक अद्यावधिक गरियो');
    }

    public function destroy(Student $student)
    {
        if ($student->image) {
            Storage::disk('public')->delete($student->image);
        }

        // Free up the room
        Room::find($student->room_id)->update(['status' => 'available']);

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'विद्यार्थी रेकर्ड सफलतापूर्वक मेटाइयो');
    }
}
