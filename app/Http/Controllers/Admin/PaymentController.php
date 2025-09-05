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

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $this->authorize('viewAny', Payment::class);

        $payments = Payment::with(['student', 'hostel'])
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        $this->authorize('create', Payment::class);

        $students = Student::where('status', 'active')->get();
        $hostels = Hostel::all();

        return view('admin.payments.create', compact('students', 'hostels'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Payment::class);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'hostel_id' => 'required|exists:hostels,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'status' => 'required|in:pending,completed,failed',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $payment = Payment::create([
                'student_id' => $request->student_id,
                'hostel_id' => $request->hostel_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'status' => $request->status,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Payment recorded successfully!');
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
        $this->authorize('view', $payment);

        $payment->load(['student', 'hostel', 'createdBy', 'updatedBy']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        $this->authorize('update', $payment);

        $students = Student::where('status', 'active')->get();
        $hostels = Hostel::all();

        return view('admin.payments.edit', compact('payment', 'students', 'hostels'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'hostel_id' => 'required|exists:hostels,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'status' => 'required|in:pending,completed,failed',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $payment->update([
                'student_id' => $request->student_id,
                'hostel_id' => $request->hostel_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Payment updated successfully!');
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
     * Generate payment report
     */
    public function report(Request $request)
    {
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
        $this->authorize('delete', $payment);

        try {
            $payment->delete();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Payment deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Payment deletion failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'Failed to delete payment.');
        }
    }

    /**
     * Export payments to Excel
     */
    public function export()
    {
        $this->authorize('export', Payment::class);

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
