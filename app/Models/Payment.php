<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    // ✅ STATUS CONSTANTS
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_CANCELLED = 'cancelled';

    // ✅ PAYMENT METHOD CONSTANTS
    const METHOD_CASH = 'cash';
    const METHOD_KHALTI = 'khalti';
    const METHOD_ESEWA = 'esewa';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_CREDIT_CARD = 'credit_card';

    // ✅ PAYMENT PURPOSE CONSTANTS
    const PURPOSE_BOOKING = 'booking';
    const PURPOSE_SUBSCRIPTION = 'subscription';
    const PURPOSE_EXTRA_HOSTEL = 'extra_hostel';
    const PURPOSE_MEAL = 'meal';
    const PURPOSE_OTHER = 'other';

    protected $fillable = [
        'organization_id', // ✅ Multi-tenant support
        'user_id',         // ✅ User who made the payment
        'student_id',
        'hostel_id',
        'room_id',
        'booking_id',
        'subscription_id', // ✅ Subscription relationship
        'amount',
        'payment_date',
        'due_date',
        'payment_method',
        'purpose',         // ✅ Payment purpose
        'transaction_id',
        'status',
        'remarks',
        'created_by',      // ✅ ADDED: Created by user
        'updated_by',      // ✅ ADDED: Updated by user
        'verified_by',
        'verified_at',
        'metadata'         // ✅ Additional payment data
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'due_date' => 'datetime',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
        'metadata' => 'array' // ✅ Cast metadata as array
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_date)) {
                $payment->payment_date = now();
            }
            if (empty($payment->status)) {
                $payment->status = self::STATUS_PENDING;
            }
            // ✅ Set created_by if not set
            if (empty($payment->created_by) && auth()->check()) {
                $payment->created_by = auth()->id();
            }
        });

        static::updating(function ($payment) {
            // ✅ Set updated_by if not set
            if (empty($payment->updated_by) && auth()->check()) {
                $payment->updated_by = auth()->id();
            }
        });
    }

    // ✅ ORGANIZATION RELATIONSHIP
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // ✅ USER RELATIONSHIP (User who made the payment)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ STUDENT RELATIONSHIP
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // ✅ HOSTEL RELATIONSHIP
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    // ✅ ROOM RELATIONSHIP
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    // ✅ BOOKING RELATIONSHIP
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // ✅ SUBSCRIPTION RELATIONSHIP
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    // ✅ CREATED BY RELATIONSHIP (ADDED)
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ✅ UPDATED BY RELATIONSHIP (ADDED)
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ✅ VERIFIED BY RELATIONSHIP
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ✅ SCOPE QUERIES
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('payment_date', now()->year)
            ->whereMonth('payment_date', now()->month);
    }

    // ✅ HELPER METHODS
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    public function markAsCompleted($verifiedBy = null): bool
    {
        return $this->update([
            'status' => self::STATUS_COMPLETED,
            'verified_by' => $verifiedBy,
            'verified_at' => now()
        ]);
    }

    public function markAsFailed(): bool
    {
        return $this->update([
            'status' => self::STATUS_FAILED
        ]);
    }

    public function markAsRefunded(): bool
    {
        return $this->update([
            'status' => self::STATUS_REFUNDED
        ]);
    }

    public function getFormattedAmount(): string
    {
        return 'रु. ' . number_format($this->amount, 2);
    }

    public function getPaymentMethodText(): string
    {
        $methods = [
            self::METHOD_CASH => 'नगद',
            self::METHOD_KHALTI => 'खल्ती',
            self::METHOD_ESEWA => 'eSewa',
            self::METHOD_BANK_TRANSFER => 'बैंक स्थानान्तरण',
            self::METHOD_CREDIT_CARD => 'क्रेडिट कार्ड'
        ];

        return $methods[$this->payment_method] ?? 'अन्य';
    }

    public function getPurposeText(): string
    {
        $purposes = [
            self::PURPOSE_BOOKING => 'कोठा बुकिंग',
            self::PURPOSE_SUBSCRIPTION => 'सदस्यता शुल्क',
            self::PURPOSE_EXTRA_HOSTEL => 'अतिरिक्त होस्टल',
            self::PURPOSE_MEAL => 'खाना शुल्क',
            self::PURPOSE_OTHER => 'अन्य'
        ];

        return $purposes[$this->purpose] ?? 'अन्य';
    }

    public function getStatusBadge(): string
    {
        $badges = [
            self::STATUS_PENDING => '<span class="badge badge-warning">पेन्डिङ</span>',
            self::STATUS_COMPLETED => '<span class="badge badge-success">पूरा भयो</span>',
            self::STATUS_FAILED => '<span class="badge badge-danger">असफल</span>',
            self::STATUS_REFUNDED => '<span class="badge badge-info">फिर्ता भयो</span>',
            self::STATUS_CANCELLED => '<span class="badge badge-secondary">रद्द भयो</span>'
        ];

        return $badges[$this->status] ?? '<span class="badge badge-secondary">अन्य</span>';
    }

    /**
     * Check if payment is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->isPending();
    }

    /**
     * Get days overdue
     */
    public function getDaysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return $this->due_date->diffInDays(now());
    }


    /**
     * Get receipt number
     */
    public function getReceiptNumber(): string
    {
        return 'RCPT-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get payment duration in months (for subscription)
     */
    public function getPaymentDuration(): int
    {
        if ($this->purpose !== self::PURPOSE_SUBSCRIPTION) {
            return 1;
        }

        // Default to 1 month for subscription
        return 1;
    }

    /**
     * Get next due date based on payment
     */
    public function getNextDueDate()
    {
        if (!$this->payment_date) {
            return null;
        }

        return $this->payment_date->copy()->addMonths($this->getPaymentDuration());
    }

    /**
     * ✅ ADDED: Get created by user name safely
     */
    public function getCreatedByName(): string
    {
        return $this->createdBy ? $this->createdBy->name : 'System';
    }

    /**
     * ✅ ADDED: Get updated by user name safely
     */
    public function getUpdatedByName(): string
    {
        return $this->updatedBy ? $this->updatedBy->name : 'System';
    }

    /**
     * ✅ ADDED: Static method to get payment method text
     */
    /**
     * Get payment method text in Nepali (static version for export)
     */
    public static function getPaymentMethodTextStatic($method): string
    {
        return match ($method) {
            'cash' => 'नगद',
            'bank_transfer' => 'बैंक स्थानान्तरण',
            'khalti' => 'खल्ती',
            'esewa' => 'ईसेवा',
            'connectips' => 'कनेक्टआइपीएस',
            default => $method
        };
    }

    /**
     * ✅ ADDED: Get verified by user name safely
     */
    public function getVerifiedByName(): string
    {
        return $this->verifiedBy ? $this->verifiedBy->name : 'N/A';
    }
}
