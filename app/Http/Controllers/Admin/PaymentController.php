<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Hostel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Get data based on user role
     */
    private function getDataByRole()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return Payment::with(['student', 'hostel'])->latest();
        } elseif ($user->hasRole('hostel_manager')) {
            return Payment::where('hostel_id', $user->hostel_id)
                ->with('student')
                ->latest();
        } elseif ($user->hasRole('student')) {
            return Payment::where('student_id', $user->id)
                ->with('hostel')
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
        } elseif ($user->hasRole('hostel_manager') && $payment->hostel_id == $user->hostel_id) {
            return true;
        } elseif ($user->hasRole('student') && $payment->student_id == $user->id) {
            return true;
        }

        abort(403, 'तपाईंसँग यो भुक्तानी हेर्ने अनुमति छैन');
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
            $students = Student::where('hostel_id', $user->hostel_id)->get();
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
            'notes' => 'nullable|string|max:500',
        ];

        // Add hostel_id validation for admin
        if ($user->hasRole('admin')) {
            $validationRules['hostel_id'] = 'required|exists:hostels,id';
        }

        $request->validate($validationRules);

        // For owners, ensure student belongs to their hostel
        if ($user->hasRole('hostel_manager')) {
            $student = Student::find($request->student_id);
            if ($student->hostel_id != $user->hostel_id) {
                return back()
                    ->withInput()
                    ->with('error', 'तपाईंसँग यो विद्यार्थीको लागि भुक्तानी थप्ने अनुमति छैन');
            }
        }

        try {
            DB::beginTransaction();

            $paymentData = [
                'student_id' => $request->student_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'notes' => $request->notes,
                'created_by' => $user->id,
            ];

            // Set hostel_id based on role
            if ($user->hasRole('admin')) {
                $paymentData['hostel_id'] = $request->hostel_id;
            } elseif ($user->hasRole('hostel_manager')) {
                $paymentData['hostel_id'] = $user->hostel_id;
            }

            Payment::create($paymentData);

            DB::commit();

            $route = $user->hasRole('admin') ? 'admin.payments.index' : 'owner.payments.index';
            return redirect()
                ->route($route)
                ->with('success', 'भुक्तानी सफलतापूर्वक थपियो!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'Failed to record payment. Please try again.');
        }
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $this->checkPaymentPermission($payment);
        $payment->load(['student', 'hostel', 'createdBy', 'updatedBy']);

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
            $students = Student::where('hostel_id', $user->hostel_id)->get();
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
            'notes' => 'nullable|string|max:500',
        ];

        // Add hostel_id validation for admin
        if ($user->hasRole('admin')) {
            $validationRules['hostel_id'] = 'required|exists:hostels,id';
        }

        $request->validate($validationRules);

        // For owners, ensure student belongs to their hostel
        if ($user->hasRole('hostel_manager')) {
            $student = Student::find($request->student_id);
            if ($student->hostel_id != $user->hostel_id) {
                return back()
                    ->withInput()
                    ->with('error', 'तपाईंसँग यो विद्यार्थीको लागि भुक्तानी सम्पादन गर्ने अनुमति छैन');
            }
        }

        try {
            DB::beginTransaction();

            $paymentData = [
                'student_id' => $request->student_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'notes' => $request->notes,
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
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'Failed to update payment. Please try again.');
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
            Log::error('Stack trace: ' . $e->getTraceAsString());

            $route = Auth::user()->hasRole('admin') ? 'admin.payments.index' : 'owner.payments.index';
            return redirect()
                ->route($route)
                ->with('error', 'Failed to delete payment.');
        }
    }

    /**
     * Export payments to Excel (Admin only)
     */
    public function export()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'तपाईंसँग निर्यात गर्ने अनुमति छैन');
        }

        try {
            return Excel::download(new PaymentsExport, 'payments-' . now()->format('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('Payment export failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'Failed to export payments. Please try again.');
        }
    }
}
