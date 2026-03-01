<?php

namespace App\Listeners;

use App\Events\BroadcastMessageCreated;
use App\Events\NewNotification;
use App\Notifications\NewBroadcastNotification;
use Illuminate\Support\Facades\Log;

class SendBroadcastNotification
{
    public function handle(BroadcastMessageCreated $event)
    {
        $broadcast = $event->broadcast;
        $recipients = $event->recipients; // Eloquent Collection

        foreach ($recipients as $recipient) {
            // ✅ Duplicate check: पछिल्लो १ मिनेटमा यही broadcast_id को notification छ?
            $existing = $recipient->notifications()
                ->where('type', NewBroadcastNotification::class)
                ->where('created_at', '>', now()->subMinutes(1))
                ->whereRaw('JSON_EXTRACT(data, "$.broadcast_id") = ?', [$broadcast->id])
                ->exists();

            if (!$existing) {
                $recipient->notify(new NewBroadcastNotification($broadcast, $broadcast->sender)); // sender पनि पठाउनुपर्छ

                // Broadcast NewNotification event if needed
                $notification = $recipient->notifications()->latest()->first();
                if ($notification) {
                    broadcast(new NewNotification($notification, $recipient->id));
                }
            } else {
                Log::info('Duplicate broadcast notification prevented', [
                    'broadcast_id' => $broadcast->id,
                    'recipient_id' => $recipient->id
                ]);
            }
        }
    }
}
