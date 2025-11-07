<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MealMenu;
use Illuminate\Http\Request;

class MealGalleryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');

        $mealMenus = MealMenu::with('hostel')
            ->where('is_active', true)
            ->when($search, function ($query) use ($search) {
                return $query->where('description', 'like', "%{$search}%")
                    ->orWhereHas('hostel', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($type && $type != 'all', function ($query) use ($type) {
                return $query->where('meal_type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.pages.meal-gallery', compact('mealMenus', 'search', 'type'));
    }
}
