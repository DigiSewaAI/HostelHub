<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BackfillInvoices extends Command
{
    protected $signature = 'invoices:backfill';
    protected $description = 'पुराना payment हरूको लागि इनभ्वाइस सिर्जना गर्ने';

    public function handle()
    {
        $payments = Payment::whereNull('invoice_id')->get();
        $bar = $this->output->createProgressBar($payments->count());
        $bar->start();

        foreach ($payments as $payment) {
            $studentId = $payment->student_id;
            $hostelId = $payment->hostel_id ?? optional($payment->student)->hostel_id;
            if (!$hostelId) {
                $this->warn("Payment ID {$payment->id} को hostel ID छैन, छोडियो");
                continue;
            }

            $paymentDate = Carbon::parse($payment->payment_date);
            $billingMonth = $paymentDate->copy()->startOfMonth()->toDateString();

            $invoice = Invoice::firstOrCreate(
                [
                    'student_id' => $studentId,
                    'billing_month' => $billingMonth,
                ],
                [
                    'hostel_id' => $hostelId,
                    'amount' => $payment->amount,
                    'due_date' => $payment->due_date ?? $paymentDate->copy()->addDays(7)->toDateString(),
                    'status' => 'unpaid',
                ]
            );

            $payment->invoice_id = $invoice->id;
            $payment->saveQuietly(); // booted listeners नचलाउन saveQuietly

            // इनभ्वाइसको status अपडेट गर्ने (यसको सबै payment हरू हेरेर)
            $invoice->updateStatus();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('इनभ्वाइस सफलतापूर्वक backfill गरियो।');
    }
}
