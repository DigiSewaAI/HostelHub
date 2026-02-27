<?php

namespace App\Console\Commands;

use App\Models\MessageThread;
use Illuminate\Console\Command;

class UpdateMessageThreadTypes extends Command
{
    protected $signature = 'messages:update-thread-types';
    protected $description = 'Update thread types based on first message';

    public function handle()
    {
        $threads = MessageThread::with('messages.sender')->get();

        foreach ($threads as $thread) {
            $firstMessage = $thread->messages->first();
            if (!$firstMessage) {
                $thread->type = 'direct';
            } elseif ($firstMessage->sender->isAdmin()) {
                $thread->type = 'broadcast';
            } elseif (in_array($firstMessage->category, ['business_inquiry', 'partnership', 'hostel_sale'])) {
                $thread->type = 'marketplace';
            } else {
                $thread->type = 'direct';
            }
            $thread->saveQuietly();
        }

        $this->info('Thread types updated successfully.');
    }
}
