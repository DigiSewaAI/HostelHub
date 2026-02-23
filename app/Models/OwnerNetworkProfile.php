<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class OwnerNetworkProfile extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'business_name',
        'phone',
        'city',
        'bio',
        'services',
        'hostel_size',
        'pricing_category',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'services' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
