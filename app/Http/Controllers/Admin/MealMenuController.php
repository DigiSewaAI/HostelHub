<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\MealMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealMenuController extends Controller
{
    public function index()
    {
        $mealMenus = MealMenu::with('hostel')->latest()->get();
        return view('admin.meal-menus.index', compact('mealMenus'));
    }

    public function create()
    {
        $hostels = Hostel::all();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];
        return view('admin.meal-menus.create', compact('hostels', 'days', 'mealTypes'));
    }

    public function store(Request $request)
    {
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

        $data = $request->only(['hostel_id', 'meal_type', 'day_of_week', 'items', 'description']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        MealMenu::create($data);

        return redirect()->route('admin.meal-menus.index')
            ->with('success', 'खानाको योजना सफलतापूर्वक थपियो!');
    }

    public function edit(MealMenu $mealMenu)
    {
        $hostels = Hostel::all();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];
        return view('admin.meal-menus.edit', compact('mealMenu', 'hostels', 'days', 'mealTypes'));
    }

    public function update(Request $request, MealMenu $mealMenu)
    {
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

        $data = $request->only(['hostel_id', 'meal_type', 'day_of_week', 'items', 'description']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($mealMenu->image) {
                Storage::disk('public')->delete($mealMenu->image);
            }
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        $mealMenu->update($data);

        return redirect()->route('admin.meal-menus.index')
            ->with('success', 'खानाको योजना सफलतापूर्वक अपडेट गरियो!');
    }

    public function destroy(MealMenu $mealMenu)
    {
        if ($mealMenu->image) {
            Storage::disk('public')->delete($mealMenu->image);
        }
        $mealMenu->delete();

        return redirect()->route('admin.meal-menus.index')
            ->with('success', 'खानाको योजना हटाइयो।');
    }
}
