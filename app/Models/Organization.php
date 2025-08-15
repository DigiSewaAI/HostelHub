<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_ready',
        'settings'
    ];

    protected $casts = [
        'is_ready' => 'boolean',
        'settings' => 'array'
    ];

    public function hostels(): HasMany
    {
        return $this->hasMany(Hostel::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    public function onboardingProgress(): HasOne
    {
        return $this->hasOne(OnboardingProgress::class);
    }
}
