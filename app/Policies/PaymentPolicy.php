<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->can('payments_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payment $payment)
    {
        // Admin हरूले सबै हेर्न सक्छन्
        if ($user->hasRole('admin')) {
            return true;
        }

        // Hostel Manager हरूले आफ्नो होस्टलका payments मात्र हेर्न सक्छन्
        if ($user->hasRole('hostel_manager')) {
            return $payment->hostel->manager_id === $user->id;
        }

        // Student हरूले आफ्नै payments मात्र हेर्न सक्छन्
        if ($user->hasRole('student')) {
            return $payment->student->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->can('payments_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment)
    {
        return $user->can('payments_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment)
    {
        return $user->can('payments_delete');
    }

    /**
     * Determine whether the user can export payments.
     */
    public function export(User $user)
    {
        return $user->can('payments_export');
    }

    /**
     * Determine whether the user can view payment reports.
     */
    public function report(User $user)
    {
        return $user->can('payments_report');
    }
}
