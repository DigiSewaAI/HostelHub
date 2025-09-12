<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\MealMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealMenuController extends Controller
{
    /**
     * Get meal menus based on user role
     */
    private function getDataByRole()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // Admin can see all meal menus
            return MealMenu::with('hostel')->latest()->get();
        } elseif ($user->hasRole('hostel_manager')) {
            // Owner can only see their hostel's meal menus
            return MealMenu::with('hostel')
                ->where('hostel_id', $user->hostel_id)
                ->latest()
                ->get();
        } elseif ($user->hasRole('student')) {
            // Student can only see their hostel's meal menus
            return MealMenu::with('hostel')
                ->where('hostel_id', $user->hostel_id)
                ->where('is_active', true)
                ->latest()
                ->get();
        }

        return collect();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mealMenus = $this->getDataByRole();

        // Return appropriate view based on role
        if (auth()->user()->hasRole('student')) {
            return view('student.meal-menus.index', compact('mealMenus'));
        }

        return view('admin.meal-menus.index', compact('mealMenus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admin and owner can create meal menus
        if (auth()->user()->hasRole('student')) {
            abort(403, 'Unauthorized action.');
        }

        $hostels = Hostel::all();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];

        // If user is owner, only show their hostel
        if (auth()->user()->hasRole('hostel_manager')) {
            $hostels = Hostel::where('id', auth()->user()->hostel_id)->get();
        }

        return view('admin.meal-menus.create', compact('hostels', 'days', 'mealTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only admin and owner can store meal menus
        if (auth()->user()->hasRole('student')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'day_of_week' => 'required|string',
            'items' => 'required|array',
            'items.*' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // If user is owner, ensure they can only create for their hostel
        if (auth()->user()->hasRole('hostel_manager')) {
            $request->merge(['hostel_id' => auth()->user()->hostel_id]);
        }

        $data = $request->only(['hostel_id', 'meal_type', 'day_of_week', 'items', 'description']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        MealMenu::create($data);

        return redirect()->route('meal-menus.index')
            ->with('success', 'खानाको योजना सफलतापूर्वक थपियो!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MealMenu $mealMenu)
    {
        // Check if user has permission to view this meal menu
        $this->checkOwnership($mealMenu);

        if (auth()->user()->hasRole('student')) {
            return view('student.meal-menus.show', compact('mealMenu'));
        }

        return view('admin.meal-menus.show', compact('mealMenu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MealMenu $mealMenu)
    {
        // Only admin and owner can edit meal menus
        if (auth()->user()->hasRole('student')) {
            abort(403, 'Unauthorized action.');
        }

        // Check if user has permission to edit this meal menu
        $this->checkOwnership($mealMenu);

        $hostels = Hostel::all();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];

        // If user is owner, only show their hostel
        if (auth()->user()->hasRole('hostel_manager')) {
            $hostels = Hostel::where('id', auth()->user()->hostel_id)->get();
        }

        return view('admin.meal-menus.edit', compact('mealMenu', 'hostels', 'days', 'mealTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MealMenu $mealMenu)
    {
        // Only admin and owner can update meal menus
        if (auth()->user()->hasRole('student')) {
            abort(403, 'Unauthorized action.');
        }

        // Check if user has permission to update this meal menu
        $this->checkOwnership($mealMenu);

        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'day_of_week' => 'required|string',
            'items' => 'required|array',
            'items.*' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // If user is owner, ensure they can only update for their hostel
        if (auth()->user()->hasRole('hostel_manager')) {
            $request->merge(['hostel_id' => auth()->user()->hostel_id]);
        }

        $data = $request->only(['hostel_id', 'meal_type', 'day_of_week', 'items', 'description']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($mealMenu->image) {
                Storage::disk('public')->delete($mealMenu->image);
            }
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        $mealMenu->update($data);

        return redirect()->route('meal-menus.index')
            ->with('success', 'खानाको योजना सफलतापूर्वक अपडेट गरियो!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MealMenu $mealMenu)
    {
        // Only admin and owner can delete meal menus
        if (auth()->user()->hasRole('student')) {
            abort(403, 'Unauthorized action.');
        }

        // Check if user has permission to delete this meal menu
        $this->checkOwnership($mealMenu);

        if ($mealMenu->image) {
            Storage::disk('public')->delete($mealMenu->image);
        }
        $mealMenu->delete();

        return redirect()->route('meal-menus.index')
            ->with('success', 'खानाको योजना हटाइयो।');
    }

    /**
     * Check if the user has permission to access the meal menu
     */
    private function checkOwnership(MealMenu $mealMenu)
    {
        $user = auth()->user();

        // Admin can access all meal menus
        if ($user->hasRole('admin')) {
            return true;
        }

        // Owner and Student can only access their hostel's meal menus
        if ($user->hasRole('hostel_manager') || $user->hasRole('student')) {
            if ($mealMenu->hostel_id != $user->hostel_id) {
                abort(403, 'तपाईंसँग यो मेनु एक्सेस गर्ने अनुमति छैन');
            }
        }

        return true;
    }
}
