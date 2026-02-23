<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BroadcastMessage;
use Illuminate\Auth\Access\HandlesAuthorization;

class BroadcastPolicy
{
    use HandlesAuthorization;

    /**
     * के प्रयोगकर्ताले ब्रोडकास्ट सिर्जना गर्न सक्छ?
     */
    public function create(User $user)
    {
        // कूलडाउन जाँच: पछिल्लो ब्रोडकास्ट पठाएको कम्तीमा ७ दिन भयो?
        $last = BroadcastMessage::where('sender_id', $user->id)
            ->where('status', 'sent')
            ->latest()
            ->first();

        return !$last || $last->sent_at->diffInDays(now()) >= 7;
    }
}
