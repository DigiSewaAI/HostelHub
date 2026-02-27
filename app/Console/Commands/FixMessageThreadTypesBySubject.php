<?php

namespace App\Console\Commands;

use App\Models\MessageThread;
use Illuminate\Console\Command;

class FixMessageThreadTypesBySubject extends Command
{
    protected $signature = 'messages:fix-types-by-subject';
    protected $description = 'Fix thread types based on first message subject and category';

    public function handle()
    {
        $threads = MessageThread::with('messages')->get();
        $marketplaceCategories = ['business_inquiry', 'partnership', 'hostel_sale'];
        $updated = 0;

        foreach ($threads as $thread) {
            $firstMessage = $thread->messages->first();
            if (!$firstMessage) {
                // यदि कुनै सन्देश छैन भने पनि direct नै रहन्छ
                continue;
            }

            $newType = null;

            // 1. Broadcast check (subject मा "Broadcast" भएमा)
            if (stripos($firstMessage->body ?? '', 'broadcast') !== false || stripos($thread->subject ?? '', 'broadcast') !== false) {
                $newType = 'broadcast';
            }
            // 2. Marketplace check (category अनुसार)
            elseif (in_array($firstMessage->category, $marketplaceCategories)) {
                $newType = 'marketplace';
            }
            // 3. Direct
            else {
                $newType = 'direct';
            }

            if ($thread->type !== $newType) {
                $thread->type = $newType;
                $thread->saveQuietly();
                $updated++;
                $this->info("Thread ID {$thread->id} type changed to {$newType}");
            }
        }

        $this->info("Total {$updated} threads updated.");
    }
}
