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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    // 🔥 CRITICAL: Explicitly set central DB connection
    protected $connection = 'mysql';

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


    /**
     * ✅ NEW: Boot method for auto-linking guest bookings
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            try {
                Log::info('Auto-linking guest bookings for new user', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);

                // Find guest bookings with matching email
                $guestBookings = Booking::where('guest_email', $user->email)
                    ->where('is_guest_booking', true)
                    ->whereNull('user_id')
                    ->get();

                Log::info('Found guest bookings to link', [
                    'count' => $guestBookings->count(),
                    'user_email' => $user->email
                ]);

                foreach ($guestBookings as $booking) {
                    try {
                        // Update booking to attach to user
                        $booking->update([
                            'user_id' => $user->id,
                            'is_guest_booking' => false,
                            'email' => $user->email
                        ]);

                        Log::info('Successfully linked guest booking to user', [
                            'booking_id' => $booking->id,
                            'user_id' => $user->id
                        ]);

                        // If booking is approved, create student record
                        if ($booking->isApproved()) {
                            try {
                                Student::createFromBooking($booking, $user);
                                Log::info('Successfully created student record from approved booking', [
                                    'booking_id' => $booking->id,
                                    'user_id' => $user->id
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Failed to create student record from booking', [
                                    'booking_id' => $booking->id,
                                    'user_id' => $user->id,
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to link guest booking', [
                            'booking_id' => $booking->id,
                            'user_id' => $user->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                Log::info('Completed auto-linking guest bookings for user', [
                    'user_id' => $user->id,
                    'linked_count' => $guestBookings->count()
                ]);
            } catch (\Exception $e) {
                Log::error('Auto-linking guest bookings failed', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'error' => $e->getMessage()
                ]);
            }
        });
    }

    /**
     * Validation rules for User model
     */
    public static function validationRules($id = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'booking_alerts' => 'boolean',
            'payment_alerts' => 'boolean',
            'role_id' => 'nullable|integer|exists:roles,id',
            'student_id' => 'nullable|exists:students,id',
            'payment_verified' => 'boolean',
            'stripe_id' => 'nullable|string|max:255',
            'pm_type' => 'nullable|string|max:50',
            'pm_last_four' => 'nullable|string|max:4',
            'trial_ends_at' => 'nullable|date',
            'organization_id' => 'nullable|exists:organizations,id',
            'hostel_id' => 'nullable|exists:hostels,id'
        ];
    }

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
     * 🔥 PERMANENT FIX: Student relationship (कुनै पनि foreign key नाममा काम गर्ने)
     */
    public function student(): HasOne
    {
        // Try multiple possible foreign key names
        if (Schema::hasColumn('students', 'user_id')) {
            return $this->hasOne(Student::class, 'user_id', 'id');
        } elseif (Schema::hasColumn('students', 'user_id')) {
            return $this->hasOne(Student::class, 'user_id', 'id');
        } elseif (Schema::hasColumn('students', 'users_id')) {
            return $this->hasOne(Student::class, 'users_id', 'id');
        } else {
            // Emergency fallback - email द्वारा जोड्ने
            return new HasOne(
                (new Student)->newQuery(),
                $this,
                'email',
                'id'
            );
        }
    }

    /**
     * 🔥 ULTIMATE FIX: Student पाउने dynamic method
     */
    public function getStudent()
    {
        // 1. Relationship बाट प्रयास गर्ने
        if ($this->relationLoaded('student') && $this->student) {
            return $this->student;
        }

        // 2. Direct query - user_id द्वारा
        $student = \App\Models\Student::where('user_id', $this->id)->first();
        if ($student) {
            $this->setRelation('student', $student);
            return $student;
        }

        // 3. Email द्वारा प्रयास गर्ने
        $student = \App\Models\Student::where('email', $this->email)->first();
        if ($student) {
            // Auto-fix: student record मा user_id update गर्ने
            if (empty($student->user_id)) {
                $student->user_id = $this->id;
                $student->save();
            }
            $this->setRelation('student', $student);
            return $student;
        }

        // 4. Name द्वारा प्रयास गर्ने
        $student = \App\Models\Student::where('name', $this->name)->first();
        if ($student) {
            // Auto-fix: student record मा user_id update गर्ने
            if (empty($student->user_id)) {
                $student->user_id = $this->id;
                $student->save();
            }
            $this->setRelation('student', $student);
            return $student;
        }

        return null;
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
     * 🔥 FIX: Add missing circularRecipients relationship
     * This was causing the error in dashboard
     */
    public function circularRecipients(): HasMany
    {
        return $this->hasMany(CircularRecipient::class);
    }

    /**
     * 🔥 NEW: Get circulars where user is recipient
     */
    public function circulars()
    {
        return $this->belongsToMany(Circular::class, 'circular_recipients', 'user_id', 'circular_id')
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }

    /**
     * 🔥 NEW: Get unread circulars count
     */
    public function getUnreadCircularsCountAttribute()
    {
        return $this->circularRecipients()->where('is_read', false)->count();
    }

    /**
     * 🔥 NEW: Get read circulars count
     */
    public function getReadCircularsCountAttribute()
    {
        return $this->circularRecipients()->where('is_read', true)->count();
    }

    /**
     * 🔥 NEW: Get total circulars count
     */
    public function getTotalCircularsCountAttribute()
    {
        return $this->circularRecipients()->count();
    }

    /**
     * 🔥 NEW: Get recent circulars for user
     */
    public function getRecentCirculars($limit = 5)
    {
        return $this->circulars()
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->with(['creator', 'organization'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * 🔥 NEW: Get urgent circulars for user
     */
    public function getUrgentCirculars($limit = 3)
    {
        return $this->circulars()
            ->where('status', 'published')
            ->where('priority', 'urgent')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->with(['creator', 'organization'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * 🔥 NEW: Mark all circulars as read for user
     */
    public function markAllCircularsAsRead()
    {
        return $this->circularRecipients()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * 🔥 NEW: Mark specific circular as read
     */
    public function markCircularAsRead($circularId)
    {
        return $this->circularRecipients()
            ->where('circular_id', $circularId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * 🔥 NEW: Check if user has unread circulars
     */
    public function hasUnreadCirculars(): bool
    {
        return $this->unread_circulars_count > 0;
    }

    /**
     * 🔥 NEW: Get circular read status
     */
    public function getCircularReadStatus($circularId): bool
    {
        $recipient = $this->circularRecipients()
            ->where('circular_id', $circularId)
            ->first();

        return $recipient ? $recipient->is_read : false;
    }

    /**
     * Scope for admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role_id', 1)->orWhereHas('roles', function ($q) {
            $q->where('name', 'admin');
        });
    }

    /**
     * Scope for hostel managers
     */
    public function scopeHostelManagers($query)
    {
        return $query->where('role_id', 2)->orWhereHas('roles', function ($q) {
            $q->where('name', 'hostel_manager');
        });
    }

    /**
     * Scope for students
     */
    public function scopeStudents($query)
    {
        return $query->where('role_id', 3)->orWhereHas('roles', function ($q) {
            $q->where('name', 'student');
        });
    }

    /**
     * Scope for organization users
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId)
            ->orWhereHas('organizations', function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            });
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
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

    /**
     * Get user statistics
     */
    public function getStatisticsAttribute(): array
    {
        return [
            'hostels_count' => $this->hostels()->count(),
            'bookings_count' => $this->bookings()->count(),
            'approved_bookings_count' => $this->approvedBookings()->count(),
            'subscriptions_count' => $this->subscriptions()->count(),
            'circulars_count' => $this->total_circulars_count,
            'unread_circulars_count' => $this->unread_circulars_count,
        ];
    }

    /**
     * Check if user can be modified by another user
     */
    public function canBeModifiedBy($user): bool
    {
        // Users can modify themselves
        if ($this->id === $user->id) {
            return true;
        }

        // Admins can modify any user
        if ($user->isAdmin()) {
            return true;
        }

        // Organization owners can modify users in their organization
        if ($this->organization_id && $this->organization_id === $user->organization_id) {
            return $user->organization->isOwnedBy($user);
        }

        return false;
    }

    /**
     * Check if user can be deleted
     */
    public function getCanBeDeletedAttribute(): bool
    {
        return $this->hostels()->count() === 0 &&
            $this->bookings()->count() === 0 &&
            $this->approvedBookings()->count() === 0 &&
            $this->subscriptions()->count() === 0;
    }

    /**
     * Get user's primary role
     */
    public function getPrimaryRoleAttribute(): string
    {
        if ($this->isAdmin()) return 'admin';
        if ($this->isHostelManager()) return 'hostel_manager';
        if ($this->isStudent()) return 'student';
        return 'user';
    }

    /**
     * Get user's primary role in Nepali
     */
    public function getPrimaryRoleNepaliAttribute(): string
    {
        $roles = [
            'admin' => 'प्रशासक',
            'hostel_manager' => 'होस्टेल प्रबन्धक',
            'student' => 'विद्यार्थी',
            'user' => 'प्रयोगकर्ता'
        ];

        return $roles[$this->primary_role] ?? $this->primary_role;
    }

    /**
     * 🔥 NEW: Check if user can receive circulars
     */
    public function canReceiveCirculars(): bool
    {
        // All active users with verified email can receive circulars
        return !is_null($this->email_verified_at) && $this->hasRole(['student', 'hostel_manager', 'admin']);
    }

    /**
     * 🔥 NEW: Get circular notification preferences
     */
    public function getCircularNotificationPreferences(): array
    {
        return [
            'email' => $this->receivesEmailNotifications(),
            'sms' => $this->receivesSmsNotifications(),
            'in_app' => true, // Always true for circulars
        ];
    }
}
