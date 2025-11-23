<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hostel_id',
        'room_id',
        'name',
        'phone',
        'email',
        'check_in_date',
        'room_type',
        'message',
        'status',
        'admin_notes',
        'approved_at',
        'rejected_at'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'check_in_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Room type constants
     */
    const ROOM_SINGLE = 'single';
    const ROOM_DOUBLE = 'double';
    const ROOM_TRIPLE = 'triple';
    const ROOM_DORMITORY = 'dormitory';
    const ROOM_SUITE = 'suite';

    /**
     * Get the hostel that owns the booking request.
     */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Get the room that owns the booking request.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope for cancelled requests
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope for active requests (pending or approved)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }

    /**
     * Get status in Nepali
     */
    public function getStatusNepaliAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'पेन्डिङ',
            self::STATUS_APPROVED => 'स्वीकृत',
            self::STATUS_REJECTED => 'अस्वीकृत',
            self::STATUS_CANCELLED => 'रद्द भयो',
            default => $this->status
        };
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-secondary',
            default => 'bg-light text-dark'
        };
    }

    /**
     * Get room type in Nepali
     */
    public function getRoomTypeNepaliAttribute(): string
    {
        return match ($this->room_type) {
            self::ROOM_SINGLE => 'एकल कोठा',
            self::ROOM_DOUBLE => 'डबल कोठा',
            self::ROOM_TRIPLE => 'ट्रिपल कोठा',
            self::ROOM_DORMITORY => 'डर्मिटरी',
            self::ROOM_SUITE => 'सुइट',
            default => $this->room_type
        };
    }

    /**
     * Check if request can be approved
     */
    public function canBeApproved(): bool
    {
        return $this->status === self::STATUS_PENDING &&
            $this->check_in_date->isFuture();
    }

    /**
     * Check if request can be rejected
     */
    public function canBeRejected(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]) &&
            $this->check_in_date->isFuture();
    }

    /**
     * Approve booking request
     */
    public function approve(string $notes = null): bool
    {
        return $this->update([
            'status' => self::STATUS_APPROVED,
            'admin_notes' => $notes,
            'approved_at' => now(),
            'rejected_at' => null
        ]);
    }

    /**
     * Reject booking request
     */
    public function reject(string $notes = null): bool
    {
        return $this->update([
            'status' => self::STATUS_REJECTED,
            'admin_notes' => $notes,
            'rejected_at' => now(),
            'approved_at' => null
        ]);
    }

    /**
     * Cancel booking request
     */
    public function cancel(string $notes = null): bool
    {
        return $this->update([
            'status' => self::STATUS_CANCELLED,
            'admin_notes' => $notes,
            'rejected_at' => now()
        ]);
    }

    /**
     * Get formatted ID with prefix
     */
    public function getFormattedIdAttribute(): string
    {
        return 'BR-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if request is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Get all status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Get all room type options
     */
    public static function getRoomTypeOptions(): array
    {
        return [
            self::ROOM_SINGLE => 'Single',
            self::ROOM_DOUBLE => 'Double',
            self::ROOM_TRIPLE => 'Triple',
            self::ROOM_DORMITORY => 'Dormitory',
            self::ROOM_SUITE => 'Suite',
        ];
    }

    /**
     * Get all room type options in Nepali
     */
    public static function getRoomTypeOptionsNepali(): array
    {
        return [
            self::ROOM_SINGLE => 'एकल कोठा',
            self::ROOM_DOUBLE => 'डबल कोठा',
            self::ROOM_TRIPLE => 'ट्रिपल कोठा',
            self::ROOM_DORMITORY => 'डर्मिटरी',
            self::ROOM_SUITE => 'सुइट',
        ];
    }
}
