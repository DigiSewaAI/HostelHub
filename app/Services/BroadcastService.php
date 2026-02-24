<?php

namespace App\Services;

use App\Models\BroadcastMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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
        // ✅ Use tenant_id from validated data (set in controller)
        return BroadcastMessage::create([
            'tenant_id' => $data['tenant_id'] ?? null,  // Use passed tenant_id
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

        // Queue मा राख्ने (पछि send हुनेछ)
        dispatch(new SendBroadcastJob($broadcast));

        return $broadcast;
    }

    /**
     * सबै योग्य मालिकहरूलाई ब्रोडकास्ट पठाउने (जब Queue प्रोसेस हुन्छ)
     * Eligibility: सक्रिय र प्रकाशित होस्टल भएका मालिकहरू
     */
    public function sendBroadcastToAllOwners($broadcast)
    {
        // ✅ Fetch only eligible owners within the same tenant
        $eligibleOwners = User::whereHas('hostels', function ($query) use ($broadcast) {
            $query->where('status', 'active')
                ->where('is_published', true);
        })
            ->when($broadcast->tenant_id, function ($query, $tenantId) {
                // If tenant isolation is required, filter by tenant
                $query->whereHas('ownerProfile', function ($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                });
            })
            ->get();

        // Also include admins (optional) if they need to receive broadcasts
        // $admins = User::role('admin')->get();
        // $recipients = $eligibleOwners->merge($admins);

        Notification::send($eligibleOwners, new BroadcastNotification($broadcast));

        $broadcast->update([
            'status'   => 'sent',
            'sent_at'  => now(),
        ]);
    }

    /**
     * कूलडाउन जाँच गर्ने (पहिले नै Policy मा छ)
     * यो method policy मा प्रयोग हुन सक्छ वा सिधै service मा
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
