<?php

namespace App\Listeners;

use App\Events\BroadcastMessageCreated;
use App\Events\NewNotification;
use App\Notifications\NewBroadcastNotification;
use Illuminate\Support\Facades\Notification;

class SendBroadcastNotification
{
    public function handle(BroadcastMessageCreated $event)
    {
        foreach ($event->recipients as $recipient) {
            $recipient->notify(new NewBroadcastNotification($event->broadcast));

            $notification = $recipient->notifications()->latest()->first();
            if ($notification) {
                broadcast(new NewNotification($notification, $recipient->id));
            }
        }
    }
}
