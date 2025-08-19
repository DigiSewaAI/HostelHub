<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;  // ✅ थपियो
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
        'room_number',
        'type',
        'capacity',
        'price',
        'status',
        'description',
        'image',
        'floor',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'price' => 'decimal:2',
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
     * Get all payments for the room through students.
     */
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Payment::class,
            Student::class,
            'room_id',
            'student_id',
            'id',
            'id'
        );
    }

    /**
     * Scope a query to only include available rooms.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include rooms with available capacity.
     */
    public function scopeWithAvailableCapacity(Builder $query): Builder
    {
        return $query->whereRaw('(SELECT COUNT(*) FROM students WHERE students.room_id = rooms.id) < capacity');
    }

    /**
     * Scope a query to get rooms by floor.
     */
    public function scopeFloor(Builder $query, string $floor): Builder
    {
        return $query->where('floor', $floor);
    }

    /**
     * Scope for occupied rooms.
     */
    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
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
     * Get the available capacity for this room.
     */
    public function getAvailableCapacityAttribute(): int
    {
        $currentOccupancy = $this->students_count ?? $this->students()->count();
        return max(0, $this->capacity - $currentOccupancy);
    }
    // app/Models/Room.php

    // यो नयाँ फंक्शन थप्नुहोस्
    public function getNepaliTypeAttribute(): string
    {
        $types = [
            'single' => '१ सिटर कोठा',
            'double' => '२ सिटर कोठा',
            'dorm'   => '४ सिटर कोठा',
        ];

        return $types[$this->type] ?? $this->type;
    }
}
