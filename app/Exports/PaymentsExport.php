<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Payment::with('student', 'hostel')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student',
            'Hostel',
            'Amount',
            'Payment Date',
            'Method',
            'Status',
            'Created At'
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->student->name ?? 'N/A',
            $payment->hostel->name ?? 'N/A',
            $payment->amount,
            $payment->payment_date->format('Y-m-d'),
            $payment->payment_method,
            $payment->status,
            $payment->created_at->format('Y-m-d H:i:s')
        ];
    }
}
