<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage; // ✅ ADD: Storage facade

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hostel_id',
        'room_number',
        'type',
        'gallery_category',
        'capacity',
        'price',
        'status',
        'image',
        'description',
        'floor',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'capacity' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['image_url', 'has_image']; // ✅ ADD: Accessors

    /**
     * Get the hostel that this room belongs to.
     */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Get the students for the room.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the bookings for the room.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // ✅ ADD: Amenities relationship
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_amenity', 'room_id', 'amenity_id')
            ->withTimestamps();
    }

    // ✅ ADD: Reviews relationship (if needed)
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Check if room is available for given dates
     */
    public function isAvailableForDates($checkIn, $checkOut): bool
    {
        $conflictingBookings = $this->bookings()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        return $conflictingBookings === 0;
    }

    /**
     * Scope a query to only include available rooms.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        // FIX: Use English status values for queries
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include occupied rooms.
     */
    public function scopeOccupied(Builder $query): Builder
    {
        return $query->where('status', 'occupied');
    }

    /**
     * Scope a query to only include rooms under maintenance.
     */
    public function scopeMaintenance(Builder $query): Builder
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Scope a query to only include rooms with available capacity.
     */
    public function scopeWithAvailableCapacity(Builder $query): Builder
    {
        return $query->whereRaw('(SELECT COUNT(*) FROM students WHERE students.room_id = rooms.id) < capacity');
    }

    /**
     * Calculate the overall room occupancy rate.
     */
    public static function getOccupancyRate(): float
    {
        $totalRooms = self::count();
        $occupiedRooms = self::where('status', 'occupied')->count();

        if ($totalRooms > 0) {
            return round(($occupiedRooms / $totalRooms) * 100, 2);
        }

        return 0.0;
    }

    /**
     * Get the current occupancy percentage for this specific room.
     */
    public function getOccupancyAttribute(): float
    {
        $currentOccupancy = $this->students_count ?? $this->students()->count();
        return $this->capacity ? round(($currentOccupancy / $this->capacity) * 100, 1) : 0.0;
    }

    /**
     * Get the current number of students in this room.
     */
    public function getCurrentOccupancyAttribute(): int
    {
        return $this->students_count ?? $this->students()->count();
    }

    /**
     * Get the available capacity for this room.
     */
    public function getAvailableCapacityAttribute(): int
    {
        $currentOccupancy = $this->students_count ?? $this->students()->count();
        return max(0, $this->capacity - $currentOccupancy);
    }

    /**
     * Check if room has available space
     */
    public function getHasAvailableSpaceAttribute(): bool
    {
        return $this->available_capacity > 0;
    }

    /**
     * Get the room image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        return asset('images/no-image.png');
    }

    /**
     * Check if room has image
     */
    public function getHasImageAttribute(): bool
    {
        return !empty($this->image) && Storage::disk('public')->exists($this->image);
    }

    /**
     * Get Nepali room type
     */
    public function getNepaliTypeAttribute(): string
    {
        $types = [
            'single' => 'एकल कोठा',
            'double' => 'दोहोरो कोठा',
            'shared' => 'साझा कोठा',
            'standard' => 'स्ट्यान्डर्ड कोठा',
            'deluxe' => 'डीलक्स कोठा',
            'vip' => 'विआईपी कोठा',
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * Get Nepali status
     */
    public function getNepaliStatusAttribute(): string
    {
        $statuses = [
            'available' => 'उपलब्ध',
            'occupied' => 'अधिभृत',
            'maintenance' => 'मर्मतमा',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'रु ' . number_format($this->price, 2);
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Update hostel room counts when room is created, updated or deleted
        static::created(function ($room) {
            $room->hostel->updateRoomCounts();
        });

        static::updated(function ($room) {
            $room->hostel->updateRoomCounts();
        });

        static::deleted(function ($room) {
            $room->hostel->updateRoomCounts();
        });
    }
}
