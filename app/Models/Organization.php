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

    /**
     * Validation rules for Organization model
     */
    public static function validationRules($id = null): array
    {
        return [
            'name' => 'required|string|max:255|unique:organizations,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:organizations,slug,' . $id,
            'is_ready' => 'boolean',
            'settings' => 'nullable|array'
        ];
    }

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

    /**
     * Scope for active organizations
     */
    public function scopeActive($query)
    {
        return $query->whereHas('subscription', function ($q) {
            $q->where('status', 'active');
        });
    }

    /**
     * Scope for user's organizations
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Scope for ready organizations
     */
    public function scopeReady($query)
    {
        return $query->where('is_ready', true);
    }

    /**
     * Get organization statistics
     */
    public function getStatisticsAttribute(): array
    {
        return [
            'hostels_count' => $this->hostels()->count(),
            'students_count' => $this->students()->count(),
            'rooms_count' => $this->rooms()->count(),
            'users_count' => $this->users()->count(),
            'galleries_count' => $this->galleries()->count(),
        ];
    }

    /**
     * Check if organization can be deleted
     */
    public function getCanBeDeletedAttribute(): bool
    {
        return $this->hostels()->count() === 0 &&
            $this->students()->count() === 0 &&
            $this->users()->count() <= 1; // Only the creator
    }

    /**
     * Check if user can modify this organization
     */
    public function canBeModifiedBy($user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if user is owner of this organization
     */
    public function isOwnedBy($user): bool
    {
        return $this->users()->where('user_id', $user->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from name
        static::saving(function ($organization) {
            if ($organization->isDirty('name') && !$organization->isDirty('slug')) {
                $organization->slug = \Illuminate\Support\Str::slug($organization->name);
            }
        });
    }
}
