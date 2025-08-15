<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return OrganizationUser::where('user_id', $user->id)->exists();
    }

    public function view(User $user, Organization $organization): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('org_id', $organization->id)
            ->exists();
    }

    public function update(User $user, Organization $organization): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('org_id', $organization->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    public function delete(User $user, Organization $organization): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('org_id', $organization->id)
            ->where('role', 'owner')
            ->exists();
    }
}
