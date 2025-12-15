<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // ✅ ADDED: Review Events for Admin Notifications
        \App\Events\ReviewSubmitted::class => [
            \App\Listeners\SendReviewSubmittedNotification::class,
        ],

        \App\Events\ReviewApproved::class => [
            \App\Listeners\SendReviewApprovedNotification::class,
        ],

        \App\Events\ReviewRejected::class => [
            \App\Listeners\SendReviewRejectedNotification::class,
        ],

        \App\Events\OwnerRepliedToReview::class => [
            \App\Listeners\SendOwnerReplyNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
