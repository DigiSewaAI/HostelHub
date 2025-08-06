<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Meal;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Show the public home page.
     */
    public function home(): View
    {
        // 1. Featured Rooms (Available rooms with at least one vacancy)
        $featuredRooms = Room::available()
            ->withCount('students')
            ->having('students_count', '<', 'capacity')
            ->orderBy('price')
            ->limit(3)
            ->get();

        // 2. System Metrics (Using query scopes for cleaner code)
        $metrics = [
            'total_students' => Student::active()->count(),
            'total_rooms' => Room::count(),
            'available_rooms' => Room::available()->count(),
            'occupancy_rate' => Room::getOccupancyRate(),
        ];

        // 3. Featured Gallery Images (Only featured items)
        // यदि Gallery मोडेल छैन भने, यो अस्थायी रूपमा कमेन्ट गर्नुहोस्
        $galleries = collect();
        if (class_exists(\App\Models\Gallery::class)) {
            $galleries = Gallery::where('category', 'featured')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        }

        // 4. Recent Meals (Today + next 2 days)
        // यदि Meal मोडेल छैन भने, यो अस्थायी रूपमा कमेन्ट गर्नुहोस्
        $meals = collect();
        if (class_exists(\App\Models\Meal::class)) {
            $meals = Meal::whereDate('date', '>=', now()->format('Y-m-d'))
                ->orderBy('date')
                ->limit(3)
                ->get();
        }

        return view('public.home', compact('featuredRooms', 'metrics', 'galleries', 'meals'));
    }
}
