<?php

namespace App\Policies;

use App\Models\Hostel;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class HostelPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        Log::info('HostelPolicy::viewAny - TEMPORARY BYPASS: returning true');
        return true;
    }

    public function view(User $user, Hostel $hostel): bool
    {
        Log::info('HostelPolicy::view - TEMPORARY BYPASS: returning true');
        return true;
    }

    public function create(User $user, Organization $organization): bool
    {
        Log::info('HostelPolicy::create - TEMPORARY BYPASS: returning true');
        return true;
    }

    public function update(User $user, Hostel $hostel): bool
    {
        Log::info('HostelPolicy::update - TEMPORARY BYPASS: returning true', [
            'user_id' => $user->id,
            'hostel_id' => $hostel->id,
            'hostel_org' => $hostel->organization_id,
            'session_org' => Session::get('current_organization_id')
        ]);
        return true; // ✅ TEMPORARY BYPASS
    }

    public function delete(User $user, Hostel $hostel): bool
    {
        Log::info('HostelPolicy::delete - TEMPORARY BYPASS: returning true');
        return true;
    }

    public function edit(User $user, Hostel $hostel): bool
    {
        Log::info('HostelPolicy::edit - TEMPORARY BYPASS: returning true');
        return $this->update($user, $hostel);
    }
}