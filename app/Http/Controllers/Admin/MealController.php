<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MealController extends Controller
{
    /**
     * Display a listing of meals based on user role.
     */
    public function index()
    {
        $user = auth()->user();

        // ✅ SECURITY FIX: Authorization check
        if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग यो सूची हेर्ने अनुमति छैन');
        }

        // Check user role and fetch meals accordingly
        if ($user->hasRole('admin')) {
            $meals = Meal::with(['student', 'hostel'])
                ->latest()
                ->paginate(10);
        } elseif ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                return view('owner.meals.index', ['meals' => collect()])
                    ->with('error', 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');
            $meals = Meal::whereIn('hostel_id', $hostelIds)
                ->with(['student', 'hostel'])
                ->latest()
                ->paginate(10);

            return view('owner.meals.index', compact('meals'));
        }

        return view('admin.meals.index', compact('meals'));
    }

    /**
     * Show the form for creating a new meal.
     */
    public function create()
    {
        $user = auth()->user();

        // ✅ SECURITY FIX: Authorization check
        if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग यो सिर्जना गर्ने अनुमति छैन');
        }

        if ($user->hasRole('admin')) {
            $students = Student::where('status', 'active')->get();
            $hostels = Hostel::where('status', 'active')->get();
            return view('admin.meals.create', compact('students', 'hostels'));
        } elseif ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                return redirect()->route('owner.meals.index')
                    ->with('error', 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');
            $students = Student::whereIn('hostel_id', $hostelIds)->where('status', 'active')->get();
            $hostels = $organization->hostels()->where('status', 'active')->get();

            return view('owner.meals.create', compact('students', 'hostels'));
        }
    }

    /**
     * Store a newly created meal in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // ✅ SECURITY FIX: Authorization check
        if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग यो सिर्जना गर्ने अनुमति छैन');
        }

        // ✅ SECURITY FIX: Mass assignment protection - use validated data only
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'hostel_id' => 'required|exists:hostels,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'date' => 'required|date',
            'status' => 'required|in:pending,served,missed',
            'remarks' => 'nullable|string|max:500'
        ]);

        // ✅ SECURITY FIX: Additional authorization for hostel_manager
        if ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');
            if (!$hostelIds->contains($validated['hostel_id'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'तपाईंसँग यो होस्टलमा भोजन रेकर्ड थप्ने अनुमति छैन');
            }

            // Check if student belongs to one of the organization's hostels
            $student = Student::find($validated['student_id']);
            if (!$student || !$hostelIds->contains($student->hostel_id)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'तपाईंसँग यो विद्यार्थीको लागि भोजन रेकर्ड थप्ने अनुमति छैन');
            }
        }

        try {
            DB::transaction(function () use ($validated) {
                Meal::create($validated);
            });

            $route = $user->hasRole('admin') ? 'admin.meals.index' : 'owner.meals.index';
            return redirect()->route($route)
                ->with('success', 'भोजन रेकर्ड सफलतापूर्वक थपियो');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'भोजन रेकर्ड थप्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified meal.
     */
    public function edit(Meal $meal)
    {
        $user = auth()->user();

        // ✅ SECURITY FIX: Authorization check
        if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग यो सम्पादन गर्ने अनुमति छैन');
        }

        // ✅ SECURITY FIX: Additional authorization for hostel_manager
        if ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                abort(403, 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');
            if (!$hostelIds->contains($meal->hostel_id)) {
                abort(403, 'तपाईंसँग यो भोजन रेकर्ड सम्पादन गर्ने अनुमति छैन');
            }

            $students = Student::whereIn('hostel_id', $hostelIds)->where('status', 'active')->get();
            $hostels = $organization->hostels()->where('status', 'active')->get();
            return view('owner.meals.edit', compact('meal', 'students', 'hostels'));
        }

        if ($user->hasRole('admin')) {
            $students = Student::where('status', 'active')->get();
            $hostels = Hostel::where('status', 'active')->get();
            return view('admin.meals.edit', compact('meal', 'students', 'hostels'));
        }
    }

    /**
     * Update the specified meal in storage.
     */
    public function update(Request $request, Meal $meal)
    {
        $user = auth()->user();

        // ✅ SECURITY FIX: Authorization check
        if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग यो अद्यावधिक गर्ने अनुमति छैन');
        }

        // ✅ SECURITY FIX: Mass assignment protection - use validated data only
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'hostel_id' => 'required|exists:hostels,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'date' => 'required|date',
            'status' => 'required|in:pending,served,missed',
            'remarks' => 'nullable|string|max:500'
        ]);

        // ✅ SECURITY FIX: Additional authorization for hostel_manager
        if ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                abort(403, 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');

            // Check current meal authorization
            if (!$hostelIds->contains($meal->hostel_id)) {
                abort(403, 'तपाईंसँग यो भोजन रेकर्ड अद्यावधिक गर्ने अनुमति छैन');
            }

            // Check new hostel_id authorization
            if (!$hostelIds->contains($validated['hostel_id'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'तपाईंसँग यो होस्टलमा भोजन रेकर्ड अद्यावधिक गर्ने अनुमति छैन');
            }

            // Check if student belongs to one of the organization's hostels
            $student = Student::find($validated['student_id']);
            if (!$student || !$hostelIds->contains($student->hostel_id)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'तपाईंसँग यो विद्यार्थीको लागि भोजन रेकर्ड अद्यावधिक गर्ने अनुमति छैन');
            }
        }

        try {
            DB::transaction(function () use ($meal, $validated) {
                $meal->update($validated);
            });

            $route = $user->hasRole('admin') ? 'admin.meals.index' : 'owner.meals.index';
            return redirect()->route($route)
                ->with('success', 'भोजन अभिलेख सफलतापूर्वक अद्यावधिक गरियो');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'भोजन अभिलेख अद्यावधिक गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified meal from storage.
     */
    public function destroy(Meal $meal)
    {
        $user = auth()->user();

        // ✅ SECURITY FIX: Authorization check
        if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग यो मेटाउने अनुमति छैन');
        }

        // ✅ SECURITY FIX: Additional authorization for hostel_manager
        if ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                abort(403, 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');
            if (!$hostelIds->contains($meal->hostel_id)) {
                abort(403, 'तपाईंसँग यो भोजन रेकर्ड मेटाउने अनुमति छैन');
            }
        }

        try {
            $meal->delete();

            $route = $user->hasRole('admin') ? 'admin.meals.index' : 'owner.meals.index';
            return redirect()->route($route)
                ->with('success', 'भोजन अभिलेख सफलतापूर्वक मेटाइयो');
        } catch (\Exception $e) {
            return redirect()->route($user->hasRole('admin') ? 'admin.meals.index' : 'owner.meals.index')
                ->with('error', 'भोजन अभिलेख मेटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * ✅ NEW: Search meals functionality with security fixes
     */
    public function search(Request $request)
    {
        $user = auth()->user();

        // ✅ SECURITY FIX: Authorization check
        if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग यो खोज गर्ने अनुमति छैन');
        }

        $request->validate([
            'search' => 'required|string|min:2'
        ], [
            'search.required' => 'खोज शब्द आवश्यक छ',
            'search.min' => 'खोज शब्द कम्तिमा २ अक्षरको हुनुपर्छ'
        ]);

        $query = $request->input('search');

        // ✅ SECURITY FIX: SQL Injection prevention in search
        $safeQuery = '%' . addcslashes($query, '%_') . '%';

        if ($user->hasRole('admin')) {
            $meals = Meal::whereHas('student', function ($q) use ($safeQuery) {
                $q->where('name', 'like', $safeQuery);
            })
                ->orWhereHas('hostel', function ($q) use ($safeQuery) {
                    $q->where('name', 'like', $safeQuery);
                })
                ->orWhere('meal_type', 'like', $safeQuery)
                ->orWhere('status', 'like', $safeQuery)
                ->orWhere('date', 'like', $safeQuery)
                ->with(['student', 'hostel'])
                ->latest()
                ->paginate(10);

            return view('admin.meals.index', compact('meals'));
        } elseif ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->wherePivot('role', 'owner')->first();

            if (!$organization) {
                return view('owner.meals.index', ['meals' => collect()])
                    ->with('error', 'तपाईंको संस्था फेला परेन');
            }

            $hostelIds = $organization->hostels->pluck('id');
            $meals = Meal::whereIn('hostel_id', $hostelIds)
                ->where(function ($q) use ($safeQuery) {
                    $q->whereHas('student', function ($q2) use ($safeQuery) {
                        $q2->where('name', 'like', $safeQuery);
                    })
                        ->orWhereHas('hostel', function ($q2) use ($safeQuery) {
                            $q2->where('name', 'like', $safeQuery);
                        })
                        ->orWhere('meal_type', 'like', $safeQuery)
                        ->orWhere('status', 'like', $safeQuery)
                        ->orWhere('date', 'like', $safeQuery);
                })
                ->with(['student', 'hostel'])
                ->latest()
                ->paginate(10);

            return view('owner.meals.index', compact('meals'));
        }
    }

    /**
     * ✅ NEW: Bulk status update for meals
     */
    public function bulkStatusUpdate(Request $request)
    {
        $user = auth()->user();

        // ✅ SECURITY FIX: Authorization check
        if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

        $validated = $request->validate([
            'meal_ids' => 'required|array',
            'meal_ids.*' => 'exists:meals,id',
            'status' => 'required|in:pending,served,missed'
        ]);

        try {
            DB::transaction(function () use ($validated, $user) {
                $query = Meal::whereIn('id', $validated['meal_ids']);

                // ✅ SECURITY FIX: Additional authorization for hostel_manager
                if ($user->hasRole('hostel_manager')) {
                    $organization = $user->organizations()->wherePivot('role', 'owner')->first();

                    if (!$organization) {
                        throw new \Exception('तपाईंको संस्था फेला परेन');
                    }

                    $hostelIds = $organization->hostels->pluck('id');
                    $query->whereIn('hostel_id', $hostelIds);
                }

                $query->update(['status' => $validated['status']]);
            });

            $route = $user->hasRole('admin') ? 'admin.meals.index' : 'owner.meals.index';
            return redirect()->route($route)
                ->with('success', 'भोजन स्थिति सफलतापूर्वक अद्यावधिक गरियो');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'भोजन स्थिति अद्यावधिक गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }
}
