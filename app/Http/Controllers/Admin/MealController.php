<?php

namespace App\Http\Controllers\Admin;

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
        $meals = Meal::with(['student', 'hostel'])
            ->latest()
            ->paginate(10);

        return view('admin.meals.index', compact('meals'));
    }

    public function create()
    {
        $students = Student::where('status', 'active')->get();
        $hostels = Hostel::where('status', 'active')->get();
        return view('admin.meals.create', compact('students', 'hostels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'hostel_id' => 'required|exists:hostels,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'date' => 'required|date',
            'status' => 'required|in:pending,served,missed',
            'remarks' => 'nullable|string|max:500'
        ]);

        try {
            DB::transaction(function () use ($validated) {
                Meal::create($validated);
            });

            return redirect()->route('admin.meals.index')
                ->with('success', 'भोजन रेकर्ड सफलतापूर्वक थपियो');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'भोजन रेकर्ड थप्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function edit(Meal $meal)
    {
        $students = Student::where('status', 'active')->get();
        $hostels = Hostel::where('status', 'active')->get();
        return view('admin.meals.edit', compact('meal', 'students', 'hostels'));
    }

    public function update(Request $request, Meal $meal)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'hostel_id' => 'required|exists:hostels,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'date' => 'required|date',
            'status' => 'required|in:pending,served,missed',
            'remarks' => 'nullable|string|max:500'
        ]);

        try {
            DB::transaction(function () use ($meal, $validated) {
                $meal->update($validated);
            });

            return redirect()->route('admin.meals.index')
                ->with('success', 'भोजन अभिलेख सफलतापूर्वक अद्यावधिक गरियो');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'भोजन अभिलेख अद्यावधिक गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function destroy(Meal $meal)
    {
        try {
            $meal->delete();

            return redirect()->route('admin.meals.index')
                ->with('success', 'भोजन अभिलेख सफलतापूर्वक मेटाइयो');
        } catch (\Exception $e) {
            return redirect()->route('admin.meals.index')
                ->with('error', 'भोजन अभिलेख मेटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }
}
