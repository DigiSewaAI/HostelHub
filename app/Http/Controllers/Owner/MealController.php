<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Student;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // ✅ ADDED for security

class MealController extends Controller
{
    // ✅ ADDED: Student authorization helper
    private function authorizeStudentAccess($studentId = null)
    {
        $user = Auth::user();

        if ($user->hasRole('student')) {
            // Students can only access their own data
            if ($studentId && $studentId != $user->id) {
                abort(403, 'तपाईंसँग अरू विद्यार्थीको डाटा एक्सेस गर्ने अनुमति छैन');
            }

            // Ensure student has a valid student record
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                abort(403, 'तपाईंसँग विद्यार्थी रेकर्ड छैन');
            }
        }

        return true;
    }

    // ✅ ADDED: Data scoping for students
    private function scopeForStudent($query)
    {
        $user = Auth::user();

        if ($user->hasRole('student')) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                return $query->where('student_id', $student->id);
            }
        }

        return $query;
    }

    // ✅ ADDED: Owner authorization helper
    private function authorizeOwnerAccess()
    {
        $user = Auth::user();

        if ($user->hasRole('hostel_manager')) {
            if (!$user->hostel_id) {
                abort(403, 'तपाईंसँग कुनै होस्टल सम्बन्धित छैन');
            }
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $hostelId = $user->hostel_id;

        // ✅ ENHANCED: Student authorization and data scoping
        if ($user->hasRole('student')) {
            $this->authorizeStudentAccess();

            // Students can only see their own meals
            $meals = $this->scopeForStudent(
                Meal::with(['student', 'hostel'])
            )->latest()->paginate(10);
        } else {
            // ✅ ENHANCED: Owner authorization
            if ($user->hasRole('hostel_manager')) {
                $this->authorizeOwnerAccess();
            }

            // Owners see all meals for their hostel
            $meals = Meal::with(['student', 'hostel'])
                ->where('hostel_id', $hostelId)
                ->latest()
                ->paginate(10);
        }

        return view('owner.meals.index', compact('meals'));
    }

    public function create()
    {
        $user = auth()->user();

        // ✅ ENHANCED: Authorization - only owners can create
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग भोजन रेकर्ड सिर्जना गर्ने अनुमति छैन');
        }

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

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

        // ✅ ENHANCED: Authorization - only owners can create
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग भोजन रेकर्ड सिर्जना गर्ने अनुमति छैन');
        }

        // ✅ ENHANCED: Owner authorization
        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        $hostelId = $user->hostel_id;

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'meal_date' => 'required|date',
            'status' => 'required|in:pending,served,missed',
            'remarks' => 'nullable|string|max:500'
        ]);

        // ✅ ENHANCED: Verify student belongs to owner's hostel
        if ($user->hasRole('hostel_manager')) {
            $student = Student::find($validated['student_id']);
            if (!$student || $student->hostel_id != $hostelId) {
                abort(403, 'यो विद्यार्थी तपाईंको होस्टलमा छैन');
            }
        }

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
        $user = auth()->user();

        // ✅ ENHANCED: Student authorization - students cannot edit meals
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग भोजन अभिलेख सम्पादन गर्ने अनुमति छैन');
        }

        // Verify ownership
        $this->checkOwnership($meal);

        $hostelId = $user->hostel_id;

        $students = Student::where('hostel_id', $hostelId)
            ->where('status', 'active')
            ->get();

        $hostels = Hostel::where('id', $hostelId)->get();

        return view('owner.meals.edit', compact('meal', 'students', 'hostels'));
    }

    public function update(Request $request, Meal $meal)
    {
        $user = auth()->user();

        // ✅ ENHANCED: Student authorization - students cannot update meals
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग भोजन अभिलेख अद्यावधिक गर्ने अनुमति छैन');
        }

        // Verify ownership
        $this->checkOwnership($meal);

        $hostelId = $user->hostel_id;

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'meal_date' => 'required|date',
            'status' => 'required|in:pending,served,missed',
            'remarks' => 'nullable|string|max:500'
        ]);

        // ✅ ENHANCED: Verify student belongs to owner's hostel
        if ($user->hasRole('hostel_manager')) {
            $student = Student::find($validated['student_id']);
            if (!$student || $student->hostel_id != $hostelId) {
                abort(403, 'यो विद्यार्थी तपाईंको होस्टलमा छैन');
            }
        }

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
        $user = auth()->user();

        // ✅ ENHANCED: Student authorization - students cannot delete meals
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग भोजन अभिलेख मेटाउने अनुमति छैन');
        }

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

        // ✅ ENHANCED: Student can only access their own meals
        if ($user->hasRole('student')) {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student || $meal->student_id != $student->id) {
                abort(403, 'तपाईंसँग यो भोजन अभिलेख एक्सेस गर्ने अनुमति छैन');
            }
            return true;
        }

        // Owner can access meals from their hostel
        if ($meal->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो भोजन अभिलेख एक्सेस गर्ने अनुमति छैन');
        }

        return true;
    }

    // ✅ ADDED: Student-specific method to view their own meal details
    public function show(Meal $meal)
    {
        $user = auth()->user();

        // Verify ownership with enhanced security
        $this->checkOwnership($meal);

        return view('owner.meals.show', compact('meal'));
    }

    // ✅ ADDED: Student meal summary for current user
    public function myMeals()
    {
        $user = auth()->user();

        // Only for students
        if (!$user->hasRole('student')) {
            abort(403, 'यो पृष्ठ केवल विद्यार्थीहरूको लागि मात्र हो');
        }

        $this->authorizeStudentAccess();

        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            abort(403, 'विद्यार्थी रेकर्ड फेला परेन');
        }

        $meals = Meal::with(['student', 'hostel'])
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(10);

        return view('owner.meals.my-meals', compact('meals', 'student'));
    }
}
