<?php
// app/Policies/OwnerNetworkProfilePolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\OwnerNetworkProfile;

class OwnerNetworkProfilePolicy
{
    /**
     * Determine if the user can view any network profiles (for directory).
     */
    public function viewAny(User $user)
    {
        // Only owners (or admins) can access network features
        return $user->isAdmin() || $user->isHostelManager();
    }

    /**
     * Determine if the user can view a specific profile (used for "My Profile" page).
     */
    public function view(User $user, OwnerNetworkProfile $profile)
    {
        return $user->isAdmin() || $profile->hostel->owner_id === $user->id;
    }

    /**
     * Owners cannot edit network profiles (auto-synced).
     */
    public function update(User $user, OwnerNetworkProfile $profile)
    {
        return false; // No manual editing allowed
    }
}
