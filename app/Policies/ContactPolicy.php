<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->can('contacts_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contact $contact)
    {
        // Admin हरूले सबै हेर्न सक्छन्
        if ($user->hasRole('admin')) {
            return true;
        }

        // Hostel Manager हरूले आफ्नो होस्टलका contacts मात्र हेर्न सक्छन्
        if ($user->hasRole('hostel_manager')) {
            return $contact->hostel_id && $contact->hostel->manager_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->can('contacts_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Contact $contact)
    {
        return $user->can('contacts_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contact $contact)
    {
        return $user->can('contacts_delete');
    }
}
