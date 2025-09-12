<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\Room;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\MealMenu;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin')->only(['reports', 'statistics']);
    }

    /**
     * Display role-specific dashboard
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('owner')) {
            return $this->ownerDashboard();
        } elseif ($user->hasRole('student')) {
            return $this->studentDashboard();
        }

        abort(403, 'अनधिकृत पहुँच');
    }

    /**
     * Admin dashboard with system-wide metrics
     */
    private function adminDashboard()
    {
        try {
            // Fetch core metrics with optimized queries
            $totalStudents = Student::count();
            $totalRooms = Room::count();
            $totalHostels = Hostel::count();
            $totalContacts = Contact::count();

            // Batch room status queries for efficiency
            $roomStatus = Room::selectRaw('
                COUNT(CASE WHEN status = "available" THEN 1 END) as available,
                COUNT(CASE WHEN status = "occupied" THEN 1 END) as occupied,
                COUNT(CASE WHEN status = "reserved" THEN 1 END) as reserved,
                COUNT(CASE WHEN status = "maintenance" THEN 1 END) as maintenance
            ')->first();

            // Calculate occupancy rate safely
            $roomOccupancy = $totalRooms > 0
                ? round(($roomStatus->occupied / $totalRooms) * 100, 1)
                : 0;

            // Calculate reservation rate
            $roomReservation = $totalRooms > 0
                ? round(($roomStatus->reserved / $totalRooms) * 100, 1)
                : 0;

            // Get recent records with proper pagination
            $recentStudents = Student::with('room.hostel')
                ->latest('created_at')
                ->paginate(5);

            $recentContacts = Contact::latest('created_at')
                ->paginate(5);

            $recentHostels = Hostel::withCount('rooms')
                ->latest('created_at')
                ->paginate(5);

            // Prepare metrics array for the view
            $metrics = [
                'total_students' => $totalStudents,
                'total_rooms' => $totalRooms,
                'total_hostels' => $totalHostels,
                'total_contacts' => $totalContacts,
                'room_occupancy' => $roomOccupancy,
                'room_reservation' => $roomReservation,
                'available_rooms' => $roomStatus->available,
                'occupied_rooms' => $roomStatus->occupied,
                'reserved_rooms' => $roomStatus->reserved,
                'maintenance_rooms' => $roomStatus->maintenance,
                'recent_students' => $recentStudents,
                'recent_contacts' => $recentContacts,
                'recent_hostels' => $recentHostels,
            ];

            return view('admin.dashboard', compact('metrics'));
        } catch (\Exception $e) {
            // Log error details for debugging
            Log::error('ड्यासबोर्ड लोड गर्न असफल: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return simplified view with error message
            return view('admin.dashboard', [
                'metrics' => [
                    'total_students' => 0,
                    'total_rooms' => 0,
                    'total_hostels' => 0,
                    'total_contacts' => 0,
                    'room_occupancy' => 0,
                    'room_reservation' => 0,
                    'available_rooms' => 0,
                    'occupied_rooms' => 0,
                    'reserved_rooms' => 0,
                    'maintenance_rooms' => 0,
                    'recent_students' => collect(),
                    'recent_contacts' => collect(),
                    'recent_hostels' => collect(),
                ],
                'error' => 'ड्यासबोर्ड डाटा लोड गर्न सकिएन। कृपया पछि प्रयास गर्नुहोस् वा समर्थन सम्पर्क गर्नुहोस्।'
            ]);
        }
    }

    /**
     * Owner dashboard with hostel-specific metrics
     */
    private function ownerDashboard()
    {
        try {
            // होस्टेल मालिकको आफ्नै होस्टेलको डाटा मात्र
            $hostel = Hostel::where('id', auth()->user()->hostel_id)->first();

            if (!$hostel) {
                return view('owner.dashboard.index')->with('error', 'तपाईंको होस्टेल फेला परेन');
            }

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
        } catch (\Exception $e) {
            Log::error('होस्टेल मालिक ड्यासबोर्ड त्रुटि: ' . $e->getMessage());
            return view('owner.dashboard.index')->with('error', 'डाटा लोड गर्न असफल भयो');
        }
    }

    /**
     * Student dashboard
     */
    private function studentDashboard()
    {
        try {
            $student = auth()->user()->student;
            $room = $student->room;
            $hostel = $room->hostel;
            $mealMenu = MealMenu::where('hostel_id', $hostel->id)
                ->where('day', now()->format('l'))
                ->first();

            return view('student.dashboard', compact('student', 'room', 'hostel', 'mealMenu'));
        } catch (\Exception $e) {
            Log::error('विद्यार्थी ड्यासबोर्ड त्रुटि: ' . $e->getMessage());
            return view('student.dashboard')->with('error', 'डाटा लोड गर्न असफल भयो');
        }
    }

    /**
     * Admin reports page
     */
    public function reports()
    {
        try {
            $reportData = [
                'student_registrations' => Student::count(),
                'room_occupancy' => Room::where('status', 'occupied')->count(),
                'total_rooms' => Room::count(),
                'revenue' => Payment::sum('amount'),
                'monthly_revenue' => Payment::whereMonth('created_at', now()->month)->sum('amount'),
                'available_rooms' => Room::where('status', 'available')->count(),
                'recent_payments' => Payment::with('student.user')
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function ($payment) {
                        return [
                            'student_name' => $payment->student->user->name,
                            'date' => $payment->created_at->format('Y-m-d'),
                            'amount' => $payment->amount,
                            'method' => $payment->payment_method,
                            'status' => $payment->status
                        ];
                    })
            ];

            return view('admin.reports.index', compact('reportData'));
        } catch (\Exception $e) {
            Log::error('प्रतिवेदन लोड गर्न असफल: ' . $e->getMessage());
            return redirect()->back()->with('error', 'प्रतिवेदन लोड गर्न असफल भयो');
        }
    }

    /**
     * Get dashboard statistics for AJAX requests (Admin only)
     */
    public function statistics(Request $request)
    {
        try {
            // Get date range from request or use default
            $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));

            // Fetch statistics within date range
            $newStudents = Student::whereBetween('created_at', [$startDate, $endDate])->count();
            $newRooms = Room::whereBetween('created_at', [$startDate, $endDate])->count();
            $newHostels = Hostel::whereBetween('created_at', [$startDate, $endDate])->count();

            // Room occupancy trend data
            $occupancyTrend = Room::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(CASE WHEN status = "occupied" THEN 1 END) as occupied_count'),
                DB::raw('COUNT(*) as total_count')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'occupancy_rate' => $item->total_count > 0
                            ? round(($item->occupied_count / $item->total_count) * 100, 1)
                            : 0
                    ];
                });

            // Student registration trend
            $studentTrend = Student::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'new_students' => $newStudents,
                    'new_rooms' => $newRooms,
                    'new_hostels' => $newHostels,
                    'occupancy_trend' => $occupancyTrend,
                    'student_trend' => $studentTrend,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('ड्यासबोर्ड तथ्याङ्क API असफल: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'तथ्याङ्क डाटा लोड गर्न असफल भयो'
            ], 500);
        }
    }
}
