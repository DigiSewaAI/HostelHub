<?php

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $booking;
    public $isGuest;
    public $type;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking, $isGuest = false, $type = 'created')
    {
        $this->booking = $booking;
        $this->isGuest = $isGuest;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $customerEmail = $this->booking->getCustomerEmail();
            $customerName = $this->booking->getCustomerName();

            if (!$customerEmail) {
                Log::error('SendBookingEmail: No customer email found for booking ID: ' . $this->booking->id);
                return;
            }

            $data = [
                'booking' => $this->booking,
                'customerName' => $customerName,
                'customerEmail' => $customerEmail,
                'isGuest' => $this->isGuest,
                'type' => $this->type,
                'hostel' => $this->booking->hostel,
                'room' => $this->booking->room,
            ];

            // ✅ FIXED: Remove any undefined array keys
            if ($this->type === 'approved') {
                $subject = 'तपाईंको बुकिंग स्वीकृत भयो';
                $view = 'emails.bookings.approved';
            } elseif ($this->type === 'rejected') {
                $subject = 'तपाईंको बुकिंग अस्वीकृत भयो';
                $view = 'emails.bookings.rejected';
                // ✅ ADD: rejection reason to data
                $data['rejectionReason'] = $this->booking->rejection_reason;
            } else {
                $subject = 'तपाईंको बुकिंग सफलतापूर्वक सिर्जना गरियो';
                $view = 'emails.bookings.created';
            }

            Mail::send($view, $data, function ($message) use ($customerEmail, $customerName, $subject) {
                $message->to($customerEmail, $customerName)
                    ->subject($subject);
            });

            Log::info('SendBookingEmail: Email sent successfully to ' . $customerEmail);
        } catch (\Exception $e) {
            Log::error('SendBookingEmail failed: ' . $e->getMessage());
        }
    }
}
