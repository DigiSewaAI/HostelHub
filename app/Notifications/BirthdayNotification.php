<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BirthdayNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Student $birthdayStudent;

    public function __construct(Student $birthdayStudent)
    {
        $this->birthdayStudent = $birthdayStudent;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type'       => 'birthday',
            'message'    => "🎉 आज {$this->birthdayStudent->name} को जन्मदिन हो! उनलाई शुभकामना दिनुहोस् 🎂",
            'student_id' => $this->birthdayStudent->id,
            'url'        => route('owner.students.show', $this->birthdayStudent->id),
        ];
    }
}
