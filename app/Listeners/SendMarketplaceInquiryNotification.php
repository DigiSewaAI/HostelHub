<?php

namespace App\Listeners;

use App\Events\MarketplaceInquirySent;
use App\Events\NewNotification;
use App\Notifications\NewMarketplaceInquiryNotification;

class SendMarketplaceInquiryNotification
{
    public function handle(MarketplaceInquirySent $event)
    {
        // यहाँ recipient एकल हो, तर code consistency को लागि उस्तै राखौं
        $recipient = $event->recipient;

        $recipient->notify(new NewMarketplaceInquiryNotification($event->listing, $event->sender, $event->thread));

        $notification = $recipient->notifications()->latest()->first();
        if ($notification) {
            broadcast(new NewNotification($notification, $recipient->id));
        }
    }
}
