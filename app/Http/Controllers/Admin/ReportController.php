<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Room;
use App\Models\Payment;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * प्रतिवेदन ड्यासबोर्ड देखाउनुहोस्
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        try {
            // ✅ FIXED: Authorization check
            if (!auth()->user()->hasRole('admin')) {
                abort(403, 'तपाईंसँग यो प्रतिवेदन हेर्ने अनुमति छैन');
            }

            $reportData = $this->prepareReportData();
            return view('admin.reports.index', compact('reportData'));
        } catch (\Exception $e) {
            Log::error('प्रतिवेदन लोड गर्दा त्रुटि: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->with('error', 'प्रतिवेदन डाटा लोड गर्न असफल भयो। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    /**
     * प्रतिवेदन डाटा तयार गर्ने सहायक method
     */
    private function prepareReportData()
    {
        // ✅ FIXED: Use parameter binding for raw queries
        $roomStats = Room::selectRaw('
            COUNT(*) as total_rooms,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as occupied_rooms,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as available_rooms
        ', ['occupied', 'available'])->first();

        // ✅ FIXED: Use parameter binding for payment queries
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $revenueData = Payment::where('status', 'success')
            ->selectRaw('
                SUM(amount) as total_revenue,
                SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN amount ELSE 0 END) as monthly_revenue
            ', [$currentMonth, $currentYear])
            ->first();

        // ✅ FIXED: Secure student relationship loading
        $recentPayments = Payment::with(['student.user'])
            ->where('status', 'success')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'student_name' => $payment->student->user->name ?? 'अज्ञात',
                    'date' => $payment->created_at->format('Y-m-d'),
                    'amount' => $payment->amount,
                    'method' => $payment->payment_method,
                    'status' => $payment->status
                ];
            });

        return [
            'student_registrations' => Student::count(),
            'room_occupancy' => $roomStats->occupied_rooms ?? 0,
            'total_rooms' => $roomStats->total_rooms ?? 0,
            'revenue' => $revenueData->total_revenue ?? 0,
            'monthly_revenue' => $revenueData->monthly_revenue ?? 0,
            'available_rooms' => $roomStats->available_rooms ?? 0,
            'total_hostels' => Hostel::count(),
            'occupied_percentage' => ($roomStats->total_rooms ?? 0) > 0 ?
                round((($roomStats->occupied_rooms ?? 0) / ($roomStats->total_rooms ?? 1)) * 100, 2) : 0,
            'available_percentage' => ($roomStats->total_rooms ?? 0) > 0 ?
                round((($roomStats->available_rooms ?? 0) / ($roomStats->total_rooms ?? 1)) * 100, 2) : 0,
            'recent_payments' => $recentPayments
        ];
    }

    /**
     * मासिक प्रतिवेदन तयार गर्नुहोस्
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function monthlyReport(Request $request)
    {
        try {
            // ✅ FIXED: Enhanced input validation
            $validated = $request->validate([
                'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
                'month' => 'required|integer|min:1|max:12'
            ]);

            $year = $validated['year'];
            $month = $validated['month'];

            // ✅ FIXED: Use parameter binding for all raw queries
            $paymentStats = Payment::where('status', 'success')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->selectRaw('
                    SUM(amount) as total_revenue,
                    AVG(amount) as average_payment,
                    COUNT(*) as payments_count,
                    payment_method
                ')
                ->groupBy('payment_method')
                ->get();

            $students = Student::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $rooms = Room::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            // ✅ FIXED: Secure daily revenue query
            $dailyRevenue = Payment::where('status', 'success')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('revenue', 'date');

            $data = [
                'total_revenue' => $paymentStats->sum('total_revenue') ?? 0,
                'total_students' => $students,
                'total_rooms_added' => $rooms,
                'average_payment' => $paymentStats->avg('average_payment') ?? 0,
                'payment_methods' => $paymentStats->pluck('payments_count', 'payment_method'),
                'daily_revenue' => $dailyRevenue
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'period' => [
                    'year' => $year,
                    'month' => $month,
                    'month_name' => Carbon::create()->month($month)->format('F')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('मासिक प्रतिवेदन त्रुटि: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'मासिक प्रतिवेदन तयार गर्न असफल भयो'
            ], 500);
        }
    }

    /**
     * वार्षिक प्रतिवेदन तयार गर्नुहोस्
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function yearlyReport(Request $request)
    {
        try {
            // ✅ FIXED: Enhanced input validation
            $validated = $request->validate([
                'year' => 'required|integer|min:2020|max:' . (date('Y') + 1)
            ]);

            $year = $validated['year'];

            // ✅ FIXED: Secure payment statistics query
            $paymentStats = Payment::where('status', 'success')
                ->whereYear('created_at', $year)
                ->selectRaw('
                    SUM(amount) as total_revenue,
                    AVG(amount) as average_payment,
                    COUNT(*) as payments_count,
                    MONTH(created_at) as month
                ')
                ->groupBy('month')
                ->get()
                ->keyBy('month');

            // ✅ FIXED: Secure student and room counts queries
            $monthlyStudents = Student::whereYear('created_at', $year)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->get()
                ->keyBy('month');

            $monthlyRooms = Room::whereYear('created_at', $year)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->get()
                ->keyBy('month');

            $monthlyData = [];
            for ($month = 1; $month <= 12; $month++) {
                $paymentStat = $paymentStats->get($month);
                $studentStat = $monthlyStudents->get($month);
                $roomStat = $monthlyRooms->get($month);

                $monthlyData[$month] = [
                    'revenue' => $paymentStat->total_revenue ?? 0,
                    'students' => $studentStat->count ?? 0,
                    'payments_count' => $paymentStat->payments_count ?? 0,
                    'rooms_added' => $roomStat->count ?? 0
                ];
            }

            $data = [
                'total_revenue' => $paymentStats->sum('total_revenue') ?? 0,
                'total_students' => Student::whereYear('created_at', $year)->count(),
                'total_rooms_added' => Room::whereYear('created_at', $year)->count(),
                'average_payment' => $paymentStats->avg('average_payment') ?? 0,
                'monthly_data' => $monthlyData
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'year' => $year
            ]);
        } catch (\Exception $e) {
            Log::error('वार्षिक प्रतिवेदन त्रुटि: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'वार्षिक प्रतिवेदन तयार गर्न असफल भयो'
            ], 500);
        }
    }

    /**
     * अनुकूलित मिति दायरा प्रतिवेदन
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customDateReport(Request $request)
    {
        try {
            // ✅ FIXED: Enhanced input validation with date formatting
            $validated = $request->validate([
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            $startDate = $validated['start_date'];
            $endDate = $validated['end_date'];

            // ✅ FIXED: Secure date range queries
            $paymentStats = Payment::where('status', 'success')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->selectRaw('
                    SUM(amount) as total_revenue,
                    AVG(amount) as average_payment,
                    COUNT(*) as payments_count,
                    payment_method
                ')
                ->groupBy('payment_method')
                ->get();

            $students = Student::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();
            $rooms = Room::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();

            // ✅ FIXED: Secure daily revenue query
            $dailyRevenue = Payment::where('status', 'success')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('revenue', 'date');

            $data = [
                'total_revenue' => $paymentStats->sum('total_revenue') ?? 0,
                'total_students' => $students,
                'total_rooms_added' => $rooms,
                'average_payment' => $paymentStats->avg('average_payment') ?? 0,
                'payment_methods' => $paymentStats->pluck('payments_count', 'payment_method'),
                'daily_revenue' => $dailyRevenue
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('अनुकूलित प्रतिवेदन त्रुटि: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'अनुकूलित प्रतिवेदन तयार गर्न असफल भयो'
            ], 500);
        }
    }

    /**
     * PDF प्रतिवेदन डाउनलोड गर्नुहोस्
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Request $request)
    {
        try {
            // ✅ FIXED: Enhanced validation with proper date constraints
            $validated = $request->validate([
                'type' => 'sometimes|in:summary,monthly,yearly,custom',
                'year' => 'required_if:type,monthly,yearly|integer|min:2020|max:' . (date('Y') + 1),
                'month' => 'required_if:type,monthly|integer|min:1|max:12',
                'start_date' => 'required_if:type,custom|date',
                'end_date' => 'required_if:type,custom|date|after_or_equal:start_date'
            ]);

            $type = $validated['type'] ?? 'summary';
            $dateRange = [];
            $data = [];

            switch ($type) {
                case 'monthly':
                    $year = $validated['year'] ?? now()->year;
                    $month = $validated['month'] ?? now()->month;

                    // ✅ FIXED: Use validated data
                    $result = $this->monthlyReportData($year, $month);
                    $data = $result['data'];
                    $dateRange = [
                        'type' => 'monthly',
                        'period' => Carbon::create()->month($month)->format('F') . ' ' . $year
                    ];
                    break;

                case 'yearly':
                    $year = $validated['year'] ?? now()->year;

                    // ✅ FIXED: Use validated data
                    $result = $this->yearlyReportData($year);
                    $data = $result['data'];
                    $dateRange = [
                        'type' => 'yearly',
                        'period' => $year
                    ];
                    break;

                case 'custom':
                    $startDate = $validated['start_date'];
                    $endDate = $validated['end_date'];

                    // ✅ FIXED: Use validated data
                    $result = $this->customDateReportData($startDate, $endDate);
                    $data = $result['data'];
                    $dateRange = [
                        'type' => 'custom',
                        'period' => $startDate . ' to ' . $endDate
                    ];
                    break;

                default:
                    $data = $this->prepareReportData();
                    $dateRange = [
                        'type' => 'summary',
                        'period' => 'सामान्य सारांश'
                    ];
                    break;
            }

            $pdf = PDF::loadView('admin.reports.pdf', [
                'data' => $data,
                'dateRange' => $dateRange,
                'generatedAt' => now()->format('Y-m-d H:i:s')
            ])->setPaper('a4', 'landscape');

            // ✅ FIXED: Secure filename generation
            $fileName = 'hostel-report-' . $type . '-' . time() . '.pdf';
            $safeFileName = preg_replace('/[^a-zA-Z0-9\-_.]/', '', $fileName);

            return $pdf->download($safeFileName);
        } catch (\Exception $e) {
            Log::error('PDF डाउनलोड त्रुटि: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'PDF डाउनलोड गर्न असफल भयो');
        }
    }

    /**
     * एक्सेल प्रतिवेदन डाउनलोड गर्नुहोस्
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function downloadExcel(Request $request)
    {
        try {
            // ✅ FIXED: Enhanced validation
            $validated = $request->validate([
                'type' => 'sometimes|in:summary,monthly,yearly,custom',
                'year' => 'required_if:type,monthly,yearly|integer|min:2020|max:' . (date('Y') + 1),
                'month' => 'required_if:type,monthly|integer|min:1|max:12',
                'start_date' => 'required_if:type,custom|date',
                'end_date' => 'required_if:type,custom|date|after_or_equal:start_date'
            ]);

            $type = $validated['type'] ?? 'summary';
            $filters = [];

            switch ($type) {
                case 'monthly':
                    $filters = [
                        'year' => $validated['year'] ?? now()->year,
                        'month' => $validated['month'] ?? now()->month
                    ];
                    break;

                case 'yearly':
                    $filters = [
                        'year' => $validated['year'] ?? now()->year
                    ];
                    break;

                case 'custom':
                    $filters = [
                        'start_date' => $validated['start_date'],
                        'end_date' => $validated['end_date']
                    ];
                    break;
            }

            // ✅ FIXED: Secure filename generation
            $fileName = 'hostel-report-' . $type . '-' . time() . '.xlsx';
            $safeFileName = preg_replace('/[^a-zA-Z0-9\-_.]/', '', $fileName);

            return Excel::download(new ReportsExport($type, $filters), $safeFileName);
        } catch (\Exception $e) {
            Log::error('एक्सेल डाउनलोड त्रुटि: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'एक्सेल डाउनलोड गर्न असफल भयो');
        }
    }

    /**
     * Helper method for monthly report data
     */
    private function monthlyReportData($year, $month)
    {
        // ✅ FIXED: Secure queries with parameter binding
        $paymentStats = Payment::where('status', 'success')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('
                SUM(amount) as total_revenue,
                AVG(amount) as average_payment,
                COUNT(*) as payments_count,
                payment_method
            ')
            ->groupBy('payment_method')
            ->get();

        $students = Student::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $rooms = Room::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $dailyRevenue = Payment::where('status', 'success')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('revenue', 'date');

        return [
            'data' => [
                'total_revenue' => $paymentStats->sum('total_revenue') ?? 0,
                'total_students' => $students,
                'total_rooms_added' => $rooms,
                'average_payment' => $paymentStats->avg('average_payment') ?? 0,
                'payment_methods' => $paymentStats->pluck('payments_count', 'payment_method'),
                'daily_revenue' => $dailyRevenue
            ]
        ];
    }

    /**
     * Helper method for yearly report data
     */
    private function yearlyReportData($year)
    {
        // ✅ FIXED: Secure queries
        $paymentStats = Payment::where('status', 'success')
            ->whereYear('created_at', $year)
            ->selectRaw('
                SUM(amount) as total_revenue,
                AVG(amount) as average_payment,
                COUNT(*) as payments_count,
                MONTH(created_at) as month
            ')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $monthlyStudents = Student::whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $paymentStat = $paymentStats->get($month);
            $studentStat = $monthlyStudents->get($month);

            $monthlyData[$month] = [
                'revenue' => $paymentStat->total_revenue ?? 0,
                'students' => $studentStat->count ?? 0,
                'payments_count' => $paymentStat->payments_count ?? 0
            ];
        }

        return [
            'data' => [
                'total_revenue' => $paymentStats->sum('total_revenue') ?? 0,
                'total_students' => Student::whereYear('created_at', $year)->count(),
                'total_rooms_added' => Room::whereYear('created_at', $year)->count(),
                'average_payment' => $paymentStats->avg('average_payment') ?? 0,
                'monthly_data' => $monthlyData
            ]
        ];
    }

    /**
     * Helper method for custom date report data
     */
    private function customDateReportData($startDate, $endDate)
    {
        // ✅ FIXED: Secure date range queries
        $paymentStats = Payment::where('status', 'success')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('
                SUM(amount) as total_revenue,
                AVG(amount) as average_payment,
                COUNT(*) as payments_count,
                payment_method
            ')
            ->groupBy('payment_method')
            ->get();

        $students = Student::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();
        $rooms = Room::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();

        $dailyRevenue = Payment::where('status', 'success')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('revenue', 'date');

        return [
            'data' => [
                'total_revenue' => $paymentStats->sum('total_revenue') ?? 0,
                'total_students' => $students,
                'total_rooms_added' => $rooms,
                'average_payment' => $paymentStats->avg('average_payment') ?? 0,
                'payment_methods' => $paymentStats->pluck('payments_count', 'payment_method'),
                'daily_revenue' => $dailyRevenue
            ]
        ];
    }
}
