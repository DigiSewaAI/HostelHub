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
        'slug',
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
        'owner_id',
        'manager_id',
        'organization_id'
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

    // ✅ Organization सँगको relationship थप्नुहोस्
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    // ✅ Students सँगको relationship थप्नुहोस्
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    // ✅ Meal menus सँगको relationship थप्नुहोस्
    public function mealMenus(): HasMany
    {
        return $this->hasMany(MealMenu::class);
    }

    // ✅ Scope for active hostels
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ✅ Scope for inactive hostels
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // ✅ Calculate occupancy rate
    public function getOccupancyRateAttribute()
    {
        if ($this->total_rooms == 0) {
            return 0;
        }

        $occupied = $this->total_rooms - $this->available_rooms;
        return round(($occupied / $this->total_rooms) * 100, 2);
    }

    // ✅ Check if hostel has available rooms
    public function getHasAvailableRoomsAttribute()
    {
        return $this->available_rooms > 0;
    }

    // ✅ FIX: Add method to update room counts from relationships
    public function updateRoomCounts()
    {
        $this->update([
            'total_rooms' => $this->rooms()->count(),
            'available_rooms' => $this->rooms()->where('status', 'available')->count()
        ]);
    }

    // ✅ Get dynamic room statistics
    public function getRoomStatisticsAttribute()
    {
        return [
            'total' => $this->rooms()->count(),
            'available' => $this->rooms()->where('status', 'available')->count(),
            'occupied' => $this->rooms()->where('status', 'occupied')->count(),
            'maintenance' => $this->rooms()->where('status', 'maintenance')->count(),
        ];
    }

    // ✅ Get dynamic student count
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    // ✅ Get Nepali status
    public function getNepaliStatusAttribute()
    {
        $statuses = [
            'active' => 'सक्रिय',
            'inactive' => 'निष्क्रिय',
            'maintenance' => 'मर्मतमा'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // ✅ Check if hostel can be deleted (no rooms or students)
    public function getCanBeDeletedAttribute()
    {
        return $this->rooms()->count() === 0 && $this->students()->count() === 0;
    }

    /**
     * Get the subscription that owns the hostel through organization
     */
    public function subscription()
    {
        if (!$this->organization) {
            return null;
        }

        // Load the organization with subscription if not already loaded
        if (!$this->relationLoaded('organization')) {
            $this->load('organization.currentSubscription');
        }

        return $this->organization->currentSubscription ?? null;
    }

    /**
     * Check if hostel can be created under current subscription
     */
    public function canBeCreated(): bool
    {
        $subscription = $this->subscription();

        if (!$subscription) {
            return false;
        }

        return $subscription->canAddMoreHostels();
    }

    /**
     * Check if hostel can add more rooms under subscription limits
     */
    public function canAddMoreRooms($newRoomsCount = 1): bool
    {
        $subscription = $this->subscription();

        if (!$subscription || !$subscription->plan) {
            return false;
        }

        // Get current total rooms count for this organization
        $currentOrgRooms = Room::whereHas('hostel', function ($query) {
            $query->where('organization_id', $this->organization_id);
        })->count();

        $proposedTotal = $currentOrgRooms + $newRoomsCount;

        return $subscription->plan->canCreateMoreRooms($proposedTotal);
    }

    /**
     * Check if hostel can add more students under subscription limits
     */
    public function canAddMoreStudents($newStudentsCount = 1): bool
    {
        $subscription = $this->subscription();

        if (!$subscription || !$subscription->plan) {
            return false;
        }

        // Get current total students count for this organization
        $currentOrgStudents = Student::where('organization_id', $this->organization_id)->count();

        $proposedTotal = $currentOrgStudents + $newStudentsCount;

        return $subscription->plan->canCreateMoreStudents($proposedTotal);
    }

    /**
     * Get subscription plan name for this hostel
     */
    public function getSubscriptionPlanName(): string
    {
        $subscription = $this->subscription();

        if (!$subscription || !$subscription->plan) {
            return 'कुनै सदस्यता छैन';
        }

        return $subscription->plan->name;
    }

    /**
     * Check if hostel has advanced booking features
     */
    public function hasAdvancedBooking(): bool
    {
        $subscription = $this->subscription();

        if (!$subscription || !$subscription->plan) {
            return false;
        }

        return $subscription->plan->allowsAdvancedBooking();
    }

    /**
     * Check if hostel has booking auto-approval
     */
    public function hasBookingAutoApproval(): bool
    {
        $subscription = $this->subscription();

        if (!$subscription || !$subscription->plan) {
            return false;
        }

        return $subscription->plan->allowsBookingAutoApproval();
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Update room counts when hostel is loaded (for existing data)
        static::retrieved(function ($hostel) {
            // Only update if counts are outdated (optional - can be heavy on performance)
            // $hostel->updateRoomCounts();
        });

        // Update slug when name is changed
        static::saving(function ($hostel) {
            if ($hostel->isDirty('name') && !$hostel->isDirty('slug')) {
                $hostel->slug = \Illuminate\Support\Str::slug($hostel->name);
            }
        });
    }
}
