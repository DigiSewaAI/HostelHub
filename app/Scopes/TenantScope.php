<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // यहाँ Auth user को tenant_id कसरी ल्याउने? यो तपाईंको System मा भर पर्छ।
        // मानौं, Owner को profile मा tenant_id छ:
        $tenantId = Auth::user()?->ownerProfile?->tenant_id;
        if ($tenantId) {
            $builder->where('tenant_id', $tenantId);
        }
    }
}
