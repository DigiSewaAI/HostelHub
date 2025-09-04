<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Meal;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->can('meals_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Meal $meal)
    {
        // Admin हरूले सबै हेर्न सक्छन्
        if ($user->hasRole('admin')) {
            return true;
        }

        // Hostel Manager हरूले आफ्नो होस्टलका meals मात्र हेर्न सक्छन्
        if ($user->hasRole('hostel_manager')) {
            return $meal->hostel_id && $meal->hostel->manager_id === $user->id;
        }

        // Student हरूले सबै meals हेर्न सक्छन्
        if ($user->hasRole('student')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->can('meals_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Meal $meal)
    {
        return $user->can('meals_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Meal $meal)
    {
        return $user->can('meals_delete');
    }
}
