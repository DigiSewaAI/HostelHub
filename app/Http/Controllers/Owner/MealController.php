<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Student;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MealController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $hostelId = $user->hostel_id;

        $meals = Meal::with(['student', 'hostel'])
            ->where('hostel_id', $hostelId)
            ->latest()
            ->paginate(10);

        return view('owner.meals.index', compact('meals'));
    }

    public function create()
    {
        $user = auth()->user();
        $hostelId = $user->hostel_id;

        $students = Student::where('hostel_id', $hostelId)
            ->where('status', 'active')
            ->get();

        $hostels = Hostel::where('id', $hostelId)->get();

        return view('owner.meals.create', compact('students', 'hostels'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $hostelId = $user->hostel_id;

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'meal_date' => 'required|date',
            'status' => 'required|in:pending,served,missed',
            'remarks' => 'nullable|string|max:500'
        ]);

        // Add owner's hostel_id automatically
        $validated['hostel_id'] = $hostelId;

        try {
            DB::transaction(function () use ($validated) {
                Meal::create($validated);
            });

            return redirect()->route('owner.meals.index')
                ->with('success', 'भोजन अभिलेख सफलतापूर्वक थपियो');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'भोजन अभिलेख थप्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function edit(Meal $meal)
    {
        // Verify ownership
        $this->checkOwnership($meal);

        $user = auth()->user();
        $hostelId = $user->hostel_id;

        $students = Student::where('hostel_id', $hostelId)
            ->where('status', 'active')
            ->get();

        $hostels = Hostel::where('id', $hostelId)->get();

        return view('owner.meals.edit', compact('meal', 'students', 'hostels'));
    }

    public function update(Request $request, Meal $meal)
    {
        // Verify ownership
        $this->checkOwnership($meal);

        $user = auth()->user();
        $hostelId = $user->hostel_id;

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'meal_date' => 'required|date',
            'status' => 'required|in:pending,served,missed',
            'remarks' => 'nullable|string|max:500'
        ]);

        // Ensure hostel_id remains the same
        $validated['hostel_id'] = $hostelId;

        try {
            DB::transaction(function () use ($meal, $validated) {
                $meal->update($validated);
            });

            return redirect()->route('owner.meals.index')
                ->with('success', 'भोजन अभिलेख सफलतापूर्वक अद्यावधिक गरियो');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'भोजन अभिलेख अद्यावधिक गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function destroy(Meal $meal)
    {
        // Verify ownership
        $this->checkOwnership($meal);

        try {
            $meal->delete();

            return redirect()->route('owner.meals.index')
                ->with('success', 'भोजन अभिलेख सफलतापूर्वक मेटाइयो');
        } catch (\Exception $e) {
            return redirect()->route('owner.meals.index')
                ->with('error', 'भोजन अभिलेख मेटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Check if the user has permission to access the meal
     */
    private function checkOwnership(Meal $meal)
    {
        $user = auth()->user();

        if ($meal->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो भोजन अभिलेख एक्सेस गर्ने अनुमति छैन');
        }

        return true;
    }
}
