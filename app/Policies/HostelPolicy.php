<?php

namespace App\Policies;

use App\Models\Hostel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class HostelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Owner ले आफ्नो organization का होस्टल मात्र हेर्न पाउँछ,
        // तर OrganizationScope ले पहिले नै filter गरिदिन्छ, त्यसैले यहाँ सबै owner लाई अनुमति दिन सकिन्छ।
        // यदि role owner हो भने true, नभए false (सुपर एडमिनलाई पनि दिन सकिन्छ)
        return $user->hasRole('owner') || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can view a specific hostel.
     */
    public function view(User $user, Hostel $hostel): bool
    {
        // यदि user को organization ID र hostel को organization_id मेल खान्छ भने मात्र हेर्न दिने
        // तर session मा current_organization_id छ भने त्यो पनि मिलाउन सकिन्छ
        $orgId = Session::get('current_organization_id') ?? $user->organizations()->first()?->id;

        if (!$orgId) {
            return false;
        }

        return $hostel->organization_id === $orgId;
    }

    /**
     * Determine whether the user can create hostels.
     * यहाँ प्लानको सीमा जाँच गर्ने।
     */
    public function create(User $user): bool
    {
        // Owner को संस्था लिने
        $organization = $user->organizations()->first();
        if (!$organization) {
            Log::warning('HostelPolicy::create - User has no organization', ['user_id' => $user->id]);
            return false;
        }

        // प्लान अनुसार अधिकतम होस्टल संख्या
        $maxHostels = match ($organization->plan) {
            'basic'     => 1,
            'pro'       => 1,
            'enterprise' => 5,
            default     => 1,
        };

        // हाल यस संस्थामा कति होस्टल छन्?
        $currentHostels = $organization->hostels()->count();

        $canCreate = $currentHostels < $maxHostels;

        Log::info('HostelPolicy::create check', [
            'organization_id' => $organization->id,
            'plan' => $organization->plan,
            'current_hostels' => $currentHostels,
            'max_hostels' => $maxHostels,
            'can_create' => $canCreate
        ]);

        return $canCreate;
    }

    /**
     * Determine whether the user can update a hostel.
     */
    public function update(User $user, Hostel $hostel): bool
    {
        $orgId = Session::get('current_organization_id') ?? $user->organizations()->first()?->id;

        if (!$orgId) {
            return false;
        }

        return $hostel->organization_id === $orgId;
    }

    /**
     * Determine whether the user can delete a hostel.
     */
    public function delete(User $user, Hostel $hostel): bool
    {
        // delete पनि update जस्तै organization मिलेको हुनुपर्छ
        return $this->update($user, $hostel);
    }

    /**
     * Determine whether the user can restore a hostel.
     */
    public function restore(User $user, Hostel $hostel): bool
    {
        // यदि soft delete प्रयोग गर्नुहुन्छ भने यो पनि organization मिलाएर जाँच गर्नुहोस्
        return $this->update($user, $hostel);
    }

    /**
     * Determine whether the user can permanently delete a hostel.
     */
    public function forceDelete(User $user, Hostel $hostel): bool
    {
        // force delete पनि organization मिलेको हुनुपर्छ
        return $this->update($user, $hostel);
    }
}
