<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\Room;
use App\Models\Student;
use App\Models\Gallery;
use App\Models\Hostel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
            Log::error('Dashboard loading failed: ' . $e->getMessage(), [
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
                'error' => 'Dashboard data could not be loaded. Please try again later or contact support.'
            ]);
        }
    }

    // DashboardController को reports method मा यसरी data तयार गर्नुहोस्
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
            Log::error('Reports loading failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'प्रतिवेदन लोड गर्न असफल भयो');
        }
    }

    /**
     * Get dashboard statistics for AJAX requests
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
            Log::error('Dashboard statistics API failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics data'
            ], 500);
        }
    }

    /**
     * Get dashboard settings
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function settings()
    {
        $settings = [
            'app_name' => config('app.name', 'HostelHub'),
            'timezone' => config('app.timezone', 'Asia/Kathmandu'),
            'date_format' => config('app.date_format', 'd/m/Y'),
            'currency' => config('app.currency', 'NPR'),
            'default_language' => config('app.locale', 'ne'),
            'max_upload_size' => ini_get('upload_max_filesize'),
            'payment_gateway' => config('services.payment_gateway', 'cash'),
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update dashboard settings
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'currency' => 'required|string',
            'default_language' => 'required|string',
        ]);

        try {
            // In a real application, you would update the .env file or a settings table
            // For demonstration, we'll just return a success message
            return redirect()->route('admin.dashboard')
                ->with('success', 'सेटिङ्स सफलतापूर्वक अपडेट गरियो!');
        } catch (\Exception $e) {
            Log::error('Settings update failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'data' => $validated
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'सेटिङ्स अपडेट गर्न असफल भयो। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }
}
