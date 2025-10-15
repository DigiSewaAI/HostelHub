<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id',
        'role_id',
        'phone',
        'address',
        'payment_verified',
        'student_id',
        'hostel_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'payment_verified' => 'boolean',
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

        return Room::where('hostel_id', $this->hostel_id)
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

        return Room::where('hostel_id', $this->hostel_id)
            ->with('hostel')
            ->orderBy('room_number')
            ->get();
    }
}
