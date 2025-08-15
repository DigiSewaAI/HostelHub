<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Hostel;
use App\Models\Meal;
use App\Models\Room;
use App\Models\Student;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    /**
     * Show the public home page.
     */
    public function home(): View
    {
        // 1. Featured Rooms (Available rooms with at least one vacancy)
        $featuredRooms = Room::where('status', 'available')
            ->withCount('students')
            ->having('students_count', '<', DB::raw('capacity'))
            ->orderBy('price')
            ->limit(3)
            ->get();

        // 2. System Metrics
        $metrics = [
            'total_hostels'     => Hostel::count(),
            'total_students'    => Student::where('status', 'active')->count(),
            'total_rooms'       => Room::count(),
            'available_rooms'   => Room::where('status', 'available')->count(),
            'occupancy_rate'    => $this->getOccupancyRate(),
        ];

        // 3. Featured Gallery Items
        $galleryItems = collect();
        if (class_exists(Gallery::class)) {
            $galleryItems = Gallery::where('category', 'featured')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        }

        // 4. Recent Meals (Today + next 2 days)
        $meals = collect();
        if (class_exists(Meal::class)) {
            $meals = Meal::whereDate('date', '>=', now()->format('Y-m-d'))
                ->orderBy('date')
                ->limit(3)
                ->get();
        }

        return view('public.home', compact(
            'featuredRooms',
            'metrics',
            'galleryItems', // fixed name
            'meals'
        ));
    }

    /**
     * Calculate room occupancy rate.
     */
    private function getOccupancyRate(): float
    {
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();

        if ($totalRooms > 0) {
            return round(($occupiedRooms / $totalRooms) * 100, 2);
        }

        return 0.0;
    }
}
