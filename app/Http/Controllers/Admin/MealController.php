// app/Http/Controllers/Admin/MealController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Student;
use App\Models\Hostel;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::with(['student', 'hostel'])
                    ->latest()
                    ->paginate(10);

        return view('admin.meals.index', compact('meals'));
    }

    public function create()
    {
        $students = Student::all();
        $hostels = Hostel::all();
        return view('admin.meals.create', compact('students', 'hostels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'hostel_id' => 'required|exists:hostels,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'meal_date' => 'required|date',
            'status' => 'required|in:pending,served,missed',
            'remarks' => 'nullable|string'
        ]);

        Meal::create($request->all());

        return redirect()->route('admin.meals.index')
            ->with('success', 'भोजन अभिलेख सफलतापूर्वक थपियो');
    }

    public function edit(Meal $meal)
    {
        $students = Student::all();
        $hostels = Hostel::all();
        return view('admin.meals.edit', compact('meal', 'students', 'hostels'));
    }

    public function update(Request $request, Meal $meal)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'hostel_id' => 'required|exists:hostels,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'meal_date' => 'required|date',
            'status' => 'required|in:pending,served,missed',
            'remarks' => 'nullable|string'
        ]);

        $meal->update($request->all());

        return redirect()->route('admin.meals.index')
            ->with('success', 'भोजन अभिलेख सफलतापूर्वक अद्यावधिक गरियो');
    }

    public function destroy(Meal $meal)
    {
        $meal->delete();
        return redirect()->route('admin.meals.index')
            ->with('success', 'भोजन अभिलेख सफलतापूर्वक मेटाइयो');
    }
}
