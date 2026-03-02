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

    /**
     * Create a new notification instance.
     */
    public function __construct(Student $birthdayStudent)
    {
        $this->birthdayStudent = $birthdayStudent;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'birthday',
            'message' => "🎉 आज {$this->student->name} को जन्मदिन हो! उनलाई शुभकामना दिनुहोस् 🎂",
            'student_id' => $this->student->id,
            'url' => route('owner.students.show', $this->student->id), // ✅ Owner को लागि
        ];
    }
}
