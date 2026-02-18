<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewStudentNotification extends Notification
{
    use Queueable;

    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        $hostelName = $this->student->hostel->name ?? 'हटेल';
        return [
            'title'      => 'नयाँ विद्यार्थी',
            'message'    => "{$this->student->name} {$hostelName} मा भर्ना भएका छन्।",
            'student_id' => $this->student->id,
            'hostel_id'  => $this->student->hostel_id,
            'type'       => 'student',
            'url'        => route('owner.students.show', $this->student->id),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title'   => 'नयाँ विद्यार्थी',
            'message' => "नयाँ विद्यार्थी भर्ना भएको छ।",
            'type'    => 'student',
        ]);
    }
}
