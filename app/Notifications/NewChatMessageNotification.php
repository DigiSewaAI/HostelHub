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
            'message'   => $this->getMessageText($sender),
            'avatar'    => $avatar,
            'url'       => route('network.messages.show', $this->thread->id),
            'sender_id' => $sender->id,
            'message_id' => $this->message->id, // ✅ यहाँ पनि थप्नुहोस्
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    private function getMessageText($sender)
    {
        $hostelName = null;

        if ($sender->isHostelManager()) {
            $hostel = $sender->hostels()->first();
            if ($hostel) {
                $hostelName = $hostel->name;
            }
        }

        if ($hostelName) {
            return $hostelName . ' बाट ' . $sender->name . ' ले तपाईंलाई नयाँ सन्देश पठाएका छन्।';
        }

        return $sender->name . ' ले तपाईंलाई सन्देश पठाएका छन्।';
    }
}
