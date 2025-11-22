<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRequest extends Model
{
    use HasFactory;

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
        'admin_notes'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

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
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Get status in Nepali
     */
    public function getStatusNepaliAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'पेन्डिङ',
            'approved' => 'स्वीकृत',
            'rejected' => 'अस्वीकृत',
            'cancelled' => 'रद्द भयो',
            default => $this->status
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'cancelled' => 'bg-secondary',
            default => 'bg-light text-dark'
        };
    }

    /**
     * Check if request can be approved
     */
    public function canBeApproved(): bool
    {
        return $this->status === 'pending' && $this->check_in_date->isFuture();
    }

    /**
     * Check if request can be rejected
     */
    public function canBeRejected(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Approve booking request
     */
    public function approve(string $notes = null): bool
    {
        return $this->update([
            'status' => 'approved',
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
            'status' => 'rejected',
            'admin_notes' => $notes,
            'rejected_at' => now(),
            'approved_at' => null
        ]);
    }
}
