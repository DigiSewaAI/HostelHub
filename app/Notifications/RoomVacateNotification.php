<?php

namespace App\Notifications;

use App\Models\Student; // Student model प्रयोग गरिएको
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class RoomVacateNotification extends Notification
{
    use Queueable;

    protected $student;

    /**
     * नयाँ notification instance सिर्जना गर्नुहोस्।
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * Notification पठाउने च्यानलहरू निर्धारण गर्नुहोस्।
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // डाटाबेस र ब्रोडकास्ट दुवै
    }

    /**
     * डाटाबेसको लागि notification डाटा ढाँचा (array)।
     */
    public function toDatabase($notifiable)
    {
        $studentName = $this->student->user->name ?? $this->student->name ?? 'कोही';
        $roomNumber  = $this->student->room->room_number ?? 'अज्ञात';

        return [
            'title'      => 'कोठा खाली भयो',
            'message'    => "{$studentName} ले {$roomNumber} कोठा खाली गरेका छन्।",
            'student_id' => $this->student->id,
            'hostel_id'  => $this->student->hostel_id,
            'room_id'    => $this->student->room_id,
            'type'       => 'vacate',
            'url'        => route('owner.students.show', $this->student->id), // owner को student show रुट
        ];
    }

    /**
     * ब्रोडकास्ट (Pusher) को लागि notification डाटा।
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title'   => 'कोठा खाली',
            'message' => 'एउटा कोठा खाली भएको छ।',
            'type'    => 'vacate',
        ]);
    }
}
