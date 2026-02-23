<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (auth()->check() && session()->has('tenant_id')) {
                $builder->where('tenant_id', session('tenant_id'));
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && session()->has('tenant_id')) {
                $model->tenant_id = session('tenant_id');
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
