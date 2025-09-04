<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Room;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->can('rooms_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Room $room)
    {
        // Admin हरूले सबै हेर्न सक्छन्
        if ($user->hasRole('admin')) {
            return true;
        }

        // Hostel Manager हरूले आफ्नो होस्टलका rooms मात्र हेर्न सक्छन्
        if ($user->hasRole('hostel_manager')) {
            return $room->hostel->manager_id === $user->id;
        }

        // Student हरूले आफ्नै room मात्र हेर्न सक्छन्
        if ($user->hasRole('student')) {
            return $room->students->contains('user_id', $user->id);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->can('rooms_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Room $room)
    {
        return $user->can('rooms_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Room $room)
    {
        return $user->can('rooms_delete');
    }
}
