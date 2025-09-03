<?php

namespace App\Http\Controllers\Owner;

use App\Models\MealMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MealMenusController extends Controller
{
    public function index()
    {
        // केवल आफ्नो होस्टलको मेनु मात्र हेर्नुहोस्
        $mealMenus = MealMenu::where('hostel_id', auth()->user()->hostel_id)->get();
        return view('owner.meal-menus.index', compact('mealMenus'));
    }

    public function create()
    {
        return view('owner.meal-menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'meal_type' => 'required',
            'items' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['hostel_id'] = auth()->user()->hostel_id;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/meal-menus'), $imageName);
            $data['image'] = 'images/meal-menus/' . $imageName;
        }

        MealMenu::create($data);

        return redirect()->route('owner.meal-menus.index')
            ->with('success', 'मेनु सफलतापूर्वक थपियो!');
    }

    public function edit(MealMenu $mealMenu)
    {
        // सुनिश्चित गर्नुहोस् कि यो मेनु आफ्नो होस्टलको हो
        if ($mealMenu->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो मेनु सम्पादन गर्ने अनुमति छैन');
        }

        return view('owner.meal-menus.edit', compact('mealMenu'));
    }

    public function update(Request $request, MealMenu $mealMenu)
    {
        // सुनिश्चित गर्नुहोस् कि यो मेनु आफ्नो होस्टलको हो
        if ($mealMenu->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो मेनु सम्पादन गर्ने अनुमति छैन');
        }

        $request->validate([
            'day' => 'required',
            'meal_type' => 'required',
            'items' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // पुरानो इमेज हटाउनुहोस्
            if ($mealMenu->image && file_exists(public_path($mealMenu->image))) {
                unlink(public_path($mealMenu->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/meal-menus'), $imageName);
            $data['image'] = 'images/meal-menus/' . $imageName;
        }

        $mealMenu->update($data);

        return redirect()->route('owner.meal-menus.index')
            ->with('success', 'मेनु सफलतापूर्वक अद्यावधिक गरियो!');
    }

    public function destroy(MealMenu $mealMenu)
    {
        // सुनिश्चित गर्नुहोस् कि यो मेनु आफ्नो होस्टलको हो
        if ($mealMenu->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो मेनु हटाउने अनुमति छैन');
        }

        // इमेज हटाउनुहोस्
        if ($mealMenu->image && file_exists(public_path($mealMenu->image))) {
            unlink(public_path($mealMenu->image));
        }

        $mealMenu->delete();

        return redirect()->route('owner.meal-menus.index')
            ->with('success', 'मेनु सफलतापूर्वक हटाइयो!');
    }
}
