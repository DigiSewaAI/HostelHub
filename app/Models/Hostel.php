<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'facilities'
    ];

    protected $casts = [
        'facilities' => 'array'
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function manager(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }
}
