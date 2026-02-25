<?php

namespace App\Helpers;

use App\Models\User;

class TenantHelper
{
    public static function getTenantId(User $user): ?int
    {
        return $user->ownerProfile?->tenant_id;
    }
}
