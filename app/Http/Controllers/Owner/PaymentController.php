<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Hostel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Check if user has permission to access a specific payment
     */
    private function checkPaymentPermission(Payment $payment)
    {
        $user = Auth::user();

        $hostelIds = Hostel::where('owner_id', $user->id)
            ->orWhere('manager_id', $user->id)
            ->pluck('id')
            ->toArray();

        if (!in_array($payment->hostel_id, $hostelIds)) {
            abort(403, 'तपाईंसँग यो भुक्तानी हेर्ने अनुमति छैन');
        }
    }

    /**
     * Generate Bill PDF - NEPALI FONT SUPPORTED
     */
    public function generateBill($id)
    {
        try {
            Log::info('Owner: Generating bill for payment: ' . $id);

            $payment = Payment::with(['student', 'hostel'])->findOrFail($id);

            // Check permission
            $this->checkPaymentPermission($payment);

            Log::info('Payment found: ' . $payment->id);

            $pdf = Pdf::loadView('pdf.bill', [
                'payment' => $payment,
                'hostel' => $payment->hostel,
                'student' => $payment->student,
                'bill_number' => 'BILL-' . $payment->id,
            ])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'helvetica',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);

            Log::info('PDF generated successfully');
            return $pdf->stream('bill_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Owner Bill PDF Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'बिल जनरेसन असफल भयो',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Generate Receipt PDF - NEPALI FONT SUPPORTED
     */
    public function generateReceipt($id)
    {
        try {
            Log::info('Owner: Generating receipt for payment: ' . $id);

            $payment = Payment::with(['student', 'hostel'])->findOrFail($id);

            // Check permission
            $this->checkPaymentPermission($payment);

            Log::info('Payment found: ' . $payment->id);

            $pdf = Pdf::loadView('pdf.receipt', [
                'payment' => $payment,
                'hostel' => $payment->hostel,
                'student' => $payment->student,
                'receipt_number' => 'REC-' . $payment->id,
            ])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'helvetica',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);

            Log::info('PDF generated successfully');
            return $pdf->stream('receipt_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Owner Receipt PDF Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'रसिद जनरेसन असफल भयो',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Debug PDF Generation
     */
    public function debugPDF()
    {
        try {
            Log::info('Debug PDF function called');

            // Simple HTML test with Nepali text
            $html = '
            <html>
            <head>
                <title>Test PDF</title>
                <style>
                    body { font-family: "NotoSansDevanagari", Arial; }
                    h1 { color: red; }
                </style>
            </head>
            <body>
                <h1>Test PDF Generation</h1>
                <p>Date: ' . now()->format('Y-m-d H:i:s') . '</p>
                <p>नेपाली टेक्स्ट टेस्ट: १ २ ३ ४ ५ ६ ७ ८ ९ ०</p>
                <p>If you see this, DOMPDF is working!</p>
            </body>
            </html>';

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'NotoSansDevanagari',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);

            Log::info('PDF created successfully');
            return $pdf->stream('debug_test.pdf');
        } catch (\Exception $e) {
            Log::error('Debug PDF Error: ' . $e->getMessage());
            return response('PDF Error: ' . $e->getMessage(), 500);
        }
    }
}
