<?php

namespace App\Console\Commands;

use App\Models\MessageThread;
use App\Models\User;
use Illuminate\Console\Command;

class FixMessageThreadTypes extends Command
{
    protected $signature = 'messages:fix-types';
    protected $description = 'Fix message thread types based on first message category and sender role';

    public function handle()
    {
        // सबै थ्रेड लिने, messages सहित
        $threads = MessageThread::with('messages.sender')->get();

        // admin को ID पत्ता लगाउने (तपाईंको admin को नाम के हो?)
        // यदि तपाईंसँग role system छ भने, त्यस अनुसार गर्नुहोस्
        // मानौं admin को email 'admin@example.com' छ:
        $admin = User::where('email', 'admin@example.com')->first();
        $adminId = $admin?->id;

        foreach ($threads as $thread) {
            $firstMessage = $thread->messages->first();

            // यदि कुनै message नै छैन भने direct
            if (!$firstMessage) {
                $thread->type = 'direct';
                $thread->saveQuietly();
                continue;
            }

            $category = $firstMessage->category;
            $sender = $firstMessage->sender;

            // 1. यदि sender admin हो भने → broadcast
            if ($sender && $sender->id === $adminId) {
                $thread->type = 'broadcast';
            }
            // 2. यदि category marketplace सँग सम्बन्धित छ भने → marketplace
            elseif ($category && in_array($category, ['business_inquiry', 'partnership', 'hostel_sale'])) {
                $thread->type = 'marketplace';
            }
            // 3. बाँकी सबै → direct
            else {
                $thread->type = 'direct';
            }

            $thread->saveQuietly();
        }

        $this->info('Thread types fixed successfully.');
    }
}
