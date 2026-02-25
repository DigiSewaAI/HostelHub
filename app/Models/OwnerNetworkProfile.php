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
        'trust_level',
        'suspended_at',
    ];

    protected $casts = [
        'auto_snapshot' => 'array',
        'verified_at'   => 'datetime',
        'suspended_at'  => 'datetime',
    ];

    /**
     * Get the hostel that owns this network profile.
     */
    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Check if the hostel is suspended.
     */
    public function isSuspended()
    {
        return $this->trust_level === 'suspended' || !is_null($this->suspended_at);
    }

    /**
     * Check if the hostel is verified (either verified or trusted).
     */
    public function isVerified()
    {
        return $this->trust_level === 'verified' || $this->trust_level === 'trusted';
    }
}
