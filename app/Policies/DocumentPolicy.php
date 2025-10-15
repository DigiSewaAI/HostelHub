<?php

namespace App\Policies;

use App\Models\StudentDocument;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, StudentDocument $document)
    {
        if ($user->hasRole('admin')) return true;
        return $document->organization_id == $user->organization_id;
    }

    public function download(User $user, StudentDocument $document)
    {
        if ($user->hasRole('admin')) return true;
        return $document->organization_id == $user->organization_id;
    }

    public function delete(User $user, StudentDocument $document)
    {
        // Only hostel manager who uploaded or admin can delete
        if ($user->hasRole('admin')) return true;
        return $document->organization_id == $user->organization_id &&
            $document->uploaded_by == $user->id;
    }

    public function create(User $user)
    {
        return $user->hasRole('hostel_manager');
    }
}
