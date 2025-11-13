<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_TRIALING = 'trialing';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELED = 'canceled';

    protected $fillable = [
        'organization_id',
        'user_id',
        'plan_id',
        'status',
        'expires_at',
        'trial_ends_at',
        'notes',
        'hostel_count',
        'extra_hostels',
        'hostel_limit',
        'room_per_hostel_limit',
        'total_room_limit',
        'student_limit',
        'booking_limit'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'expires_at' => 'datetime',
        'hostel_count' => 'integer',
        'extra_hostels' => 'integer',
        'hostel_limit' => 'integer',
        'room_per_hostel_limit' => 'integer',
        'total_room_limit' => 'integer',
        'student_limit' => 'integer',
        'booking_limit' => 'integer'
    ];

    /**
     * Validation rules for Subscription model
     */
    public static function validationRules($id = null): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'status' => 'required|in:active,trialing,expired,canceled',
            'expires_at' => 'required|date|after:today',
            'trial_ends_at' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:1000',
            'hostel_count' => 'integer|min:0',
            'extra_hostels' => 'integer|min:0',
            'hostel_limit' => 'integer|min:1',
            'room_per_hostel_limit' => 'integer|min:1',
            'total_room_limit' => 'integer|min:1',
            'student_limit' => 'integer|min:1',
            'booking_limit' => 'integer|min:1'
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where('expires_at', '>', now());
    }

    /**
     * Scope for trial subscriptions
     */
    public function scopeTrial($query)
    {
        return $query->where('status', self::STATUS_TRIALING)
            ->where('trial_ends_at', '>', now());
    }

    /**
     * Scope for expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_EXPIRED)
                ->orWhere('expires_at', '<=', now());
        });
    }

    /**
     * Scope for organization subscriptions
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope for user subscriptions
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if subscription is currently active
     */
    public function isActive(): bool
    {
        if ($this->status === self::STATUS_TRIALING && $this->trial_ends_at) {
            return now()->lessThanOrEqualTo($this->trial_ends_at);
        }

        if ($this->status === self::STATUS_ACTIVE && $this->expires_at) {
            return now()->lessThanOrEqualTo($this->expires_at);
        }

        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if subscription is in trial period
     */
    public function isOnTrial(): bool
    {
        return $this->status === self::STATUS_TRIALING &&
            $this->trial_ends_at &&
            now()->lessThanOrEqualTo($this->trial_ends_at);
    }

    /**
     * Check if subscription has expired
     */
    public function isExpired(): bool
    {
        if ($this->status === self::STATUS_EXPIRED) {
            return true;
        }

        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return true;
        }

        return false;
    }

    /**
     * Get days remaining until expiration
     */
    public function getDaysRemaining(): int
    {
        if ($this->isOnTrial() && $this->trial_ends_at) {
            return now()->diffInDays($this->trial_ends_at, false);
        }

        if ($this->expires_at) {
            return now()->diffInDays($this->expires_at, false);
        }

        return 0;
    }

    /**
     * Check if subscription can add more hostels
     */
    public function canAddMoreHostels(): bool
    {
        if (!$this->plan) {
            return false;
        }

        $currentCount = $this->hostel_count ?? 0;
        $maxAllowed = $this->plan->getMaxHostelsWithAddons($this->extra_hostels ?? 0);

        return $currentCount < $maxAllowed;
    }

    /**
     * Get remaining hostel slots
     */
    public function getRemainingHostelSlots(): int
    {
        if (!$this->plan) {
            return 0;
        }

        $currentCount = $this->hostel_count ?? 0;
        $maxAllowed = $this->plan->getMaxHostelsWithAddons($this->extra_hostels ?? 0);

        return max(0, $maxAllowed - $currentCount);
    }

    /**
     * Increment hostel count
     */
    public function incrementHostelCount(): void
    {
        $this->hostel_count = ($this->hostel_count ?? 0) + 1;
        $this->save();
    }

    /**
     * Decrement hostel count  
     */
    public function decrementHostelCount(): void
    {
        $this->hostel_count = max(0, ($this->hostel_count ?? 0) - 1);
        $this->save();
    }

    /**
     * Add extra hostel slots
     */
    public function addExtraHostels($count): void
    {
        $this->extra_hostels = ($this->extra_hostels ?? 0) + $count;
        $this->save();
    }

    /**
     * Check if booking requires manual approval
     */
    public function requiresManualBookingApproval(): bool
    {
        return $this->plan && !$this->plan->allowsBookingAutoApproval();
    }

    /**
     * Relationship with owner (alias for user)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if user can modify this subscription
     */
    public function canBeModifiedBy($user): bool
    {
        return $this->user_id === $user->id ||
            $this->organization->canBeModifiedBy($user) ||
            $user->isAdmin();
    }

    /**
     * Get subscription statistics
     */
    public function getStatisticsAttribute(): array
    {
        return [
            'hostels_used' => $this->hostel_count ?? 0,
            'hostels_remaining' => $this->getRemainingHostelSlots(),
            'days_remaining' => $this->getDaysRemaining(),
            'is_active' => $this->isActive(),
            'is_trial' => $this->isOnTrial(),
            'is_expired' => $this->isExpired(),
        ];
    }

    /**
     * Activate subscription
     */
    public function activate(): bool
    {
        return $this->update([
            'status' => self::STATUS_ACTIVE,
            'expires_at' => now()->addYear()
        ]);
    }

    /**
     * Cancel subscription
     */
    public function cancel(): bool
    {
        return $this->update([
            'status' => self::STATUS_CANCELED
        ]);
    }
}
