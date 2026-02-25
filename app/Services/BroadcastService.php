<?php

namespace App\Services;

use App\Models\BroadcastMessage;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BroadcastNotification;
use App\Jobs\SendBroadcastJob;

class BroadcastService
{
    /**
     * ब्रोडकास्ट पेन्डिङ अवस्थामा सिर्जना गर्ने
     */
    public function createBroadcast($senderId, $data)
    {
        return BroadcastMessage::create([
            'tenant_id' => $data['tenant_id'] ?? null,
            'sender_id' => $senderId,
            'subject'   => $data['subject'],
            'body'      => $data['body'],
            'status'    => 'pending',
        ]);
    }

    /**
     * ब्रोडकास्ट स्वीकृत गर्ने र पठाउनको लागि पङ्क्तिमा राख्ने
     */
    public function approveBroadcast($broadcastId, $moderatorId)
    {
        $broadcast = BroadcastMessage::findOrFail($broadcastId);

        $broadcast->update([
            'status'       => 'approved',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
        ]);

        dispatch(new SendBroadcastJob($broadcast));

        return $broadcast;
    }

    /**
     * सबै योग्य मालिकहरूलाई ब्रोडकास्ट पठाउने (Queue प्रोसेस हुँदा)
     */
    public function sendBroadcastToAllOwners($broadcast)
    {
        $eligibleOwners = User::whereHas('hostels', function ($query) use ($broadcast) {
            $query->where('status', 'active')
                ->where('is_published', true);
        })
            ->when($broadcast->tenant_id, function ($query, $tenantId) {
                $query->whereHas('ownerProfile', function ($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                });
            })
            ->get();

        Notification::send($eligibleOwners, new BroadcastNotification($broadcast));

        $broadcast->update([
            'status'   => 'sent',
            'sent_at'  => now(),
        ]);
    }

    /**
     * कूलडाउन जाँच गर्ने (अब 'approved' स्टेटस पनि गणना गर्छ)
     */
    public function checkCooldown($ownerId)
    {
        $last = BroadcastMessage::where('sender_id', $ownerId)
            ->whereIn('status', ['approved', 'sent'])
            ->latest()
            ->first();

        return !$last || $last->sent_at->diffInDays(now()) >= 7;
    }
}
