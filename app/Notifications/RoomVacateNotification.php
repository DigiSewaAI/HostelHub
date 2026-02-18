<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class RoomVacateNotification extends Notification
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
        $studentName = $this->booking->student->name ?? $this->booking->guest_name ?? 'कोही';
        return [
            'title'      => 'कोठा खाली भयो',
            'message'    => "{$studentName} ले {$this->booking->room->room_number} कोठा खाली गरेका छन्।",
            'booking_id' => $this->booking->id,
            'hostel_id'  => $this->booking->hostel_id,
            'room_id'    => $this->booking->room_id,
            'type'       => 'vacate',
            'url'        => route('owner.bookings.show', $this->booking->id),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title'   => 'कोठा खाली',
            'message' => "एउटा कोठा खाली भएको छ।",
            'type'    => 'vacate',
        ]);
    }
}
