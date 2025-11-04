<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Booking;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use PDF;

class PaymentController extends Controller
{
    /**
     * Get data based on user role
     */
    private function getDataByRole()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return Payment::with(['student', 'hostel', 'room'])->latest();
        } elseif ($user->hasRole('hostel_manager')) {
            $hostelIds = Hostel::where('owner_id', $user->id)
                ->orWhere('manager_id', $user->id)
                ->pluck('id')
                ->toArray();

            return Payment::whereIn('hostel_id', $hostelIds)
                ->with(['student', 'hostel', 'room'])
                ->latest();
        } elseif ($user->hasRole('student')) {
            return Payment::where('student_id', $user->id)
                ->with(['hostel', 'room'])
                ->latest();
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Check if user has permission to access a specific payment
     */
    private function checkPaymentPermission(Payment $payment)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return true;
        } elseif ($user->hasRole('hostel_manager')) {
            $hostelIds = Hostel::where('owner_id', $user->id)
                ->orWhere('manager_id', $user->id)
                ->pluck('id')
                ->toArray();

            return in_array($payment->hostel_id, $hostelIds);
        } elseif ($user->hasRole('student') && $payment->student_id == $user->id) {
            return true;
        }

        abort(403, 'तपाईंसँग यो भुक्तानी हेर्ने अनुमति छैन');
    }

    /**
     * Show payment report for owner's hostels
     */
    public function ownerReport(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('hostel_manager')) {
            abort(403, 'तपाईंसँग यो रिपोर्ट हेर्ने अनुमति छैन');
        }

        // Get current user's hostel IDs
        $hostelIds = Hostel::where('owner_id', $user->id)
            ->orWhere('manager_id', $user->id)
            ->pluck('id')
            ->toArray();

        // Get payments for owner's hostels with date filter
        $paymentsQuery = Payment::whereIn('hostel_id', $hostelIds)
            ->with(['student', 'hostel']);

        // Apply date filter if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $paymentsQuery->whereBetween('payment_date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $payments = $paymentsQuery->latest()->paginate(10);

        // Statistics for owner's hostels
        $totalRevenue = Payment::whereIn('hostel_id', $hostelIds)
            ->where('status', 'completed')
            ->sum('amount');

        $pendingTransfers = Payment::whereIn('hostel_id', $hostelIds)
            ->where('payment_method', 'bank_transfer')
            ->where('status', 'pending')
            ->count();

        $currentMonthRevenue = Payment::whereIn('hostel_id', $hostelIds)
            ->where('status', 'completed')
            ->whereYear('payment_date', now()->year)
            ->whereMonth('payment_date', now()->month)
            ->sum('amount');

        $totalPaymentsCount = Payment::whereIn('hostel_id', $hostelIds)->count();
        $averagePayment = $totalPaymentsCount > 0 ? $totalRevenue / $totalPaymentsCount : 0;

        // Completed payments count
        $completedPayments = Payment::whereIn('hostel_id', $hostelIds)
            ->where('status', 'completed')
            ->count();

        // Payment methods breakdown
        $paymentMethods = Payment::whereIn('hostel_id', $hostelIds)
            ->select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method')
            ->toArray();

        return view('owner.payments.report', compact(
            'payments',
            'totalRevenue',
            'pendingTransfers',
            'currentMonthRevenue',
            'averagePayment',
            'paymentMethods',
            'completedPayments',
            'totalPaymentsCount'
        ));
    }

    /**
     * Create manual cash payment for owner
     */
    public function createManualPayment(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('hostel_manager')) {
            abort(403, 'तपाईंसँग म्यानुअल भुक्तानी थप्ने अनुमति छैन');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:1',
            'paid_at' => 'required|date',
        ]);

        // Get current user's hostel IDs
        $hostelIds = Hostel::where('owner_id', $user->id)
            ->orWhere('manager_id', $user->id)
            ->pluck('id')
            ->toArray();

        // Verify the student belongs to owner's hostels
        $student = Student::where('id', $request->student_id)
            ->whereHas('room', function ($query) use ($hostelIds) {
                $query->whereIn('hostel_id', $hostelIds);
            })->first();

        if (!$student) {
            return back()->with('error', 'तपाईंसँग यो विद्यार्थीको लागि भुक्तानी थप्ने अनुमति छैन');
        }

        try {
            DB::beginTransaction();

            // Get hostel_id from student's room
            $hostel_id = $student->room->hostel_id;

            Payment::create([
                'student_id' => $request->student_id,
                'hostel_id' => $hostel_id,
                'amount' => $request->amount,
                'payment_method' => 'cash',
                'payment_date' => $request->paid_at,
                'status' => 'completed',
                'verified_by' => $user->id,
                'verified_at' => now(),
                'created_by' => $user->id,
            ]);

            DB::commit();

            return redirect()->route('owner.payments.report')->with('success', 'म्यानुअल भुक्तानी सफलतापूर्वक दर्ता गरियो!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Manual payment creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'म्यानुअल भुक्तानी दर्ता गर्न असफल। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $payments = $this->getDataByRole()->paginate(10);

        // Return appropriate view based on role
        if (Auth::user()->hasRole('admin')) {
            return view('admin.payments.index', compact('payments'));
        } elseif (Auth::user()->hasRole('hostel_manager')) {
            return view('owner.payments.index', compact('payments'));
        } elseif (Auth::user()->hasRole('student')) {
            return view('student.payments.index', compact('payments'));
        }
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        // Only admin and owner can create payments
        if (!Auth::user()->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग भुक्तानी थप्ने अनुमति छैन');
        }

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $students = Student::where('status', 'active')->get();
            $hostels = Hostel::all();
            return view('admin.payments.create', compact('students', 'hostels'));
        } elseif ($user->hasRole('hostel_manager')) {
            $hostelIds = Hostel::where('owner_id', $user->id)
                ->orWhere('manager_id', $user->id)
                ->pluck('id')
                ->toArray();

            $students = Student::whereHas('room', function ($query) use ($hostelIds) {
                $query->whereIn('hostel_id', $hostelIds);
            })->where('status', 'active')->get();

            return view('owner.payments.create', compact('students'));
        }
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        // Only admin and owner can create payments
        if (!Auth::user()->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग भुक्तानी थप्ने अनुमति छैन');
        }

        $user = Auth::user();
        $validationRules = [
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'status' => 'required|in:pending,completed,failed',
            'remarks' => 'nullable|string|max:500',
        ];

        // Add hostel_id validation for admin
        if ($user->hasRole('admin')) {
            $validationRules['hostel_id'] = 'required|exists:hostels,id';
        }

        $request->validate($validationRules);

        // For owners, ensure student belongs to their hostels
        if ($user->hasRole('hostel_manager')) {
            $hostelIds = Hostel::where('owner_id', $user->id)
                ->orWhere('manager_id', $user->id)
                ->pluck('id')
                ->toArray();

            $student = Student::where('id', $request->student_id)
                ->whereHas('room', function ($query) use ($hostelIds) {
                    $query->whereIn('hostel_id', $hostelIds);
                })->first();

            if (!$student) {
                return back()
                    ->withInput()
                    ->with('error', 'तपाईंसँग यो विद्यार्थीको लागि भुक्तानी थप्ने अनुमति छैन');
            }
        }

        try {
            DB::beginTransaction();

            // Get student and auto-detect room_id
            $student = Student::find($request->student_id);
            $roomId = $student && $student->room ? $student->room->id : null;

            // Set due_date to payment_date if not provided
            $dueDate = $request->due_date ?? $request->payment_date;

            $paymentData = [
                'student_id' => $request->student_id,
                'room_id' => $roomId,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'due_date' => $dueDate,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'remarks' => $request->notes,
                'created_by' => $user->id,
            ];

            // Set hostel_id based on role
            if ($user->hasRole('admin')) {
                $paymentData['hostel_id'] = $request->hostel_id;
            } elseif ($user->hasRole('hostel_manager')) {
                if ($student && $student->room) {
                    $paymentData['hostel_id'] = $student->room->hostel_id;
                } else {
                    $paymentData['hostel_id'] = $user->hostel_id ?? null;
                }
            }

            Payment::create($paymentData);

            DB::commit();

            // Redirect back to create form with success message
            if ($user->hasRole('admin')) {
                return redirect()
                    ->route('admin.payments.create')
                    ->with('success', 'भुक्तानी सफलतापूर्वक थपियो! नयाँ भुक्तानी थप्नुहोस्।');
            } else {
                return redirect()
                    ->route('owner.payments.create')
                    ->with('success', 'भुक्तानी सफलतापूर्वक थपियो! नयाँ भुक्तानी थप्नुहोस्।');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'भुक्तानी दर्ता गर्न असफल। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        // Load relationships safely
        $payment->load(['student', 'hostel', 'room']);

        // Load user relationships safely with error handling
        try {
            if ($payment->created_by) {
                $payment->load(['createdBy']);
            }
            if ($payment->updated_by) {
                $payment->load(['updatedBy']);
            }
        } catch (\Exception $e) {
            // Log error but don't break the page
            Log::warning('Error loading user relationships: ' . $e->getMessage());
        }

        // Return appropriate view based on role
        if (Auth::user()->hasRole('admin')) {
            return view('admin.payments.show', compact('payment'));
        } elseif (Auth::user()->hasRole('hostel_manager')) {
            return view('owner.payments.show', compact('payment'));
        } elseif (Auth::user()->hasRole('student')) {
            return view('student.payments.show', compact('payment'));
        }
    }

    /**
     * Search payments
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $payments = $this->getDataByRole()
            ->where(function ($query) use ($search) {
                $query->where('amount', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->paginate(10);

        // Return appropriate view based on role
        if (Auth::user()->hasRole('admin')) {
            return view('admin.payments.index', compact('payments'));
        } elseif (Auth::user()->hasRole('hostel_manager')) {
            return view('owner.payments.index', compact('payments'));
        } elseif (Auth::user()->hasRole('student')) {
            return view('student.payments.index', compact('payments'));
        }
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        // Students can't edit payments
        if (Auth::user()->hasRole('student')) {
            abort(403, 'तपाईंसँग भुक्तानी सम्पादन गर्ने अनुमति छैन');
        }

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $students = Student::where('status', 'active')->get();
            $hostels = Hostel::all();
            return view('admin.payments.edit', compact('payment', 'students', 'hostels'));
        } elseif ($user->hasRole('hostel_manager')) {
            $hostelIds = Hostel::where('owner_id', $user->id)
                ->orWhere('manager_id', $user->id)
                ->pluck('id')
                ->toArray();

            $students = Student::whereHas('room', function ($query) use ($hostelIds) {
                $query->whereIn('hostel_id', $hostelIds);
            })->where('status', 'active')->get();

            return view('owner.payments.edit', compact('payment', 'students'));
        }
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        // Students can't update payments
        if (Auth::user()->hasRole('student')) {
            abort(403, 'तपाईंसँग भुक्तानी अद्यावधिक गर्ने अनुमति छैन');
        }

        $user = Auth::user();
        $validationRules = [
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'status' => 'required|in:pending,completed,failed',
            'remarks' => 'nullable|string|max:500',
        ];

        // Add hostel_id validation for admin
        if ($user->hasRole('admin')) {
            $validationRules['hostel_id'] = 'required|exists:hostels,id';
        }

        $request->validate($validationRules);

        // For owners, ensure student belongs to their hostels
        if ($user->hasRole('hostel_manager')) {
            $hostelIds = Hostel::where('owner_id', $user->id)
                ->orWhere('manager_id', $user->id)
                ->pluck('id')
                ->toArray();

            $student = Student::where('id', $request->student_id)
                ->whereHas('room', function ($query) use ($hostelIds) {
                    $query->whereIn('hostel_id', $hostelIds);
                })->first();

            if (!$student) {
                return back()
                    ->withInput()
                    ->with('error', 'तपाईंसँग यो विद्यार्थीको लागि भुक्तानी सम्पादन गर्ने अनुमति छैन');
            }
        }

        try {
            DB::beginTransaction();

            // Get student and auto-detect room_id
            $student = Student::find($request->student_id);
            $roomId = $student && $student->room ? $student->room->id : null;

            // Set due_date to payment_date if not provided
            $dueDate = $request->due_date ?? $request->payment_date;

            $paymentData = [
                'student_id' => $request->student_id,
                'room_id' => $roomId,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'due_date' => $dueDate,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'remarks' => $request->notes,
                'updated_by' => $user->id,
            ];

            // Set hostel_id based on role
            if ($user->hasRole('admin')) {
                $paymentData['hostel_id'] = $request->hostel_id;
            }

            $payment->update($paymentData);

            DB::commit();

            $route = $user->hasRole('admin') ? 'admin.payments.index' : 'owner.payments.index';
            return redirect()
                ->route($route)
                ->with('success', 'भुक्तानी सफलतापूर्वक अद्यावधिक गरियो!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment update failed: ' . $e->getMessage());
            Log::error('Stack trace: ...' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'भुक्तानी अद्यावधिक गर्न असफल। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Update payment status
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        $request->validate([
            'status' => 'required|in:pending,completed,failed'
        ]);

        try {
            $payment->update([
                'status' => $request->status,
                'updated_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'भुक्तानी स्थिति सफलतापूर्वक अद्यावधिक गरियो!');
        } catch (\Exception $e) {
            Log::error('Payment status update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'भुक्तानी स्थिति अद्यावधिक गर्न असफल।');
        }
    }

    /**
     * Generate payment report (Admin only)
     */
    public function report(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग रिपोर्ट हेर्ने अनुमति छैन');
        }

        // Get date range from request or default to last 30 days
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get payments within date range
        $payments = Payment::with(['student', 'hostel'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->orderBy('payment_date', 'desc')
            ->get();

        // Calculate summary data
        $totalAmount = $payments->sum('amount');
        $completedCount = $payments->where('status', 'completed')->count();
        $pendingCount = $payments->where('status', 'pending')->count();

        return view('admin.payments.report', compact(
            'payments',
            'totalAmount',
            'completedCount',
            'pendingCount',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        // Students can't delete payments
        if (Auth::user()->hasRole('student')) {
            abort(403, 'तपाईंसँग भुक्तानी हटाउने अनुमति छैन');
        }

        try {
            $payment->delete();

            $route = Auth::user()->hasRole('admin') ? 'admin.payments.index' : 'owner.payments.index';
            return redirect()
                ->route($route)
                ->with('success', 'भुक्तानी सफलतापूर्वक हटाइयो!');
        } catch (\Exception $e) {
            Log::error('Payment deletion failed: ' . $e->getMessage());
            Log::error('Stack trace: ...' . $e->getTraceAsString());

            $route = Auth::user()->hasRole('admin') ? 'admin.payments.index' : 'owner.payments.index';
            return redirect()
                ->route($route)
                ->with('error', 'भुक्तानी हटाउन असफल।');
        }
    }

    /**
     * Export payments to Excel for Owner
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'hostel_manager'])) {
            abort(403, 'तपाईंसँग निर्यात गर्ने अनुमति छैन');
        }

        try {
            // Get current user's hostel IDs for owners
            if ($user->hasRole('hostel_manager')) {
                $hostelIds = Hostel::where('owner_id', $user->id)
                    ->orWhere('manager_id', $user->id)
                    ->pluck('id')
                    ->toArray();

                // Apply date filter if provided - ✅ FIXED: Handle empty dates
                $paymentsQuery = Payment::whereIn('hostel_id', $hostelIds)
                    ->with(['student', 'hostel']);

                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $paymentsQuery->whereBetween('payment_date', [
                        $request->start_date,
                        $request->end_date
                    ]);
                }

                $payments = $paymentsQuery->get();

                // ✅ FIXED: Handle filename based on type
                $filename = $this->getExportFilename($request->type, $request->start_date, $request->end_date);
                return Excel::download(new PaymentsExport($payments), $filename);
            }

            // For admin, export all payments (with date filter if needed)
            $paymentsQuery = Payment::with(['student', 'hostel']);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $paymentsQuery->whereBetween('payment_date', [
                    $request->start_date,
                    $request->end_date
                ]);
            }

            $payments = $paymentsQuery->get();

            // ✅ FIXED: Handle filename based on type
            $filename = $this->getExportFilename($request->type, $request->start_date, $request->end_date);
            return Excel::download(new PaymentsExport($payments), $filename);
        } catch (\Exception $e) {
            Log::error('Payment export failed: ' . $e->getMessage());
            Log::error('Stack trace: ...' . $e->getTraceAsString());

            $errorMessage = 'भुक्तानी निर्यात गर्न असफल। कृपया पुनः प्रयास गर्नुहोस्।';

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.payments.index')
                    ->with('error', $errorMessage);
            } else {
                return redirect()->route('owner.payments.report')
                    ->with('error', $errorMessage);
            }
        }
    }

    /**
     * Get export filename based on type and dates
     */
    private function getExportFilename($type = null, $startDate = null, $endDate = null)
    {
        $baseName = 'payments-' . now()->format('Y-m-d');

        // Add type to filename if provided
        if ($type) {
            $baseName = $type . '-' . $baseName;
        }

        // Add date range to filename if provided
        if ($startDate && $endDate) {
            $baseName .= '-from-' . $startDate . '-to-' . $endDate;
        }

        return $baseName . '.xlsx';
    }

    /**
     * Khalti callback route
     */
    public function khaltiCallback(Request $request)
    {
        // Khalti payment verification logic
        $token = $request->token;
        $amount = $request->amount;

        $args = http_build_query([
            'token' => $token,
            'amount'  => $amount
        ]);

        $url = "https://khalti.com/api/v2/payment/verify/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = ['Authorization: Key test_secret_key_XXXXXX'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response);

        if (isset($result->idx)) {
            // Payment successful
            $payment = Payment::find($request->payment_id);
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $result->idx
                ]);

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['error' => 'भुक्तानी प्रमाणीकरण असफल']);
    }

    /**
     * Approve bank transfer payment
     */
    public function approveBankTransfer(Request $request, Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        if ($payment->payment_method !== 'bank_transfer' || $payment->status !== 'pending') {
            return redirect()->back()->with('error', 'अमान्य भुक्तानी वा भुक्तानी अवस्था।');
        }

        try {
            DB::beginTransaction();

            $payment->update([
                'status' => 'completed',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'metadata' => array_merge($payment->metadata ?? [], [
                    'approved_by' => Auth::id(),
                    'approved_at' => now()->toISOString(),
                    'approval_remarks' => $request->input('remarks', '')
                ])
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'बैंक हस्तान्तरण भुक्तानी सफलतापूर्वक स्वीकृत गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bank Transfer Approval Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'भुक्तानी स्वीकृत गर्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * Reject bank transfer payment
     */
    public function rejectBankTransfer(Request $request, Payment $payment)
    {
        $this->checkPaymentPermission($payment);

        if ($payment->payment_method !== 'bank_transfer' || $payment->status !== 'pending') {
            return redirect()->back()->with('error', 'अमान्य भुक्तानी वा भुक्तानी अवस्था।');
        }

        try {
            $payment->update([
                'status' => 'failed',
                'metadata' => array_merge($payment->metadata ?? [], [
                    'rejected_by' => Auth::id(),
                    'rejected_at' => now()->toISOString(),
                    'rejection_reason' => $request->input('reason', '')
                ])
            ]);

            return redirect()->back()->with('success', 'बैंक हस्तान्तरण भुक्तानी सफलतापूर्वक अस्वीकृत गरियो।');
        } catch (\Exception $e) {
            Log::error('Bank Transfer Rejection Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'भुक्तानी अस्वीकृत गर्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * View bank transfer proof
     */
    public function viewProof(Payment $payment)
    {
        if ($payment->payment_method !== 'bank_transfer') {
            abort(404);
        }

        $screenshotPath = $payment->metadata['screenshot_path'] ?? null;

        if (!$screenshotPath || !Storage::disk('public')->exists($screenshotPath)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($screenshotPath));
    }

    /**
     * Student payments (for student role)
     */
    public function studentPayments(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('student')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $payments = Payment::where('student_id', $user->id)
            ->with(['hostel', 'room'])
            ->latest()
            ->paginate(10);

        return view('student.payments.index', compact('payments'));
    }

    /**
     * Show receipt for student
     */
    public function showReceipt($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $this->checkPaymentPermission($payment);

        return view('student.payments.receipt', compact('payment'));
    }

    /**
     * Download receipt
     */
    public function downloadReceipt($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $this->checkPaymentPermission($payment);

        // PDF generation logic here
        // This is a placeholder for PDF download functionality

        return response()->json(['message' => 'Receipt download functionality']);
    }

    /**
     * Bank transfer request form
     */
    public function bankTransferRequest(Request $request)
    {
        // Show bank transfer form
        return view('payments.bank-transfer');
    }

    /**
     * Store bank transfer payment
     */
    public function storeBankTransfer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'screenshot' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $screenshotPath = $request->file('screenshot')->store('payment-proofs', 'public');

            $payment = Payment::create([
                'student_id' => $user->id,
                'amount' => $request->amount,
                'payment_method' => 'bank_transfer',
                'payment_date' => now(),
                'status' => 'pending',
                'metadata' => [
                    'screenshot_path' => $screenshotPath,
                    'bank_name' => $request->bank_name,
                    'transaction_id' => $request->transaction_id
                ],
                'created_by' => $user->id,
            ]);

            DB::commit();

            return redirect()->route('student.payments.index')
                ->with('success', 'बैंक हस्तान्तरण भुक्तानी सफलतापूर्वक दर्ता गरियो। स्वीकृतिको लागि प्रतीक्षा गर्नुहोस्।');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bank transfer payment failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'भुक्तानी दर्ता गर्न असफल। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Esewa payment
     */
    public function payWithEsewa(Request $request)
    {
        // Esewa payment integration logic
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_id' => 'required|exists:payments,id'
        ]);

        // Esewa payment implementation
        return response()->json(['message' => 'Esewa payment initiated']);
    }

    /**
     * Verify esewa payment
     */
    public function verifyEsewa(Request $request)
    {
        // Esewa payment verification logic
        $paymentId = $request->payment_id;
        $refId = $request->refId;

        // Esewa verification implementation
        return response()->json(['success' => true]);
    }

    /**
     * Generate Receipt PDF - SIMPLE WORKING VERSION
     */
    public function generateReceipt($id)
    {
        try {
            $payment = Payment::with(['student', 'hostel'])->find($id);

            if (!$payment) {
                return redirect()->back()->with('error', 'Payment not found');
            }

            // Simple permission check
            $user = Auth::user();
            if ($user->hasRole('student') && $payment->student_id != $user->id) {
                abort(403, 'Unauthorized');
            }

            $data = [
                'payment' => $payment,
                'hostel' => $payment->hostel,
                'student' => $payment->student,
                'logoUrl' => $payment->hostel && $payment->hostel->logo_path ?
                    Storage::disk('public')->url($payment->hostel->logo_path) : null,
                'receipt_number' => 'R-' . $payment->id,
                'description' => $payment->remarks ?: 'No description'
            ];

            return PDF::loadView('pdf.simple_receipt', $data)
                ->download('receipt_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Receipt generation failed');
        }
    }

    /**
     * Simple Bill PDF - WORKING VERSION  
     */
    public function generateBill($id)
    {
        try {
            $payment = Payment::with(['student', 'hostel'])->find($id);

            if (!$payment) {
                return redirect()->back()->with('error', 'Payment not found');
            }

            // Simple permission check
            $user = Auth::user();
            if ($user->hasRole('student') && $payment->student_id != $user->id) {
                abort(403, 'Unauthorized');
            }

            $data = [
                'payment' => $payment,
                'hostel' => $payment->hostel,
                'student' => $payment->student,
                'logoUrl' => $payment->hostel && $payment->hostel->logo_path ?
                    Storage::disk('public')->url($payment->hostel->logo_path) : null,
                'bill_number' => 'B-' . $payment->id,
                'description' => $payment->remarks ?: 'No description'
            ];

            return PDF::loadView('pdf.simple_bill', $data)
                ->download('bill_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bill generation failed');
        }
    }

    /**
     * Convert amount to Nepali words - IMPROVED VERSION
     */
    private function convertToNepaliWords($number)
    {
        $nepaliDigits = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
        $numberStr = number_format($number, 2);
        $nepaliNumber = '';

        for ($i = 0; $i < strlen($numberStr); $i++) {
            if (is_numeric($numberStr[$i])) {
                $nepaliNumber .= $nepaliDigits[(int)$numberStr[$i]];
            } else {
                $nepaliNumber .= $numberStr[$i];
            }
        }

        // Add "मात्र" at the end for official documents
        return $nepaliNumber . ' रुपैयाँ मात्र';
    }

    // =================================================================
    // BILLING AND RECEIPT SYSTEM METHODS
    // =================================================================

    /**
     * Student search for invoice generation
     */
    public function studentSearchForInvoice(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('hostel_manager')) {
            abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन');
        }

        $query = $request->input('query');

        if (!$query) {
            return redirect()->route('owner.payments.report')->with('error', 'कृपया खोज्ने शब्द लेख्नुहोस्।');
        }

        // Get current user's hostel IDs
        $hostelIds = Hostel::where('owner_id', $user->id)
            ->orWhere('manager_id', $user->id)
            ->pluck('id')
            ->toArray();

        $students = Student::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('student_id', 'like', "%{$query}%");
        })
            ->whereIn('hostel_id', $hostelIds)
            ->with(['payments' => function ($p) {
                $p->orderBy('payment_date', 'desc');
            }, 'hostel', 'room'])
            ->limit(20)
            ->get();

        return view('owner.payments.search_results', compact('students', 'query'));
    }

    /**
     * Upload hostel logo
     */
    public function uploadHostelLogo(Request $request, $hostelId)
    {
        $user = Auth::user();

        if (!$user->hasRole('hostel_manager')) {
            abort(403, 'तपाईंसँग लोगो अपलोड गर्ने अनुमति छैन');
        }

        // Verify the hostel belongs to the user
        $hostel = Hostel::where('id', $hostelId)
            ->where(function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhere('manager_id', $user->id);
            })
            ->firstOrFail();

        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Delete old logo if exists
            if ($hostel->logo_path && Storage::disk('public')->exists($hostel->logo_path)) {
                Storage::disk('public')->delete($hostel->logo_path);
            }

            // Store new logo
            $path = $request->file('logo')->store('hostel_logos', 'public');

            $hostel->update([
                'logo_path' => $path
            ]);

            return redirect()->back()->with('success', 'लोगो सफलतापूर्वक अपलोड गरियो!');
        } catch (\Exception $e) {
            Log::error('Logo upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'लोगो अपलोड गर्न असफल। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }
}
