<?php

namespace App\Listeners;

use App\Events\ChatMessageSent;
use App\Events\NewNotification;
use App\Notifications\NewChatMessageNotification;
use Illuminate\Support\Facades\Notification;

class SendChatMessageNotification
{
    public function handle(ChatMessageSent $event)
    {
        // 1. प्रत्येक recipient लाई notification पठाउने
        foreach ($event->recipients as $recipient) {
            $recipient->notify(new NewChatMessageNotification($event->message, $event->thread));

            // 2. यो नोटिफिकेसन पठाइसकेपछि, भर्खरै सिर्जना भएको notification लिने
            $notification = $recipient->notifications()->latest()->first();

            // 3. यदि notification भेटियो भने, custom broadcast event पठाउने
            if ($notification) {
                broadcast(new NewNotification($notification, $recipient->id));
            }
        }
    }
}
