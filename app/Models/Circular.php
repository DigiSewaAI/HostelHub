<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    // Published scope
    public function scopePublished(Builder $query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    // Active circulars (not expired)
    public function scopeActive(Builder $query)
    {
        return $query->where(function ($q) {
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

    public function isPublished()
    {
        return $this->status === 'published' &&
            (!$this->published_at || $this->published_at <= now());
    }

    public function markAsPublished()
    {
        $this->update([
            'status' => 'published',
            'published_at' => $this->published_at ?: now()
        ]);
    }
}
