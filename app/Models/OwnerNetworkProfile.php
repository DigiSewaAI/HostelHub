<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerNetworkProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
        'auto_snapshot',
        'verified_at',
    ];

    protected $casts = [
        'auto_snapshot' => 'array',
        'verified_at'   => 'datetime',
    ];

    /**
     * Get the hostel that owns this network profile.
     */
    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }
}
