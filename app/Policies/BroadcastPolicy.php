<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BroadcastMessage;
use App\Services\BroadcastService;
use Illuminate\Auth\Access\HandlesAuthorization;

class BroadcastPolicy
{
    use HandlesAuthorization;

    /**
     * के प्रयोगकर्ताले ब्रोडकास्ट सिर्जना गर्न सक्छ?
     */
    public function create(User $user)
    {
        \Log::info('BroadcastPolicy create called', ['user_id' => $user->id]);
        return app(BroadcastService::class)->checkCooldown($user->id);
    }
}
