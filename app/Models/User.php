<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'email_notifications',
        'sms_notifications',
        'booking_alerts',
        'payment_alerts',
        'role_id',
        'student_id',
        'payment_verified',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'organization_id',
        'hostel_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'booking_alerts' => 'boolean',
        'payment_alerts' => 'boolean',
        'payment_verified' => 'boolean',
        'trial_ends_at' => 'datetime'
    ];

    // ✅ FIXED: Organization relationship with null safety
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id')->withDefault([
            'name' => 'No Organization',
            'slug' => 'no-organization',
        ]);
    }

    // ✅ Role helper methods for easier checks
    public function isAdmin(): bool
    {
        return $this->role_id === 1 || $this->hasRole('admin');
    }

    public function isHostelManager(): bool
    {
        return $this->role_id === 2 || $this->hasRole('hostel_manager');
    }

    public function isStudent(): bool
    {
        return $this->role_id === 3 || $this->hasRole('student');
    }

    // ✅ FIXED: isOwner is same as isHostelManager in your system
    public function isOwner(): bool
    {
        return $this->isHostelManager();
    }

    // ✅ Safe organization access
    public function getOrganizationNameAttribute(): string
    {
        return optional($this->organization)->name ?? 'No Organization';
    }

    // ✅ Check if user has organization
    public function hasOrganization(): bool
    {
        return !is_null($this->organization_id);
    }

    // Many-to-Many relationship with organizations through pivot table
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the student profile associated with the user.
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    /**
     * Get all hostels owned by the user.
     */
    public function hostels(): HasMany
    {
        return $this->hasMany(Hostel::class, 'owner_id');
    }

    /**
     * 🔥 CRITICAL: Get the hostel that the user is currently managing (for owners/hostel_managers)
     */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class, 'hostel_id');
    }

    /**
     * Get all bookings made by the user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all bookings approved by the user.
     */
    public function approvedBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'approved_by');
    }

    /**
     * Get the role name of the user.
     */
    public function getRoleName(): string
    {
        return $this->roles->first()?->name ?? 'N/A';
    }

    // User.php मा यो method थप्नुहोस्
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * 🔥 CRITICAL: Auto-fix hostel_id if it's null but user owns a hostel
     * This acts as a safety net for existing users
     */
    public function getHostelIdAttribute($value)
    {
        // ✅ FIXED: Remove duplicate check - use only isHostelManager()
        if (is_null($value) && $this->isHostelManager()) {
            $ownedHostel = $this->hostels()->first();

            if ($ownedHostel) {
                Log::info('Auto-fixing hostel_id for user', [
                    'user_id' => $this->id,
                    'user_name' => $this->name,
                    'hostel_id' => $ownedHostel->id,
                    'hostel_name' => $ownedHostel->name
                ]);

                $this->hostel_id = $ownedHostel->id;
                $this->save();

                return $ownedHostel->id;
            }
        }

        return $value;
    }

    /**
     * 🔥 CRITICAL: Check if user has a valid hostel_id set
     */
    public function hasHostel(): bool
    {
        return !is_null($this->hostel_id) && !empty($this->hostel_id);
    }

    /**
     * 🔥 CRITICAL: Get the user's current hostel with safety checks
     */
    public function getCurrentHostel()
    {
        if (!$this->hasHostel()) {
            // Try to auto-fix if possible
            $this->getHostelIdAttribute($this->hostel_id);
        }

        return $this->hostel;
    }

    /**
     * 🔥 CRITICAL: Ensure user can manage the given hostel
     */
    public function canManageHostel($hostelId): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (!$this->hasHostel()) {
            return false;
        }

        return $this->hostel_id == $hostelId;
    }

    /**
     * 🔥 CRITICAL: Get available rooms for the user's hostel
     */
    public function getAvailableRooms()
    {
        if (!$this->hasHostel()) {
            return collect();
        }

        return \App\Models\Room::where('hostel_id', $this->hostel_id)
            ->orderBy('room_number')
            ->get();
    }

    /**
     * 🔥 CRITICAL: Get all rooms for the user's hostel (including occupied ones)
     */
    public function getAllRooms()
    {
        if (!$this->hasHostel()) {
            return collect();
        }

        return \App\Models\Room::where('hostel_id', $this->hostel_id)
            ->with('hostel')
            ->orderBy('room_number')
            ->get();
    }

    /**
     * Check if user has email notifications enabled
     */
    public function receivesEmailNotifications(): bool
    {
        return $this->email_notifications ?? true;
    }

    /**
     * Check if user has SMS notifications enabled
     */
    public function receivesSmsNotifications(): bool
    {
        return $this->sms_notifications ?? false;
    }

    /**
     * Check if user has booking alerts enabled
     */
    public function receivesBookingAlerts(): bool
    {
        return $this->booking_alerts ?? true;
    }

    /**
     * Check if user has payment alerts enabled
     */
    public function receivesPaymentAlerts(): bool
    {
        return $this->payment_alerts ?? true;
    }
}
