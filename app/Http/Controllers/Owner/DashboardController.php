<?php

namespace App\Http\Controllers\Owner;

use App\Models\Hostel;
use App\Models\MealMenu;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // होस्टल मालिकको आफ्नै होस्टलको डाटा मात्र हेर्नुहोस्
        $hostel = Hostel::where('id', auth()->user()->hostel_id)->first();
        $totalRooms = Room::where('hostel_id', auth()->user()->hostel_id)->count();
        $occupiedRooms = Room::where('hostel_id', auth()->user()->hostel_id)
            ->where('status', 'occupied')
            ->count();
        $totalStudents = Student::where('hostel_id', auth()->user()->hostel_id)->count();
        $todayMeal = MealMenu::where('hostel_id', auth()->user()->hostel_id)
            ->where('day', now()->format('l'))
            ->first();

        return view('owner.dashboard.index', compact(
            'hostel',
            'totalRooms',
            'occupiedRooms',
            'totalStudents',
            'todayMeal'
        ));
    }
}
