<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\MealMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealMenuController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $hostelId = $user->hostel_id;

        $mealMenus = MealMenu::with('hostel')
            ->where('hostel_id', $hostelId)
            ->latest()
            ->get();

        return view('owner.meal-menus.index', compact('mealMenus'));
    }

    public function create()
    {
        $user = auth()->user();
        $hostelId = $user->hostel_id;

        $hostels = Hostel::where('id', $hostelId)->get();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];

        return view('owner.meal-menus.create', compact('hostels', 'days', 'mealTypes'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $hostelId = $user->hostel_id;

        $request->validate([
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'day_of_week' => 'required|string',
            'items' => 'required|array',
            'items.*' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Set owner's hostel_id automatically
        $data = $request->only(['meal_type', 'day_of_week', 'items', 'description']);
        $data['hostel_id'] = $hostelId;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        MealMenu::create($data);

        return redirect()->route('owner.meal-menus.index')
            ->with('success', 'खानाको योजना सफलतापूर्वक थपियो!');
    }

    public function show(MealMenu $mealMenu)
    {
        // Check if user has permission to view this meal menu
        $this->checkOwnership($mealMenu);

        return view('owner.meal-menus.show', compact('mealMenu'));
    }

    public function edit(MealMenu $mealMenu)
    {
        // Check if user has permission to edit this meal menu
        $this->checkOwnership($mealMenu);

        $user = auth()->user();
        $hostelId = $user->hostel_id;

        $hostels = Hostel::where('id', $hostelId)->get();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];

        return view('owner.meal-menus.edit', compact('mealMenu', 'hostels', 'days', 'mealTypes'));
    }

    public function update(Request $request, MealMenu $mealMenu)
    {
        // Check if user has permission to update this meal menu
        $this->checkOwnership($mealMenu);

        $user = auth()->user();
        $hostelId = $user->hostel_id;

        $request->validate([
            'meal_type' => 'required|in:breakfast,lunch,dinner',
            'day_of_week' => 'required|string',
            'items' => 'required|array',
            'items.*' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->only(['meal_type', 'day_of_week', 'items', 'description']);
        $data['hostel_id'] = $hostelId; // Ensure hostel_id remains the same
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($mealMenu->image) {
                Storage::disk('public')->delete($mealMenu->image);
            }
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        $mealMenu->update($data);

        return redirect()->route('owner.meal-menus.index')
            ->with('success', 'खानाको योजना सफलतापूर्वक अपडेट गरियो!');
    }

    public function destroy(MealMenu $mealMenu)
    {
        // Check if user has permission to delete this meal menu
        $this->checkOwnership($mealMenu);

        if ($mealMenu->image) {
            Storage::disk('public')->delete($mealMenu->image);
        }
        $mealMenu->delete();

        return redirect()->route('owner.meal-menus.index')
            ->with('success', 'खानाको योजना हटाइयो।');
    }

    /**
     * Check if the user has permission to access the meal menu
     */
    private function checkOwnership(MealMenu $mealMenu)
    {
        $user = auth()->user();

        if ($mealMenu->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो मेनु एक्सेस गर्ने अनुमति छैन');
        }

        return true;
    }
}
