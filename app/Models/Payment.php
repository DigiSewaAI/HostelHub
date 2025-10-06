<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'hostel_id', 
        'room_id',
        'booking_id', // ✅ नयाँ field थप्नुहोस्
        'amount',
        'payment_date',
        'due_date',
        'payment_method',
        'transaction_id',
        'status',
        'remarks',
        'paid_by', // ✅ नयाँ field थप्नुहोस् (user_id)
        'verified_by', // ✅ नयाँ field थप्नुहोस्
        'verified_at' // ✅ नयाँ field थप्नुहोस्
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'due_date' => 'datetime',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    // ✅ नयाँ relationship थप्नुहोस्
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // ✅ नयाँ relationship थप्नुहोस्
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    // ✅ नयाँ relationship थप्नुहोस्
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ✅ Status constants थप्नुहोस्
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    // ✅ Payment method constants थप्नुहोस्
    const METHOD_CASH = 'cash';
    const METHOD_KHALTI = 'khalti';
    const METHOD_ESEWA = 'esewa';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
}