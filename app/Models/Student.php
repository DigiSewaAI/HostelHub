<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
     * ✅ NEW: Create student record from approved booking
     */
    public static function createFromBooking($booking, $user)
    {
        try {
            Log::info('Creating student record from booking', [
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            // Generate unique student ID
            $studentId = 'STU' . time() . rand(100, 999);

            $studentData = [
                'student_id' => $studentId,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'Not provided',
                'address' => $user->address ?? 'Not provided',
                'guardian_name' => 'To be updated',
                'guardian_contact' => 'To be updated',
                'guardian_relation' => 'To be updated',
                'guardian_address' => 'To be updated',
                'dob' => now()->subYears(18), // Default 18 years old
                'gender' => 'other',
                'payment_status' => 'pending',
                'status' => 'active',
                'admission_date' => now(),
                'hostel_id' => $booking->hostel_id,
                'room_id' => $booking->room_id,
                'organization_id' => $booking->hostel->organization_id ?? 1, // Default organization
                'image' => null,
            ];

            // Create student record
            $student = self::create($studentData);

            Log::info('Successfully created student record from booking', [
                'student_id' => $student->id,
                'booking_id' => $booking->id,
                'user_id' => $user->id
            ]);

            return $student;
        } catch (\Exception $e) {
            Log::error('Failed to create student record from booking', [
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Validation rules for Student model
     */
    public static function validationRules($id = null): array
    {
        return [
            'student_id' => 'required|string|max:100|unique:students,student_id,' . $id,
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:20',
            'guardian_relation' => 'required|string|max:100',
            'guardian_address' => 'nullable|string|max:500',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'payment_status' => 'required|in:pending,paid,unpaid',
            'status' => 'required|in:pending,approved,active,inactive',
            'admission_date' => 'required|date',
            'college_id' => 'nullable|exists:colleges,id',
            'college' => 'nullable|string|max:255',
            'room_id' => 'nullable|exists:rooms,id',
            'hostel_id' => 'required|exists:hostels,id',
            'organization_id' => 'required|exists:organizations,id',
            'image' => 'nullable|string|max:500'
        ];
    }

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
        return $this->belongsTo(User::class, 'user_id', 'id');
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
     * ✅ ADDED: Get the reviews written by this student.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * ✅ ADDED: Get circulars for this student
     */
    public function circulars()
    {
        return $this->hasManyThrough(
            Circular::class,
            CircularRecipient::class,
            'user_id', // Foreign key on circular_recipients table
            'id', // Foreign key on circulars table
            'user_id', // Local key on students table
            'circular_id' // Local key on circular_recipients table
        );
    }

    /**
     * Scope to get only active students.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for organization students
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope for hostel students
     */
    public function scopeForHostel($query, $hostelId)
    {
        return $query->where('hostel_id', $hostelId);
    }

    /**
     * Scope for room students
     */
    public function scopeForRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    /**
     * Scope for user access control
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('hostel', function ($q) use ($userId) {
            $q->where('owner_id', $userId)
                ->orWhere('manager_id', $userId);
        })->orWhereHas('organization', function ($q) use ($userId) {
            $q->whereHas('users', function ($q2) use ($userId) {
                $q2->where('user_id', $userId);
            });
        });
    }

    /**
     * Accessor for image URL.
     */
    public function getImageUrlAttribute()
    {
        if (!empty($this->image)) {
            // Check if it's already a full URL
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }

            // Try to get from storage
            if (Storage::disk('public')->exists($this->image)) {
                return Storage::disk('public')->url($this->image);
            }

            // Try asset path
            if (file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }
        }

        return asset('images/default-user.png');
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
     * ✅ ADDED: Get unread circulars count
     */
    public function getUnreadCircularsCountAttribute()
    {
        return $this->user->circularRecipients()
            ->where('is_read', false)
            ->count();
    }

    /**
     * New method: Check if student is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if student can be transferred to another hostel
     */
    public function canTransferTo($hostelId): bool
    {
        // Can't transfer if already in the same hostel
        if ($this->hostel_id == $hostelId) {
            return false;
        }

        // Can't transfer if active in another hostel
        if (in_array($this->status, ['active', 'approved'])) {
            return false;
        }

        // Can transfer if inactive or pending
        return in_array($this->status, ['inactive', 'pending']);
    }

    /**
     * Transfer student to another hostel
     */
    public function transferToHostel($newHostelId, $newRoomId = null, $newStatus = 'active')
    {
        if (!$this->canTransferTo($newHostelId)) {
            return false;
        }

        $oldHostelId = $this->hostel_id;
        $oldRoomId = $this->room_id;

        // Free old room if assigned
        if ($oldRoomId) {
            $oldRoom = Room::find($oldRoomId);
            if ($oldRoom) {
                // Check if no other active students in the room
                $otherActiveStudents = Student::where('room_id', $oldRoomId)
                    ->where('id', '!=', $this->id)
                    ->whereIn('status', ['active', 'approved'])
                    ->count();

                if ($otherActiveStudents == 0) {
                    $oldRoom->update(['status' => 'available']);
                }
            }
        }

        // Update student to new hostel
        $this->update([
            'hostel_id' => $newHostelId,
            'room_id' => $newRoomId,
            'status' => $newStatus
        ]);

        // Occupy new room if assigned
        if ($newRoomId) {
            $newRoom = Room::find($newRoomId);
            if ($newRoom && $newRoom->status == 'available') {
                $newRoom->update(['status' => 'occupied']);
            }
        }

        Log::info('Student transferred', [
            'student_id' => $this->id,
            'from_hostel' => $oldHostelId,
            'to_hostel' => $newHostelId,
            'from_room' => $oldRoomId,
            'to_room' => $newRoomId
        ]);

        return true;
    }


    /**
     * Check if student is active in hostel
     */
    public function isActiveInHostel(): bool
    {
        return $this->hostel_id && in_array($this->status, ['active', 'approved']);
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
        if (!$this->dob) {
            return null;
        }
        return $this->dob->age ?? now()->diffInYears($this->dob);
    }

    /**
     * Check if student can be modified by user
     */
    public function canBeModifiedBy($user): bool
    {
        // Check if hostel exists and user can modify it
        if ($this->hostel && method_exists($this->hostel, 'canBeModifiedBy')) {
            if ($this->hostel->canBeModifiedBy($user)) {
                return true;
            }
        }

        // Check if organization exists and user can modify it
        if ($this->organization && method_exists($this->organization, 'canBeModifiedBy')) {
            if ($this->organization->canBeModifiedBy($user)) {
                return true;
            }
        }

        // Check if user owns this student record
        return $this->user_id && $this->user_id === $user->id;
    }

    /**
     * Get the hostel through room relationship
     */
    public function getHostelThroughRoomAttribute()
    {
        if ($this->room && $this->room->hostel) {
            return $this->room->hostel;
        }
        return $this->hostel;
    }

    /**
     * Accessor for current hostel (with fallbacks)
     */
    public function getCurrentHostelAttribute()
    {
        // Priority 1: Direct hostel relationship
        if ($this->hostel) {
            return $this->hostel;
        }

        // Priority 2: Hostel through room
        if ($this->room && $this->room->hostel) {
            return $this->room->hostel;
        }

        // Priority 3: User's hostel
        if ($this->user && $this->user->hostel) {
            return $this->user->hostel;
        }

        return null;
    }

    /**
     * Get student statistics
     */
    public function getStatisticsAttribute(): array
    {
        return [
            'total_meals' => $this->meals()->count(),
            'total_payments' => $this->payments()->count(),
            'total_bookings' => $this->bookings()->count(),
            'total_reviews' => $this->reviews()->count(),
        ];
    }

    /**
     * 🔥 CRITICAL: Prevent hostel_id from being set to NULL accidentally
     */
    public function setHostelIdAttribute($value)
    {
        // If trying to set to NULL but student is active/approved, keep current value
        if ($value === null && in_array($this->status, ['active', 'approved'])) {
            // Keep the existing hostel_id
            $this->attributes['hostel_id'] = $this->getOriginal('hostel_id');
        } else {
            $this->attributes['hostel_id'] = $value;
        }
    }

    /**
     * 🔥 CRITICAL: Prevent room_id from being set to NULL when hostel_id exists
     */
    public function setRoomIdAttribute($value)
    {
        // If trying to set to NULL but hostel_id exists, keep current value
        if ($value === null && $this->hostel_id) {
            // Keep the existing room_id
            $this->attributes['room_id'] = $this->getOriginal('room_id');
        } else {
            $this->attributes['room_id'] = $value;
        }
    }

    /**
     * Check if student can be deleted
     */
    public function getCanBeDeletedAttribute(): bool
    {
        return $this->meals()->count() === 0 &&
            $this->payments()->count() === 0 &&
            $this->bookings()->count() === 0 &&
            $this->reviews()->count() === 0;
    }
}
