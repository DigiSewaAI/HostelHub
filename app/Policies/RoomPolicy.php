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
     * सबै rooms को सूची हेर्न अनुमति छ कि छैन
     */
    public function viewAny(User $user): bool
    {
        return $user->can('rooms_access');
    }

    /**
     * Determine whether the user can view the specific room.
     * कुनै एक कोठाको विवरण हेर्न अनुमति
     */
    public function view(User $user, Room $room): bool
    {
        // Admin हरूले सबै हेर्न सक्छन्
        if ($user->hasRole('admin')) {
            return true;
        }

        // Owner हरूले आफ्नो होस्टलका rooms मात्र हेर्न सक्छन्
        if ($user->hasRole('owner')) {
            return $room->hostel && $room->hostel->owner_id === $user->id;
        }

        // Hostel Manager हरूले आफ्नो तोकिएको होस्टलका rooms मात्र हेर्न सक्छन्
        if ($user->hasRole('hostel_manager')) {
            return $room->hostel_id === $user->hostel_id;
        }

        // Student हरूले आफू बसेको room मात्र हेर्न सक्छन्
        if ($user->hasRole('student')) {
            return $room->students()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create rooms.
     * नयाँ कोठा सिर्जना गर्न अनुमति
     */
    public function create(User $user): bool
    {
        return $user->can('rooms_create');
    }

    /**
     * Determine whether the user can update the room.
     * कोठा सम्पादन गर्न अनुमति (permission + ownership)
     */
    public function update(User $user, Room $room): bool
    {
        // पहिले permission check
        if (!$user->can('rooms_edit')) {
            return false;
        }

        // Admin हरूलाई सधैं अनुमति
        if ($user->hasRole('admin')) {
            return true;
        }

        // Owner: आफ्नै hostel को room हो भने मात्र
        if ($user->hasRole('owner')) {
            return $room->hostel && $room->hostel->owner_id === $user->id;
        }

        // Hostel Manager: आफ्नो assigned hostel को room हो भने मात्र
        if ($user->hasRole('hostel_manager')) {
            return $room->hostel_id === $user->hostel_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the room.
     * कोठा मेटाउन अनुमति (permission + ownership)
     */
    public function delete(User $user, Room $room): bool
    {
        // पहिले permission check
        if (!$user->can('rooms_delete')) {
            return false;
        }

        // Admin हरूलाई सधैं अनुमति
        if ($user->hasRole('admin')) {
            return true;
        }

        // Owner: आफ्नै hostel को room हो भने मात्र
        if ($user->hasRole('owner')) {
            return $room->hostel && $room->hostel->owner_id === $user->id;
        }

        // Hostel Manager: आफ्नो assigned hostel को room हो भने मात्र
        if ($user->hasRole('hostel_manager')) {
            return $room->hostel_id === $user->hostel_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the room.
     * (यदि soft delete प्रयोग गरिन्छ भने)
     */
    public function restore(User $user, Room $room): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the room.
     */
    public function forceDelete(User $user, Room $room): bool
    {
        return $user->hasRole('admin');
    }
}
