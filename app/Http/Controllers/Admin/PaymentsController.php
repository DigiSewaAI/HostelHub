<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Payment; // तपाईंको Payment मोडेल अनुसार परिवर्तन गर्नुहोस्
use App\Models\Student;
use App\Models\Hostel;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
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
        $students = Student::where('status', 'active')->get();
        $hostels = Hostel::all();

        return view('admin.payments.create', compact('students', 'hostels'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
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
        $payment->load(['student', 'hostel', 'createdBy']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        $students = Student::where('status', 'active')->get();
        $hostels = Hostel::all();

        return view('admin.payments.edit', compact('payment', 'students', 'hostels'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
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

            return back()
                ->withInput()
                ->with('error', 'Failed to update payment. Please try again.');
        }
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        try {
            $payment->delete();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Payment deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Payment deletion failed: ' . $e->getMessage());

            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'Failed to delete payment.');
        }
    }

    /**
     * Export payments to CSV
     */
    public function export()
    {
        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Student', 'Hostel', 'Amount', 'Date', 'Method', 'Status', 'Transaction ID', 'Created At']);

            Payment::with(['student', 'hostel'])
                ->orderBy('payment_date', 'desc')
                ->chunk(100, function ($payments) use ($handle) {
                    foreach ($payments as $payment) {
                        fputcsv($handle, [
                            $payment->id,
                            $payment->student->name ?? 'N/A',
                            $payment->hostel->name ?? 'N/A',
                            $payment->amount,
                            $payment->payment_date,
                            $payment->payment_method,
                            $payment->status,
                            $payment->transaction_id,
                            $payment->created_at,
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payments-export-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}