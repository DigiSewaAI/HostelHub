<?php

namespace App\Events;

use App\Models\Message;
use App\Models\MessageThread;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $thread;
    public $recipients;

    public function __construct(Message $message, MessageThread $thread, $recipients)
    {
        $this->message = $message;
        $this->thread = $thread;
        $this->recipients = $recipients; // Collection of User models
    }
}
