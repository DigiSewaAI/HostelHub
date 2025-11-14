<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Circular extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'created_by',
        'hostel_id',
        'title',
        'content',
        'priority',
        'status',
        'audience_type',
        'scheduled_at',
        'published_at',
        'expires_at',
        'target_audience'
    ];

    protected $casts = [
        'target_audience' => 'array',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Priority types in Nepali
    public const PRIORITIES = [
        'urgent' => 'जरुरी',
        'normal' => 'सामान्य',
        'info' => 'जानकारी'
    ];

    // Audience types in Nepali
    public const AUDIENCE_TYPES = [
        'all_students' => 'सबै विद्यार्थीहरू',
        'all_managers' => 'सबै होस्टेल म्यानेजरहरू',
        'all_users' => 'सबै प्रयोगकर्ताहरू',
        'organization_students' => 'संस्थाका विद्यार्थीहरू',
        'organization_managers' => 'संस्थाका म्यानेजरहरू',
        'organization_users' => 'संस्थाका सबै प्रयोगकर्ताहरू',
        'specific_hostel' => 'विशेष होस्टेल',
        'specific_students' => 'विशेष विद्यार्थीहरू'
    ];

    // Status types in Nepali
    public const STATUSES = [
        'draft' => 'मस्यौदा',
        'published' => 'प्रकाशित',
        'archived' => 'संग्रहित'
    ];

    /**
     * Validation rules for circular
     */
    public static function validationRules($id = null): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'created_by' => 'required|exists:users,id',
            'hostel_id' => 'nullable|exists:hostels,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:urgent,normal,info',
            'status' => 'required|in:draft,published,archived',
            'audience_type' => 'required|in:all_students,all_managers,all_users,organization_students,organization_managers,organization_users,specific_hostel,specific_students',
            'scheduled_at' => 'nullable|date',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:scheduled_at',
            'target_audience' => 'nullable|array'
        ];
    }

    // Multi-tenant scope
    public function scopeForOrganization(Builder $query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    // Published scope - ✅ FIXED: Better published status handling
    public function scopePublished(Builder $query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    // Active circulars (not expired) - ✅ FIXED SCOPE: Better active status handling
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope for user-specific circulars
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('created_by', $userId)
            ->orWhereHas('recipients', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
    }

    /**
     * Scope for creator-specific circulars
     */
    public function scopeForCreator($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    // ✅ FIXED: Scope for student dashboard circulars with proper filtering
    public function scopeForStudentDashboard($query, $userId)
    {
        return $query->whereHas('recipients', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->with(['creator', 'organization'])
            ->latest();
    }

    // ✅ NEW: Scope for student accessible circulars (simplified version)
    public function scopeForStudent($query, $userId)
    {
        return $query->whereHas('recipients', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->active()
            ->with(['creator', 'organization']);
    }

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class)->withDefault();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class)->withDefault();
    }

    public function recipients()
    {
        return $this->hasMany(CircularRecipient::class);
    }

    // ✅ FIXED: Get student recipients through circular_recipients with proper relationship
    public function studentRecipients()
    {
        return $this->hasManyThrough(
            Student::class,
            CircularRecipient::class,
            'circular_id', // Foreign key on circular_recipients table
            'user_id', // Foreign key on students table
            'id', // Local key on circulars table
            'user_id' // Local key on circular_recipients table
        )->where('circular_recipients.user_type', 'student');
    }

    // Helper methods
    public function getPriorityNepaliAttribute()
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public function getAudienceTypeNepaliAttribute()
    {
        return self::AUDIENCE_TYPES[$this->audience_type] ?? $this->audience_type;
    }

    public function getStatusNepaliAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    // ✅ FIXED: Check if circular is active and published with better logic
    public function isActive(): bool
    {
        return $this->status === 'published' &&
            (!$this->published_at || $this->published_at <= now()) &&
            (!$this->expires_at || $this->expires_at > now());
    }

    // ✅ FIXED: Check if circular is published (regardless of expiry)
    public function isPublished(): bool
    {
        return $this->status === 'published' &&
            (!$this->published_at || $this->published_at <= now());
    }

    // ✅ NEW: Check if circular is scheduled for future publication
    public function isScheduled(): bool
    {
        return $this->status === 'draft' &&
            $this->scheduled_at &&
            $this->scheduled_at > now();
    }

    // ✅ NEW: Check if circular is expired
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at <= now();
    }

    // ✅ NEW: Auto-publish scheduled circulars
    public function autoPublishIfScheduled(): void
    {
        if ($this->isScheduled() && $this->scheduled_at <= now()) {
            $this->update([
                'status' => 'published',
                'published_at' => $this->published_at ?: now()
            ]);
        }
    }

    // ✅ FIXED: Mark as published with proper timestamp handling
    public function markAsPublished()
    {
        $this->update([
            'status' => 'published',
            'published_at' => $this->published_at ?: now()
        ]);
    }

    // ✅ FIXED: Get recipients count with caching
    public function getRecipientsCountAttribute()
    {
        return $this->recipients()->count();
    }

    // ✅ FIXED: Get read recipients count
    public function getReadRecipientsCountAttribute()
    {
        return $this->recipients()->where('is_read', true)->count();
    }

    // ✅ FIXED: Get unread recipients count
    public function getUnreadRecipientsCountAttribute()
    {
        return $this->recipients()->where('is_read', false)->count();
    }

    // ✅ FIXED: Get read percentage with safe division
    public function getReadPercentageAttribute()
    {
        $total = $this->recipients_count;
        return $total > 0 ? round(($this->read_recipients_count / $total) * 100, 2) : 0;
    }

    // ✅ FIXED: Check if user has read this circular
    public function isReadByUser($userId): bool
    {
        return $this->recipients()
            ->where('user_id', $userId)
            ->where('is_read', true)
            ->exists();
    }

    // ✅ FIXED: Mark as read for a specific user with better error handling
    public function markAsRead($userId): bool
    {
        try {
            $recipient = $this->recipients()
                ->where('user_id', $userId)
                ->first();

            if ($recipient && !$recipient->is_read) {
                $recipient->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('Error marking circular as read: ' . $e->getMessage());
            return false;
        }
    }

    // ✅ FIXED: Get recipient status for a user
    public function getRecipientStatus($userId)
    {
        $recipient = $this->recipients()
            ->where('user_id', $userId)
            ->first();

        return $recipient ? [
            'is_read' => $recipient->is_read,
            'read_at' => $recipient->read_at,
            'user_type' => $recipient->user_type
        ] : null;
    }

    // ✅ FIXED: Get circulars for a specific student user with proper scope
    public static function getForStudent($userId)
    {
        return static::whereHas('recipients', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->active()
            ->with(['creator', 'organization'])
            ->latest()
            ->get();
    }

    // ✅ FIXED: Get urgent circulars for student with proper scope
    public static function getUrgentForStudent($userId)
    {
        return static::whereHas('recipients', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('priority', 'urgent')
            ->active()
            ->with(['creator', 'organization'])
            ->latest()
            ->get();
    }

    // ✅ FIXED: Check if circular can be viewed by user with better logic
    public function canBeViewedBy($user): bool
    {
        // Admin can view all circulars
        if ($user->hasRole('admin')) {
            return true;
        }

        // Organization owners/managers can view circulars in their organization
        if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            $userOrganization = $user->organizations()->first();
            return $userOrganization && $this->organization_id === $userOrganization->id;
        }

        // Students can only view circulars where they are recipients
        if ($user->hasRole('student')) {
            return $this->recipients()->where('user_id', $user->id)->exists() &&
                $this->isActive();
        }

        return false;
    }

    // ✅ NEW: Boot method for auto-publishing scheduled circulars
    protected static function boot()
    {
        parent::boot();

        // Auto-publish scheduled circulars when their time comes
        static::saving(function ($circular) {
            if ($circular->isScheduled() && $circular->scheduled_at <= now()) {
                $circular->status = 'published';
                $circular->published_at = $circular->published_at ?: now();
            }
        });
    }

    // ✅ NEW: Get circulars that need to be auto-published (for cron job)
    public static function getScheduledForPublishing()
    {
        return static::where('status', 'draft')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();
    }

    // ✅ NEW: Process auto-publishing for all scheduled circulars
    public static function processScheduledPublishing()
    {
        $circulars = self::getScheduledForPublishing();
        $count = 0;

        foreach ($circulars as $circular) {
            $circular->markAsPublished();
            $count++;
        }

        return $count;
    }

    // ✅ NEW: Get circulars expiring soon (for notifications)
    public static function getExpiringSoon($days = 1)
    {
        return static::where('status', 'published')
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays($days))
            ->get();
    }

    // ✅ NEW: Accessor for formatted dates
    public function getFormattedScheduledAtAttribute()
    {
        return $this->scheduled_at ? $this->scheduled_at->format('Y-m-d H:i') : null;
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('Y-m-d H:i') : null;
    }

    public function getFormattedExpiresAtAttribute()
    {
        return $this->expires_at ? $this->expires_at->format('Y-m-d H:i') : null;
    }
}
