<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return OrganizationUser::where('user_id', $user->id)->exists();
    }

    public function view(User $user, Student $student): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('organization_id', $student->organization_id)
            ->exists();
    }

    public function create(User $user, Organization $organization): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('organization_id', $organization->id)
            ->whereIn('role', ['owner', 'admin', 'manager'])
            ->exists();
    }

    public function update(User $user, Student $student): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('organization_id', $student->organization_id)
            ->whereIn('role', ['owner', 'admin', 'manager'])
            ->exists();
    }

    public function delete(User $user, Student $student): bool
    {
        return OrganizationUser::where('user_id', $user->id)
            ->where('organization_id', $student->organization_id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }
}
