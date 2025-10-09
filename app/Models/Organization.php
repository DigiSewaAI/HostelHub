<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_ready',
        'settings'
        // ❌ plan_type र status थप्नु पर्दैन!
    ];

    protected $casts = [
        'is_ready' => 'boolean',
        'settings' => 'array'
    ];

    // ✅ Accessors थपिएका:

    /**
     * Get plan type from subscription
     */
    public function getPlanTypeAttribute()
    {
        return $this->subscription?->plan?->name ?? 'free';
    }

    /**
     * Get status from subscription  
     */
    public function getStatusAttribute()
    {
        return $this->subscription?->status ?? 'inactive';
    }

    /**
     * Check if organization is active
     */
    public function getIsActiveAttribute()
    {
        return $this->subscription?->isActive() ?? false;
    }

    // बाँकी सबै relationships यस्तै रहन्छ:

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'organization_id')->latest();
    }

    // ✅ यो नयाँ relationship थप्नुहोस् जसको कारण error आइरहेको थियो
    public function currentSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'organization_id')->latest();
    }

    public function onboardingProgress(): HasOne
    {
        return $this->hasOne(OnboardingProgress::class);
    }
}
