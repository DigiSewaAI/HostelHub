<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * के प्रयोगकर्ताले सन्देश हेर्न सक्छ?
     */
    public function view(User $user, Message $message)
    {
        // प्रयोगकर्ता थ्रेडको सहभागी हुनुपर्छ
        return $message->thread->participants()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * के प्रयोगकर्ताले सन्देश सिर्जना गर्न सक्छ?
     */
    public function create(User $user)
    {
        // मालिक प्रोफाइल हुनुपर्छ
        return $user->ownerNetworkProfile !== null;
    }

    /**
     * के प्रयोगकर्ताले सन्देश मेटाउन सक्छ?
     */
    public function delete(User $user, Message $message)
    {
        // आफ्नै सन्देश मात्र मेटाउन पाउने
        return $user->id === $message->sender_id;
    }
}
