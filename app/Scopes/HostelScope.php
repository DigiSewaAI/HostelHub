<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class HostelScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if ($user && $user->hasRole('hostel_manager')) {
            // होस्टेल म्यानेजरले आफ्ना होस्टेलहरू मात्र देख्न सक्छन्
            $builder->where('owner_id', $user->id);
        }

        // संस्था स्कोप पनि लागू गर्ने
        if (session('current_organization_id')) {
            $builder->where('organization_id', session('current_organization_id'));
        }
    }
}