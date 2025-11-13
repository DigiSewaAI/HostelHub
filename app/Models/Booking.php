<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    /**
     * Booking status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'room_id',
        'hostel_id',
        'booking_date',
        'check_in_date',
        'check_out_date',
        'status',
        'amount',
        'payment_status',
        'notes',
        'approved_by',
        'approved_at',
        'rejection_reason'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'booking_date' => 'datetime',
        'check_in_date' => 'datetime',
        'check_out_date' => 'datetime',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime'
    ];

    /**
     * Validation rules for booking
     */
    public static function validationRules($id = null): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'hostel_id' => 'required|exists:hostels,id',
            'booking_date' => 'required|date',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'status' => 'required|in:pending,approved,rejected,cancelled,completed',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string|max:500',
            'approved_by' => 'nullable|exists:users,id',
            'approved_at' => 'nullable|date',
            'rejection_reason' => 'nullable|string|max:255'
        ];
    }

    /**
     * Get the room that owns the booking.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class)->withDefault();
    }

    /**
     * Get the hostel that owns the booking.
     */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class)->withDefault();
    }

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Get the user who approved the booking
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by')->withDefault();
    }

    /**
     * Scope for user-specific bookings
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for hostel-specific bookings
     */
    public function scopeForHostel($query, $hostelId)
    {
        return $query->where('hostel_id', $hostelId);
    }

    /**
     * Scope for organization-specific bookings
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->whereHas('hostel', function ($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        });
    }

    /**
     * Check if booking is pending approval
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if booking is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if booking is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if booking is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if booking is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Approve booking
     */
    public function approve($approvedBy): bool
    {
        return $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $approvedBy,
            'approved_at' => now()
        ]);
    }

    /**
     * Reject booking
     */
    public function reject($rejectedBy, $reason = null): bool
    {
        return $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by' => $rejectedBy,
            'approved_at' => now(),
            'rejection_reason' => $reason
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel(): bool
    {
        return $this->update([
            'status' => self::STATUS_CANCELLED
        ]);
    }

    /**
     * Scope a query to only include active bookings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_APPROVED)
            ->where('check_out_date', '>=', now());
    }

    /**
     * Scope a query to only include completed bookings.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED)
            ->orWhere('check_out_date', '<', now());
    }

    /**
     * Scope for pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved bookings
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected bookings
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Get bookings that require approval
     */
    public function scopeRequiresApproval($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Get booking status with Nepali translation
     */
    public function getStatusNepali(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'पेन्डिङ',
            self::STATUS_APPROVED => 'स्वीकृत',
            self::STATUS_REJECTED => 'अस्वीकृत',
            self::STATUS_CANCELLED => 'रद्द भयो',
            self::STATUS_COMPLETED => 'पूरा भयो',
            default => $this->status
        };
    }

    /**
     * Check if booking can be approved
     */
    public function canBeApproved(): bool
    {
        return $this->isPending() &&
            $this->check_in_date > now() &&
            $this->room->is_available;
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]) &&
            $this->check_in_date > now()->addDays(1);
    }
}
