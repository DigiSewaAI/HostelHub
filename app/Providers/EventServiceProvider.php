<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\ChatMessageSent;
use App\Listeners\SendChatMessageNotification;
use App\Events\BroadcastMessageCreated;
use App\Listeners\SendBroadcastNotification;
use App\Events\MarketplaceInquirySent;
use App\Listeners\SendMarketplaceInquiryNotification;

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
        ChatMessageSent::class => [
            SendChatMessageNotification::class,
        ],
        BroadcastMessageCreated::class => [
            SendBroadcastNotification::class,
        ],
        MarketplaceInquirySent::class => [
            SendMarketplaceInquiryNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // ✅ सबै Observers हटाइयो (Notification प्रणालीले काम गर्छ)
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
