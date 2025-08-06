<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with key metrics and recent activities.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        try {
            // Fetch metrics with optimized queries
            $totalStudents = Student::count();
            $totalRooms = Room::count();

            // Batch room status queries for efficiency
            $roomStatus = Room::selectRaw('
                COUNT(CASE WHEN status = "available" THEN 1 END) as available,
                COUNT(CASE WHEN status = "occupied" THEN 1 END) as occupied,
                COUNT(CASE WHEN status = "maintenance" THEN 1 END) as maintenance
            ')->first();

            // Calculate occupancy rate safely
            $roomOccupancy = $totalRooms > 0
                ? round(($roomStatus->occupied / $totalRooms) * 100, 1)
                : 0;

            // Get recent records with pagination for large datasets
            $recentStudents = Student::with('room')
                ->latest('created_at')
                ->take(5)
                ->get();

            $recentContacts = Contact::latest('created_at')
                ->take(5)
                ->get();

            // Prepare metrics array
            $metrics = [
                'total_students' => $totalStudents,
                'total_rooms' => $totalRooms,
                'room_occupancy' => $roomOccupancy,
                'available_rooms' => $roomStatus->available,
                'occupied_rooms' => $roomStatus->occupied,
                'maintenance_rooms' => $roomStatus->maintenance,
                'recent_students' => $recentStudents,
                'recent_contacts' => $recentContacts,
            ];

            return view('admin.dashboard', compact('metrics'));

        } catch (\Exception $e) {
            // Log error details for debugging
            Log::error('Dashboard loading failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id()
            ]);

            // Return simplified view with error message
            return view('admin.dashboard', [
                'metrics' => [
                    'total_students' => 0,
                    'total_rooms' => 0,
                    'room_occupancy' => 0,
                    'available_rooms' => 0,
                    'occupied_rooms' => 0,
                    'maintenance_rooms' => 0,
                    'recent_students' => collect(),
                    'recent_contacts' => collect(),
                ],
                'error' => 'Dashboard data could not be loaded. Please try again later.'
            ]);
        }
    }
}
