<?php

namespace App\Notifications;

use App\Models\RoomIssue; // RoomIssue model प्रयोग गरिएको
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class RoomIssueNotification extends Notification
{
    use Queueable;

    protected $roomIssue;

    /**
     * नयाँ notification instance सिर्जना गर्नुहोस्।
     */
    public function __construct(RoomIssue $roomIssue)
    {
        $this->roomIssue = $roomIssue;
    }

    /**
     * Notification पठाउने च्यानलहरू निर्धारण गर्नुहोस्।
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * डाटाबेसको लागि notification डाटा ढाँचा (array)।
     */
    public function toDatabase($notifiable)
    {
        $student = $this->roomIssue->student;
        $studentName = $student->user->name ?? $student->name ?? 'कोही';
        $roomNumber  = $this->roomIssue->room->room_number ?? 'अज्ञात';

        // Student image URL बनाउने
        $avatarUrl = null;
        if ($student && $student->image) {
            // तपाईंको storage path अनुसार URL बनाउनुहोस्
            // मानौं student->image मा relative path stored छ (जस्तै: hostels/15/students/filename.jpg)
            $avatarUrl = asset('storage/' . $student->image);
        }

        return [
            'title'      => 'रूम समस्या',
            'message'    => "{$studentName} ले {$roomNumber} कोठाको लागि समस्या रिपोर्ट गरे।",
            'issue_id'   => $this->roomIssue->id,
            'hostel_id'  => $this->roomIssue->hostel_id,
            'room_id'    => $this->roomIssue->room_id,
            'issue'      => $this->roomIssue->issue ?? $this->roomIssue->description,
            'type'       => 'maintenance',
            'url'        => route('owner.room-issues.show', $this->roomIssue->id),
            'avatar'     => $avatarUrl, // यो थपियो
        ];
    }

    /**
     * ब्रोडकास्ट (Pusher) को लागि notification डाटा।
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title'   => 'रूम समस्या',
            'message' => 'नयाँ रूम समस्या रिपोर्ट गरिएको छ।',
            'type'    => 'maintenance',
        ]);
    }
}
