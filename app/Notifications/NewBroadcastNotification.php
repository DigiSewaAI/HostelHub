<?php

namespace App\Notifications;

use App\Models\BroadcastMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage as BroadcastNotificationMessage;

class NewBroadcastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $broadcast;

    public function __construct(BroadcastMessage $broadcast)
    {
        $this->broadcast = $broadcast;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        $sender = $this->broadcast->sender;
        $avatar = getNotificationAvatar($sender, 'broadcast');

        return [
            'type'      => 'broadcast',
            'title'     => 'प्रसारण सन्देश',
            'message'   => $this->broadcast->subject,
            'avatar'    => $avatar,
            'url'       => route('network.broadcast.index'), // उपयुक्त पृष्ठ
            'sender_id' => $sender->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastNotificationMessage($this->toDatabase($notifiable));
    }
}
