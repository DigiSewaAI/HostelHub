<?php

namespace App\Listeners;

use App\Events\MarketplaceInquirySent;
use App\Notifications\NewMarketplaceInquiryNotification;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Log;

class SendMarketplaceInquiryNotification
{
    public function handle(MarketplaceInquirySent $event)
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

            // ✅ Duplicate check: पछिल्लो १ मिनेटमा यही listing_id र sender_id बाट notification छ?
            $existing = $recipient->notifications()
                ->where('type', NewMarketplaceInquiryNotification::class)
                ->where('created_at', '>', now()->subMinutes(1))
                ->whereRaw('JSON_EXTRACT(data, "$.listing_id") = ?', [$event->listing->id])
                ->whereRaw('JSON_EXTRACT(data, "$.sender_id") = ?', [$event->sender->id])
                ->exists();

            if (!$existing) {
                $recipient->notify(new NewMarketplaceInquiryNotification(
                    $event->listing,
                    $event->sender,
                    $event->thread
                ));

                // Broadcast NewNotification event (if needed)
                $notification = $recipient->notifications()->latest()->first();
                if ($notification) {
                    broadcast(new NewNotification($notification, $recipient->id));
                }
                Log::info('✅ Marketplace notification sent');
            } else {
                Log::info('⏭️ Duplicate marketplace notification prevented', [
                    'listing_id' => $event->listing->id,
                    'sender_id' => $event->sender->id,
                    'recipient_id' => $recipient->id
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('❌ Exception in SendMarketplaceInquiryNotification', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }
    }
}
