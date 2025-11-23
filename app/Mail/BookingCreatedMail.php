<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $isGuest;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $isGuest = false, $status = null)
    {
        $this->booking = $booking;
        $this->isGuest = $isGuest;
        $this->status = $status;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->getSubject();

        return $this->subject($subject)
            ->view('emails.booking-created')
            ->with([
                'booking' => $this->booking,
                'isGuest' => $this->isGuest,
                'status' => $this->status,
            ]);
    }

    /**
     * Get email subject based on booking type and status
     */
    private function getSubject(): string
    {
        if ($this->isGuest) {
            return 'तपाईंको बुकिंग अनुरोध प्राप्त भयो - HostelHub';
        }

        switch ($this->status) {
            case 'approved':
                return 'तपाईंको बुकिंग स्वीकृत भयो - HostelHub';
            case 'rejected':
                return 'तपाईंको बुकिंग अस्वीकृत भयो - HostelHub';
            default:
                return 'तपाईंको बुकिंग स्थिति अपडेट - HostelHub';
        }
    }
}
