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
        'name',
        'email',
        'phone',
        'address',
        'guardian_name',
        'guardian_phone',
        'college_id',
        'room_id',
        'hostel_id',
        'status',
        'admission_date',
        'image',
        'user_id', // ✅ user_id थपियो (यदि user सँग link गर्न चाहिन्छ भने)
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'admission_date' => 'date',
    ];

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
        return $this->belongsTo(User::class); // ✅ User सँगको relation
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
}
