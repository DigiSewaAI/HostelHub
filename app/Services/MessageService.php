<?php

namespace App\Services;

use App\Models\MessageThread;
use App\Models\Message;
use App\Models\MessageThreadParticipant;
use Illuminate\Support\Facades\DB;

class MessageService
{
    /**
     * नयाँ थ्रेड सिर्जना गर्ने
     */
    public function createThread(array $participantIds, $subject = null)
    {
        return DB::transaction(function () use ($participantIds, $subject) {
            $thread = MessageThread::create([
                'tenant_id' => session('tenant_id'),
                'subject' => $subject,
                'type' => count($participantIds) > 2 ? 'group' : 'direct',
            ]);

            foreach ($participantIds as $userId) {
                MessageThreadParticipant::create([
                    'thread_id' => $thread->id,
                    'user_id' => $userId,
                ]);
            }

            return $thread;
        });
    }

    /**
     * थ्रेडमा सन्देश पठाउने
     */
    public function sendMessage($threadId, $senderId, $body, $category, $priority)
    {
        return DB::transaction(function () use ($threadId, $senderId, $body, $category, $priority) {
            $message = Message::create([
                'thread_id' => $threadId,
                'sender_id' => $senderId,
                'body' => $body,
                'category' => $category,
                'priority' => $priority,
            ]);

            // थ्रेडको last_message_at अपडेट गर्ने
            MessageThread::where('id', $threadId)->update([
                'last_message_at' => now()
            ]);

            return $message;
        });
    }

    /**
     * सन्देश पढिसकेको चिन्ह लगाउने
     */
    public function markAsRead($threadId, $userId)
    {
        return MessageThreadParticipant::where('thread_id', $threadId)
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);
    }

    /**
     * प्रयोगकर्ताको लागि थ्रेड अभिलेख गर्ने
     */
    public function archiveThread($threadId, $userId)
    {
        return MessageThreadParticipant::where('thread_id', $threadId)
            ->where('user_id', $userId)
            ->update(['is_archived' => true]);
    }

    /**
     * इनबक्स सूची ल्याउने
     */
    public function getInbox($userId, $filters = [])
    {
        $query = MessageThreadParticipant::with(['thread.messages' => function ($q) {
            $q->latest()->limit(1);
        }])
            ->where('user_id', $userId)
            ->where('is_archived', false);

        if (isset($filters['category'])) {
            $query->whereHas('thread.messages', function ($q) use ($filters) {
                $q->where('category', $filters['category']);
            });
        }

        return $query->paginate(15);
    }
}
