<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hostel_id',
        'room_number',  // पहिले: कोठा_नम्बर
        'type',         // पहिले: प्रकार  
        'capacity',     // पहिले: क्षमता
        'price',        // पहिले: मूल्य
        'status',       // पहिले: स्थिति
        'image',        // पहिले: तस्वीर
        'description',  // पहिले: विवरण
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
     * Scope a query to only include available rooms.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'उपलब्ध');
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
        $occupiedRooms = self::where('status', 'बुक भएको')->count();

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
     * Get the available capacity for this room.
     */
    public function getAvailableCapacityAttribute(): int
    {
        $currentOccupancy = $this->students_count ?? $this->students()->count();
        return max(0, $this->capacity - $currentOccupancy);
    }

    /**
     * Get Nepali room type
     */
    public function getNepaliTypeAttribute(): string
    {
        $types = [
            'स्ट्यान्डर्ड' => 'स्ट्यान्डर्ड कोठा',
            'डीलक्स' => 'डीलक्स कोठा',
            'विआईपी' => 'विआईपी कोठा',
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * Get Nepali status
     */
    public function getNepaliStatusAttribute(): string
    {
        $statuses = [
            'उपलब्ध' => 'उपलब्ध',
            'बुक भएको' => 'बुक भएको',
            'रिङ्गोट' => 'रिङ्गोट',
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}
