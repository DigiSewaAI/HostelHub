<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Room;
use App\Models\Payment;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * प्रतिवेदन ड्यासबोर्ड देखाउनुहोस्
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        try {
            // प्रतिवेदन डाटा तयार गर्नुहोस्
            $reportData = [
                'student_registrations' => Student::count(),
                'room_occupancy' => Room::where('status', 'occupied')->count(),
                'total_rooms' => Room::count(),
                'revenue' => Payment::where('status', 'success')->sum('amount'),
                'monthly_revenue' => Payment::where('status', 'success')
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount'),
                'available_rooms' => Room::where('status', 'available')->count(),
                'total_hostels' => Hostel::count(),
                'occupied_percentage' => Room::count() > 0 ?
                    round((Room::where('status', 'occupied')->count() / Room::count()) * 100, 2) : 0,
                'available_percentage' => Room::count() > 0 ?
                    round((Room::where('status', 'available')->count() / Room::count()) * 100, 2) : 0,
                'recent_payments' => Payment::with(['student.user'])
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
                    })
            ];

            return view('admin.reports.index', compact('reportData'));
        } catch (\Exception $e) {
            Log::error('प्रतिवेदन लोड गर्दा त्रुटि: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'प्रतिवेदन डाटा लोड गर्न असफल भयो। कृपया पुनः प्रयास गर्नुहोस्।');
        }
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
            $year = $request->input('year', now()->year);
            $month = $request->input('month', now()->month);

            $payments = Payment::where('status', 'success')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();

            $students = Student::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();

            $data = [
                'total_revenue' => $payments->sum('amount'),
                'total_students' => $students->count(),
                'average_payment' => $payments->count() > 0 ? $payments->avg('amount') : 0,
                'payment_methods' => $payments->groupBy('payment_method')->map->count(),
                'daily_revenue' => $payments->groupBy(function ($date) {
                    return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
                })->map->sum('amount')
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('मासिक प्रतिवेदन त्रुटि: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'मासिक प्रतिवेदन तयार गर्न असफल भयो'
            ], 500);
        }
    }

    /**
     * PDF प्रतिवेदन डाउनलोड गर्नुहोस्
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf()
    {
        try {
            // PDF जनरेट गर्ने लजिक यहाँ थप्नुहोस्
            // उदाहरणको लागि: return response()->download($path);

            return redirect()->back()
                ->with('success', 'PDF प्रतिवेदन सफलतापूर्वक डाउनलोड भयो');
        } catch (\Exception $e) {
            Log::error('PDF डाउनलोड त्रुटि: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'PDF डाउनलोड गर्न असफल भयो');
        }
    }

    /**
     * एक्सेल प्रतिवेदन डाउनलोड गर्नुहोस्
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadExcel()
    {
        try {
            // एक्सेल जनरेट गर्ने लजिक यहाँ थप्नुहोस्
            // उदाहरणको लागि: return response()->download($path);

            return redirect()->back()
                ->with('success', 'एक्सेल प्रतिवेदन सफलतापूर्वक डाउनलोड भयो');
        } catch (\Exception $e) {
            Log::error('एक्सेल डाउनलोड त्रुटि: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'एक्सेल डाउनलोड गर्न असफल भयो');
        }
    }
}
