<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug', // ✅ slug field थप्नुहोस्
        'price_month',
        'price_year',
        'max_students',
        'max_hostels',
        'max_rooms',
        'features',
        'is_active',
        'sort_order',
        'description',
        'stripe_price_id',
        'stripe_product_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'features' => 'array',
        'price_month' => 'decimal:2',
        'price_year' => 'decimal:2'
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if plan can create more hostels
     */
    public function canCreateMoreHostels($currentHostelsCount): bool
    {
        // Enterprise plan has unlimited hostels
        if ($this->slug === 'enterprise') {
            return true;
        }

        // Starter and Pro plans have limited hostels
        return $currentHostelsCount < $this->max_hostels;
    }

    /**
     * Check if plan can create more students
     */
    public function canCreateMoreStudents($currentStudentsCount): bool
    {
        // Enterprise plan has unlimited students
        if ($this->slug === 'enterprise') {
            return true;
        }

        // Starter and Pro plans have limited students
        return $currentStudentsCount < $this->max_students;
    }

    /**
     * Check if plan can create more rooms
     */
    public function canCreateMoreRooms($currentRoomsCount): bool
    {
        // Enterprise plan has unlimited rooms
        if ($this->slug === 'enterprise') {
            return true;
        }

        // Starter and Pro plans have limited rooms
        return $currentRoomsCount < $this->max_rooms;
    }

    /**
     * Get plan limits description
     */
    public function getLimitsDescription(): string
    {
        $limits = [];

        if ($this->max_hostels === 1) {
            $limits[] = "१ होस्टेल";
        } elseif ($this->max_hostels > 1) {
            $limits[] = "{$this->max_hostels} होस्टेलहरू";
        } else {
            $limits[] = "असीमित होस्टेलहरू";
        }

        if ($this->max_students > 0) {
            $limits[] = "{$this->max_students} विद्यार्थीहरू";
        } else {
            $limits[] = "असीमित विद्यार्थीहरू";
        }

        if ($this->max_rooms > 0) {
            $limits[] = "{$this->max_rooms} कोठाहरू";
        } else {
            $limits[] = "असीमित कोठाहरू";
        }

        return implode(', ', $limits);
    }

    /**
     * Get plan features as array
     */
    public function getFeaturesArray(): array
    {
        if (is_array($this->features)) {
            return $this->features;
        }

        if (is_string($this->features)) {
            $decoded = json_decode($this->features, true);
            return is_array($decoded) ? $decoded : explode(',', $this->features);
        }

        return [];
    }

    /**
     * Check if this is the starter plan
     */
    public function isStarter(): bool
    {
        return $this->slug === 'starter';
    }

    /**
     * Check if this is the pro plan
     */
    public function isPro(): bool
    {
        return $this->slug === 'pro';
    }

    /**
     * Check if this is the enterprise plan
     */
    public function isEnterprise(): bool
    {
        return $this->slug === 'enterprise';
    }

    /**
     * Get display price
     */
    public function getDisplayPrice(): string
    {
        if ($this->price_month == 0) {
            return 'निःशुल्क';
        }

        return 'रु. ' . number_format($this->price_month) . '/महिना';
    }

    /**
     * Scope active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by slug
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get recommended plans (excluding starter)
     */
    public function scopeRecommended($query)
    {
        return $query->where('slug', '!=', 'starter')->active();
    }

    /**
     * Check if plan has a specific feature
     */
    public function hasFeature(string $feature): bool
    {
        $features = $this->getFeaturesArray();
        return in_array($feature, $features);
    }

    /**
     * Check if plan allows advanced booking features
     */
    public function allowsAdvancedBooking(): bool
    {
        return $this->slug === 'pro' || $this->slug === 'enterprise';
    }

    /**
     * Check if plan allows auto-approval for bookings
     */
    public function allowsBookingAutoApproval(): bool
    {
        return $this->slug === 'pro' || $this->slug === 'enterprise';
    }

    /**
     * Check if plan has multi-hostel support
     */
    public function hasMultiHostelSupport(): bool
    {
        return $this->slug === 'enterprise';
    }

    /**
     * Get add-on price for extra hostels
     */
    public function getExtraHostelPrice(): float
    {
        return 1000.00; // रु. १,००० per additional hostel per month
    }

    /**
     * Get maximum allowed hostels including add-ons
     */
    public function getMaxHostelsWithAddons($extraHostels = 0): int
    {
        $baseLimit = $this->max_hostels;

        if ($this->slug === 'enterprise') {
            return $baseLimit + $extraHostels;
        }

        return $baseLimit;
    }
}
