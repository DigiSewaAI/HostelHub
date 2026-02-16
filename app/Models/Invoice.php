<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'hostel_id',
        'billing_month',
        'amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'billing_month' => 'date',
        'due_date'      => 'date',
    ];

    // सम्बन्ध (Relationships)
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * इनभ्वाइसको स्थिति (status) भुक्तानीको कुल रकम अनुसार अपडेट गर्ने
     */
    public function updateStatus(): void
    {
        $totalPaid = $this->payments()
            ->whereIn('status', ['completed', 'paid'])   // 'paid' पनि हुन सक्छ, तर तपाईंको कोडमा 'completed' मात्र छ
            ->sum('amount');

        if ($totalPaid >= $this->amount) {
            $this->status = 'paid';
        } elseif ($totalPaid > 0) {
            $this->status = 'partial';
        } else {
            $this->status = 'unpaid';
        }
        $this->saveQuietly();  // इभेन्टहरू नउठाई सेभ गर्ने
    }
}
