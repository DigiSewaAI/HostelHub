<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $payments;

    public function __construct($payments = null)
    {
        $this->payments = $payments ?: Payment::with('student', 'hostel')->get();
    }

    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'आईडी',
            'विद्यार्थीको नाम',
            'होस्टल',
            'रकम',
            'भुक्तानी मिति',
            'भुक्तानी विधि',
            'स्थिति',
            'दर्ता मिति'
        ];
    }

    public function map($payment): array
    {
        // ✅ FIXED: Safe payment method text
        $paymentMethodText = $this->getPaymentMethodText($payment->payment_method);

        // ✅ FIXED: Safe status text
        $statusText = $this->getStatusText($payment->status);

        // ✅ FIXED: Safe date formatting
        $paymentDate = $payment->payment_date instanceof \Carbon\Carbon
            ? $payment->payment_date->format('Y-m-d')
            : Carbon::parse($payment->payment_date)->format('Y-m-d');

        $createdAt = $payment->created_at instanceof \Carbon\Carbon
            ? $payment->created_at->format('Y-m-d H:i:s')
            : Carbon::parse($payment->created_at)->format('Y-m-d H:i:s');

        return [
            $payment->id,
            $payment->student->name ?? 'नभएको',
            $payment->hostel->name ?? 'नभएको',
            'रु ' . number_format($payment->amount, 2),
            $paymentDate,
            $paymentMethodText,
            $statusText,
            $createdAt
        ];
    }

    /**
     * Get payment method text in Nepali
     */
    private function getPaymentMethodText($method): string
    {
        return match ($method) {
            'cash' => 'नगद',
            'bank_transfer' => 'बैंक स्थानान्तरण',
            'khalti' => 'खल्ती',
            'esewa' => 'ईसेवा',
            'connectips' => 'कनेक्टआइपीएस',
            default => $method
        };
    }

    /**
     * Get status text in Nepali
     */
    private function getStatusText($status): string
    {
        return match ($status) {
            'completed' => 'पूरा भयो',
            'pending' => 'बाँकी',
            'failed' => 'असफल',
            'refunded' => 'फिर्ता भयो',
            default => $status
        };
    }
}
