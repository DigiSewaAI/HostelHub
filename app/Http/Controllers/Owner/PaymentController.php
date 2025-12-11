<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;  // ✅ यसरी specific package use गर्नुहोस्
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function generateBill($id)
    {
        try {
            Log::info('Generating bill for payment: ' . $id);

            $payment = Payment::with(['student', 'hostel'])->findOrFail($id);
            Log::info('Payment found: ' . $payment->id);

            $pdf = Pdf::loadView('pdf.bill', [
                'payment' => $payment,
                'hostel' => $payment->hostel,
                'student' => $payment->student,
                'bill_number' => 'BILL-' . $payment->id,
            ])->setPaper('a4', 'portrait');  // ✅ यसरी

            Log::info('PDF generated successfully');
            return $pdf->stream('bill_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Bill PDF Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            // Detailed error for debugging
            return response()->json([
                'success' => false,
                'message' => 'Bill generation failed',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function generateReceipt($id)
    {
        try {
            Log::info('Generating receipt for payment: ' . $id);

            $payment = Payment::with(['student', 'hostel'])->findOrFail($id);
            Log::info('Payment found: ' . $payment->id);

            $pdf = Pdf::loadView('pdf.receipt', [
                'payment' => $payment,
                'hostel' => $payment->hostel,
                'student' => $payment->student,
                'receipt_number' => 'REC-' . $payment->id,
            ])->setPaper('a4', 'portrait');  // ✅ यसरी

            Log::info('PDF generated successfully');
            return $pdf->stream('receipt_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Receipt PDF Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            // Detailed error for debugging
            return response()->json([
                'success' => false,
                'message' => 'Receipt generation failed',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function debugPDF()
    {
        try {
            Log::info('Debug PDF function called');

            // Simple HTML test
            $html = '
            <html>
            <head>
                <title>Test PDF</title>
                <style>
                    body { font-family: Arial; }
                    h1 { color: red; }
                </style>
            </head>
            <body>
                <h1>Test PDF Generation</h1>
                <p>Date: ' . now()->format('Y-m-d H:i:s') . '</p>
                <p>If you see this, DOMPDF is working!</p>
            </body>
            </html>';

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOptions(['defaultFont' => 'sans-serif']);  // ✅ setOptions use गर्नुहोस्

            Log::info('PDF created successfully');
            return $pdf->stream('debug_test.pdf');
        } catch (\Exception $e) {
            Log::error('Debug PDF Error: ' . $e->getMessage());
            return response('PDF Error: ' . $e->getMessage(), 500);
        }
    }
}
