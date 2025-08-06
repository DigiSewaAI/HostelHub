<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_number',
        'floor',
        'capacity',
        'status',
        'description',
        'price', // यो थपियो किनभने क्वेरीमा price को प्रयोग छ
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2', // मूल्यलाई दशमलवको रूपमा कास्ट गर्ने
    ];

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
     * Scope a query to get rooms with available capacity (students_count < capacity)
     */
    public function scopeWithAvailableCapacity(Builder $query): Builder
    {
        return $query->whereRaw('(SELECT COUNT(*) FROM students WHERE students.room_id = rooms.id) < capacity');
    }

    /**
     * Scope a query to get rooms by floor
     */
    public function scopeFloor(Builder $query, string $floor): Builder
    {
        return $query->where('floor', $floor);
    }

    /**
     * Scope a query to get rooms by capacity
     */
    public function scopeCapacity(Builder $query, int $capacity): Builder
    {
        return $query->where('capacity', $capacity);
    }

    /**
     * Calculate the overall room occupancy rate.
     */
    public static function getOccupancyRate(): float
    {
        $totalRooms = self::count();

        if ($totalRooms === 0) {
            return 0.0;
        }

        $occupiedRooms = self::occupied()->count();
        return round(($occupiedRooms / $totalRooms) * 100, 1);
    }

    /**
     * Get the current occupancy percentage for this specific room.
     */
    public function getOccupancyAttribute(): float
    {
        if ($this->capacity === 0) {
            return 0.0;
        }

        // क्याश भएको students_count प्रयोग गर्ने वा नयाँ काउन्ट गर्ने
        $currentOccupancy = isset($this->students_count) ? $this->students_count : $this->students()->count();
        return round(($currentOccupancy / $this->capacity) * 100, 1);
    }

    /**
     * Get the available capacity for this room.
     */
    public function getAvailableCapacityAttribute(): int
    {
        $currentOccupancy = isset($this->students_count) ? $this->students_count : $this->students()->count();
        return max(0, $this->capacity - $currentOccupancy);
    }
}
