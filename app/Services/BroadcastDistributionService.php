<?php

namespace App\Services;

use App\Models\BroadcastMessage;
use App\Models\MessageThread;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BroadcastDistributionService
{
    public function distribute(BroadcastMessage $broadcast)
    {
        // approved र पठाइनसकेको मात्र
        if ($broadcast->status !== 'approved' || $broadcast->sent_at) {
            return;
        }

        DB::transaction(function () use ($broadcast) {
            // ✅ सही तरिका: होस्टेलको organization_id बाट eligible प्रयोगकर्ता
            $recipients = User::whereHas('hostels', function ($q) use ($broadcast) {
                $q->where('organization_id', $broadcast->tenant_id)
                    ->where('status', 'active')
                    ->where('is_published', true);
            })
                ->get();

            if ($recipients->isEmpty()) {
                Log::warning('कुनै eligible प्रापक छैन', [
                    'broadcast_id' => $broadcast->id,
                    'tenant_id'    => $broadcast->tenant_id,
                ]);
                return;
            }

            // message thread बनाउने
            $thread = MessageThread::create([
                'tenant_id' => $broadcast->tenant_id,
                'subject'   => $broadcast->subject,
            ]);

            // participants (सबै प्रापक + पठाउने)
            $participantIds = $recipients->pluck('id')->push($broadcast->sender_id)->unique();
            $thread->participants()->createMany(
                $participantIds->map(fn($userId) => ['user_id' => $userId, 'last_read_at' => null])
            );

            // message सिर्जना
            $message = Message::create([
                'thread_id'   => $thread->id,
                'sender_id'   => $broadcast->sender_id,
                'body'        => $broadcast->body,
                'category'    => 'general',
                'priority'    => 'medium',
                'created_at'  => $broadcast->created_at,
            ]);

            // thread को अन्तिम म्यासेज समय अपडेट
            $thread->update(['last_message_at' => $message->created_at]);

            // broadcast लाई sent मार्क
            $broadcast->update([
                'status'   => 'sent',
                'sent_at'  => now(),
            ]);
        });
    }
}
