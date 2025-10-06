<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'user_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'ends_at',
        'notes',
        'hostel_count', // ✅ नयाँ field थपियो
        'extra_hostels' // ✅ नयाँ field थपियो
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'renews_at' => 'datetime',
        'hostel_count' => 'integer',
        'extra_hostels' => 'integer'
    ];

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

    public function isActive(): bool
    {
        return $this->status === 'active' || ($this->status === 'trial' && now()->lessThan($this->trial_ends_at));
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
}
