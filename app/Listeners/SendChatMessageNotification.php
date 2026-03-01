<?php

namespace App\Listeners;

use App\Events\ChatMessageSent;
use App\Events\NewNotification;
use App\Notifications\NewChatMessageNotification;
use Illuminate\Support\Facades\Log;

class SendChatMessageNotification
{
    public function handle(ChatMessageSent $event)
    {
        foreach ($event->recipients as $recipient) {
            // ✅ Duplicate check: पछिल्लो १ मिनेटमा यही message_id को notification छ?
            $existing = $recipient->notifications()
                ->where('type', NewChatMessageNotification::class)
                ->where('created_at', '>', now()->subMinutes(1))
                ->whereRaw('JSON_EXTRACT(data, "$.message_id") = ?', [$event->message->id])
                ->exists();

            if (!$existing) {
                $recipient->notify(new NewChatMessageNotification($event->message, $event->thread));

                $notification = $recipient->notifications()->latest()->first();
                if ($notification) {
                    broadcast(new NewNotification($notification, $recipient->id));
                }
            } else {
                Log::info('Duplicate chat notification prevented', [
                    'message_id' => $event->message->id,
                    'recipient_id' => $recipient->id
                ]);
            }
        }
    }
}
