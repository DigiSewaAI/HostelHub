<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewBookingNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        $guestName = $this->booking->guest_name ?? $this->booking->student->name ?? 'कोही';
        return [
            'title'      => 'नयाँ बुकिङ',
            'message'    => "{$guestName} ले {$this->booking->room->room_number} कोठा बुक गरेका छन्।",
            'booking_id' => $this->booking->id,
            'hostel_id'  => $this->booking->hostel_id,
            'room_id'    => $this->booking->room_id,
            'type'       => 'booking',
            'url'        => route('owner.bookings.show', $this->booking->id),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title'   => 'नयाँ बुकिङ',
            'message' => "एउटा नयाँ बुकिङ भएको छ।",
            'type'    => 'booking',
        ]);
    }
}
