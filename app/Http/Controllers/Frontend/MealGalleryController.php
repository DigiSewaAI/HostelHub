<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MealMenu;
use App\Models\Hostel;
use Illuminate\Http\Request;

class MealGalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = MealMenu::with('hostel')
            ->where('is_active', true);

        // Filter by meal type
        if ($request->filled('type') && $request->type != 'all') {
            $query->where('meal_type', $request->type);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('hostel', function ($hostelQuery) use ($search) {
                        $hostelQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $mealMenus = $query->latest()->get();

        // ✅ FIXED: Get UNIQUE hostels with active meals
        $hostelIdsWithMeals = MealMenu::where('is_active', true)
            ->pluck('hostel_id')
            ->unique()
            ->toArray();

        $featuredHostels = Hostel::whereIn('id', $hostelIdsWithMeals)
            ->where('status', 'active')
            ->where('is_published', true)
            ->select('id', 'name', 'logo_path', 'address', 'city')
            ->get();

        return view('frontend.pages.meal-gallery', compact('mealMenus', 'featuredHostels'));
    }
}
