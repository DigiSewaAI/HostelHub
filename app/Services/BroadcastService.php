<?php

namespace App\Services;

use App\Models\BroadcastMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BroadcastNotification;

class BroadcastService
{
    /**
     * ब्रोडकास्ट पेन्डिङ अवस्थामा सिर्जना गर्ने
     */
    public function createBroadcast($senderId, $data)
    {
        return BroadcastMessage::create([
            'tenant_id' => session('tenant_id'),
            'sender_id' => $senderId,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'status' => 'pending',
        ]);
    }

    /**
     * ब्रोडकास्ट स्वीकृत गर्ने र पठाउनको लागि पङ्क्तिमा राख्ने
     */
    public function approveBroadcast($broadcastId, $moderatorId)
    {
        $broadcast = BroadcastMessage::findOrFail($broadcastId);

        $broadcast->update([
            'status' => 'approved',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
        ]);

        // Queue मा राख्ने (पछि send हुनेछ)
        dispatch(new \App\Jobs\SendBroadcastJob($broadcast));

        return $broadcast;
    }

    /**
     * सबै मालिकहरूलाई ब्रोडकास्ट पठाउने (जब Queue प्रोसेस हुन्छ)
     */
    public function sendBroadcastToAllOwners($broadcast)
    {
        $owners = User::whereHas('ownerNetworkProfile')->get();

        Notification::send($owners, new BroadcastNotification($broadcast));

        $broadcast->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * कूलडाउन जाँच गर्ने (पहिले नै Policy मा छ)
     */
    public function checkCooldown($ownerId)
    {
        $last = BroadcastMessage::where('sender_id', $ownerId)
            ->where('status', 'sent')
            ->latest()
            ->first();

        return !$last || $last->sent_at->diffInDays(now()) >= 7;
    }
}
