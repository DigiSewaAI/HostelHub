<?php

namespace App\Policies;

use App\Models\Hostel;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HostelPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return OrganizationUser::where('user_id', $user->id)->exists();
    }

    public function view(User $user, Hostel $hostel): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('organization_id', $hostel->organization_id)
            ->exists();
    }

    public function create(User $user, Organization $organization): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('organization_id', $organization->id)
            ->whereIn('role', ['owner', 'admin', 'manager'])
            ->exists();
    }

    public function update(User $user, Hostel $hostel): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('organization_id', $hostel->organization_id)
            ->whereIn('role', ['owner', 'admin', 'manager'])
            ->exists();
    }

    public function delete(User $user, Hostel $hostel): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('organization_id', $hostel->organization_id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }
}
