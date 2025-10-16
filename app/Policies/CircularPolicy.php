<?php

namespace App\Policies;

use App\Models\Circular;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CircularPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'hostel_manager', 'student']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Circular $circular): bool
    {
        // Admin can view all circulars
        if ($user->hasRole('admin')) {
            return true;
        }

        // Hostel manager can view circulars from their organization
        if ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->first();
            return $circular->organization_id === $organization->id;
        }

        // Student can only view circulars sent to them
        if ($user->hasRole('student')) {
            return $circular->recipients()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'hostel_manager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Circular $circular): bool
    {
        // Admin can update any circular
        if ($user->hasRole('admin')) {
            return true;
        }

        // Hostel manager can only update their own circulars in their organization
        if ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->first();
            return $circular->organization_id === $organization->id &&
                $circular->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Circular $circular): bool
    {
        // Admin can delete any circular
        if ($user->hasRole('admin')) {
            return true;
        }

        // Hostel manager can only delete their own circulars in their organization
        if ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->first();
            return $circular->organization_id === $organization->id &&
                $circular->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Circular $circular): bool
    {
        return $this->update($user, $circular);
    }

    /**
     * Determine whether the user can view analytics.
     */
    public function viewAnalytics(User $user, Circular $circular): bool
    {
        return $this->view($user, $circular) &&
            ($user->hasRole('admin') || $user->hasRole('hostel_manager'));
    }

    /**
     * Determine whether the user can manage circular templates.
     */
    public function manageTemplates(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
