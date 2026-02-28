<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\MessageThread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewChatMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $thread;

    public function __construct(Message $message, MessageThread $thread)
    {
        $this->message = $message;
        $this->thread = $thread;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        $sender = $this->message->sender;
        $avatar = getNotificationAvatar($sender, 'chat');

        return [
            'type'      => 'chat',
            'title'     => 'नयाँ सन्देश',
            'message'   => $sender->name . ' ले तपाईंलाई सन्देश पठाएका छन्।',
            'avatar'    => $avatar,
            'url'       => route('network.messages.show', $this->thread->id),
            'sender_id' => $sender->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
