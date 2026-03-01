<?php

namespace App\Listeners;

use App\Events\MarketplaceInquirySent;
use App\Notifications\NewMarketplaceInquiryNotification;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendMarketplaceInquiryNotification
{
    public function handle(MarketplaceInquirySent $event): void
    {
        try {
            Log::info('🚀 SendMarketplaceInquiryNotification started', [
                'listing_id'  => $event->listing->id ?? null,
                'sender_id'   => $event->sender->id ?? null,
                'recipient_id' => $event->recipient->id ?? null,
                'thread_id'   => $event->thread->id ?? null,
            ]);

            $recipient = $event->recipient;

            if (!$recipient) {
                Log::error('❌ Recipient not found in event');
                return;
            }

            Log::info('Recipient details', [
                'id'    => $recipient->id,
                'email' => $recipient->email,
                'class' => get_class($recipient),
            ]);

            $notification = new NewMarketplaceInquiryNotification(
                $event->listing,
                $event->sender,
                $event->thread
            );

            $recipient->notify($notification);
            Log::info('✅ notify() method called');

            // Verify insertion - डाटाबेसमा पर्यो कि परेन जाँच गर्ने
            $latestNotification = $recipient->notifications()->latest()->first();
            if ($latestNotification) {
                Log::info('✅ Notification found in database', [
                    'id' => $latestNotification->id,
                    'type' => $latestNotification->type,
                    'data' => $latestNotification->data,
                ]);
            } else {
                Log::warning('⚠️ Notification NOT found after notify()');
            }
        } catch (Throwable $e) {
            Log::error('❌ Exception in SendMarketplaceInquiryNotification', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }
    }
}
