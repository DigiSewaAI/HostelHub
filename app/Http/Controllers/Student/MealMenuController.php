<?php

namespace App\Http\Controllers\Student;

use App\Models\MealMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MealMenuController extends Controller
{
    public function index()
    {
        // आफ्नो होस्टलको मेनुहरू हेर्नुहोस्
        $mealMenus = MealMenu::where('hostel_id', auth()->user()->hostel_id)->get();
        return view('student.meal-menus.index', compact('mealMenus'));
    }
}
