<?php

namespace App\Jobs;

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
    public $status;

    /**
     * Create a new job instance.
     */
    public function __construct($booking, $isGuest = false, $status = null)
    {
        $this->booking = $booking;
        $this->isGuest = $isGuest;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $email = $this->booking->getCustomerEmail();

            if (!$email) {
                Log::warning('No email found for booking', ['booking_id' => $this->booking->id]);
                return;
            }

            Log::info('Sending booking email', [
                'booking_id' => $this->booking->id,
                'email' => $email,
                'is_guest' => $this->isGuest,
                'status' => $this->status
            ]);

            Mail::to($email)->send(new \App\Mail\BookingCreatedMail(
                $this->booking,
                $this->isGuest,
                $this->status
            ));

            Log::info('Booking email sent successfully', ['booking_id' => $this->booking->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking email', [
                'booking_id' => $this->booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Exception $exception)
    {
        Log::error('SendBookingEmail job failed', [
            'booking_id' => $this->booking->id,
            'error' => $exception->getMessage()
        ]);
    }
}
