<?php

namespace App\Policies;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GalleryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Gallery $gallery): bool
    {
        // Owners can only view their own hostel's galleries
        if ($user->hasRole('owner')) {
            return $user->hostel->id === $gallery->hostel_id;
        }

        // Admin can view all galleries
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Gallery $gallery): bool
    {
        // Owners can only update their own hostel's galleries
        if ($user->hasRole('owner')) {
            return $user->hostel->id === $gallery->hostel_id;
        }

        // Admin can update all galleries
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Gallery $gallery): bool
    {
        // Owners can only delete their own hostel's galleries
        if ($user->hasRole('owner')) {
            return $user->hostel->id === $gallery->hostel_id;
        }

        // Admin can delete all galleries
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Gallery $gallery): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Gallery $gallery): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can toggle featured status.
     */
    public function toggleFeatured(User $user, Gallery $gallery): bool
    {
        // Owners can only toggle their own hostel's galleries
        if ($user->hasRole('owner')) {
            return $user->hostel->id === $gallery->hostel_id;
        }

        // Admin can toggle all galleries
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can toggle active status.
     */
    public function toggleActive(User $user, Gallery $gallery): bool
    {
        // Owners can only toggle their own hostel's galleries
        if ($user->hasRole('owner')) {
            return $user->hostel->id === $gallery->hostel_id;
        }

        // Admin can toggle all galleries
        return $user->hasRole('admin');
    }
}
