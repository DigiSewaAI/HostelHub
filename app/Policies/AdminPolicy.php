<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Bypass all checks for admin users
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the admin dashboard.
     */
    public function viewAdminDashboard(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can access admin features.
     */
    public function accessAdmin(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
