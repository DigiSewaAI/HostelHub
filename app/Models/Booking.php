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
        'rejection_reason',
        // ✅ Guest booking fields
        'guest_name',
        'guest_email',
        'guest_phone',
        'is_guest_booking',
        'email',
        // ✅ New fields for emergency contact
        'emergency_contact',
        'organization_id'
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
        'approved_at' => 'datetime',
        // ✅ Guest booking casts
        'is_guest_booking' => 'boolean'
    ];

    /**
     * Validation rules for booking
     */
    public static function validationRules($id = null): array
    {
        $today = now()->format('Y-m-d'); // ✅ ADDED: Get today's date for validation

        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'room_id' => 'nullable|exists:rooms,id',
            'hostel_id' => 'required|exists:hostels,id',
            'organization_id' => 'nullable|exists:organizations,id',
            'booking_date' => 'required|date',
            'check_in_date' => 'required|date|after_or_equal:' . $today, // ✅ CHANGED: after:today -> after_or_equal:today
            'check_out_date' => 'nullable|date|after:check_in_date',
            'status' => 'required|in:pending,approved,rejected,cancelled,completed',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string|max:500',
            'approved_by' => 'nullable|exists:users,id',
            'approved_at' => 'nullable|date',
            'rejection_reason' => 'nullable|string|max:255',
            // ✅ Guest booking validation
            'guest_name' => 'nullable|required_if:is_guest_booking,true|string|max:255',
            'guest_email' => 'nullable|required_if:is_guest_booking,true|email',
            'guest_phone' => 'nullable|required_if:is_guest_booking,true|string|max:20',
            'is_guest_booking' => 'sometimes|boolean',
            'email' => 'nullable|email',
            // ✅ Emergency contact field
            'emergency_contact' => 'nullable|string|max:15'
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
     * Get the organization that owns the booking through hostel
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class)->withDefault();
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
            (!$this->room_id || $this->room->is_available);
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]) &&
            $this->check_in_date > now()->addDays(1);
    }

    // ✅ NEW GUEST BOOKING METHODS

    /**
     * Check if booking is by guest
     */
    public function isGuestBooking(): bool
    {
        return (bool) $this->is_guest_booking && !$this->user_id;
    }

    /**
     * Get customer email for the booking
     */
    public function getCustomerEmail(): ?string
    {
        if ($this->isGuestBooking()) {
            return $this->guest_email;
        }

        return $this->email ?? ($this->user->email ?? null);
    }

    /**
     * Get customer name for the booking
     */
    public function getCustomerName(): ?string
    {
        if ($this->isGuestBooking()) {
            return $this->guest_name;
        }

        return $this->user->name ?? null;
    }

    /**
     * Check if guest booking can be converted to student booking
     */
    public function canBeConvertedToStudent(): bool
    {
        return $this->isGuestBooking() &&
            !empty($this->guest_email) &&
            $this->isApproved();
    }

    /**
     * Attach guest booking to user
     */
    public function attachToUser(User $user): bool
    {
        return $this->update([
            'user_id' => $user->id,
            'is_guest_booking' => false,
            'email' => $user->email
        ]);
    }

    /**
     * Scope for guest bookings
     */
    public function scopeGuestBookings($query)
    {
        return $query->where('is_guest_booking', true);
    }

    /**
     * Scope for guest bookings by email
     */
    public function scopeByGuestEmail($query, $email)
    {
        return $query->where('guest_email', $email)
            ->where('is_guest_booking', true);
    }

    /**
     * ✅ NEW: Check if booking has check-out date
     */
    public function hasCheckoutDate(): bool
    {
        return !is_null($this->check_out_date);
    }

    /**
     * ✅ NEW: Get duration in days (if checkout date exists)
     */
    public function getDurationInDays(): ?int
    {
        if (!$this->hasCheckoutDate()) {
            return null;
        }

        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    /**
     * ✅ NEW: Get displayable check-out date
     */
    public function getDisplayCheckoutDate(): string
    {
        if (!$this->hasCheckoutDate()) {
            return 'N/A';
        }

        return $this->check_out_date->format('Y-m-d');
    }
}
