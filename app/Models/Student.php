<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'guardian_name',
        'guardian_contact',
        'guardian_relation',
        'guardian_address',
        'dob',
        'gender',
        'payment_status',
        'status',
        'admission_date',
        'college_id',     // existing
        'college',        // ✅ NEW - for "other college" name
        'room_id',
        'hostel_id',
        'organization_id',
        'image',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'admission_date' => 'date',
        'dob' => 'date',
    ];

    /**
     * The model's default values for attributes.
     */
    protected $attributes = [
        'user_id' => null,
        'status' => 'pending',
        'payment_status' => 'pending',
    ];

    /**
     * Get the college that this student belongs to.
     */
    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    /**
     * Get the course that this student belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the organization that this student belongs to.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the room that this student belongs to.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the hostel that this student belongs to.
     */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Get the user associated with this student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bookings made by this student.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id', 'user_id');
    }

    /**
     * Get the payments made by this student.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the meals assigned to this student.
     */
    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class);
    }

    /**
     * Scope to get only active students.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Accessor for image URL.
     */
    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-user.png');
    }

    /**
     * Accessor for Nepali status.
     */
    public function getNepaliStatusAttribute()
    {
        $statusMap = [
            'pending' => 'पेन्डिङ',
            'approved' => 'स्वीकृत',
            'active' => 'सक्रिय',
            'inactive' => 'निष्क्रिय'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    /**
     * Accessor for Nepali payment status.
     */
    public function getNepaliPaymentStatusAttribute()
    {
        $paymentStatusMap = [
            'pending' => 'पेन्डिङ',
            'paid' => 'भुक्तानी भएको',
            'unpaid' => 'भुक्तानी नभएको'
        ];

        return $paymentStatusMap[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * New method: Check if student is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * New method: Check if payment is completed
     */
    public function hasPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * New method: Get student's age
     */
    public function getAgeAttribute(): ?int
    {
        return $this->dob ? $this->dob->age : null;
    }
}
