<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hostel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'contact_person',
        'contact_phone',
        'contact_email',
        'description',
        'total_rooms',
        'available_rooms',
        'status',
        'facilities',
        'owner_id', // Added owner_id to fillable
        'manager_id' // Added manager_id to fillable (if not already present)
    ];

    protected $casts = [
        'facilities' => 'array'
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(HostelImage::class);
    }
}
