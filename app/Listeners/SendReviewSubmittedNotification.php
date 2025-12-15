<?php

namespace App\Listeners;

use App\Events\ReviewSubmitted;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendReviewSubmittedNotification implements ShouldQueue
{
    public function handle(ReviewSubmitted $event): void
    {
        // Get all admin users
        $admins = User::role('admin')->get();

        // Send notification (using existing notification system)
        Notification::send($admins, new \App\Notifications\ReviewSubmitted($event->review));
    }
}
