<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
        'room_number',
        'type',
        'gallery_category',
        'capacity',
        'current_occupancy',
        'available_beds',
        'price',
        'status',
        'image',
        'description',
        'floor',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'current_occupancy' => 'integer',
        'available_beds' => 'integer',
    ];

    protected $appends = ['image_url', 'has_image', 'display_status'];

    /**
     * ✅ FIXED: Boot method with type-capacity auto-sync and normalized status handling
     */
    protected static function boot()
    {
        parent::boot();

        // ✅ FIXED: Auto-calculate available beds, update status, and sync capacity with type
        static::saving(function ($room) {
            // ✅ NEW: Auto-set capacity based on room type
            $typeCapacityMap = [
                '1 seater' => 1,
                '2 seater' => 2,
                '3 seater' => 3,
                '4 seater' => 4,
                // 'साझा कोठा' keeps existing capacity (custom)
            ];

            if (isset($typeCapacityMap[$room->type]) && $room->type !== 'साझा कोठा') {
                $room->capacity = $typeCapacityMap[$room->type];
            }

            // Calculate available beds
            $room->available_beds = $room->capacity - $room->current_occupancy;

            // ✅ FIXED: Auto-update status with normalized values (only if not maintenance)
            if ($room->status !== 'maintenance') {
                if ($room->current_occupancy == 0) {
                    $room->status = 'available';
                } elseif ($room->current_occupancy == $room->capacity) {
                    $room->status = 'occupied';
                } else {
                    $room->status = 'partially_available';
                }
            }

            // ✅ FIXED: Always set gallery_category if empty
            if (empty($room->gallery_category)) {
                $room->gallery_category = $room->getGalleryCategoryByType();
            }
        });

        // Update hostel room counts when room is created, updated or deleted
        static::created(function ($room) {
            $room->hostel->updateRoomCounts();
            if ($room->image) {
                $room->syncImageToGallery();
            }
        });

        static::updated(function ($room) {
            $room->hostel->updateRoomCounts();
            if ($room->isDirty('image') || $room->isDirty('type')) {
                $room->syncImageToGallery();
            }
        });

        static::deleted(function ($room) {
            $room->hostel->updateRoomCounts();
            $room->galleries()->delete();
        });
    }

    /**
     * ✅ NEW: Unified status mapping for consistent display
     */
    public static function statusOptions()
    {
        return [
            'available' => 'उपलब्ध',
            'partially_available' => 'आंशिक उपलब्ध',
            'occupied' => 'व्यस्त',
            'maintenance' => 'मर्मत सम्भार',
        ];
    }

    /**
     * ✅ NEW: Get status label in Nepali
     */
    public function getStatusLabelAttribute()
    {
        $map = self::statusOptions();
        return $map[$this->status] ?? $this->status;
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_amenity', 'room_id', 'amenity_id')
            ->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
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
        return $query->where('status', 'available');
    }

    public function scopeOccupied(Builder $query): Builder
    {
        return $query->where('status', 'occupied');
    }

    public function scopeMaintenance(Builder $query): Builder
    {
        return $query->where('status', 'maintenance');
    }

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
        $currentOccupancy = $this->current_occupancy ?? 0;
        return $this->capacity ? round(($currentOccupancy / $this->capacity) * 100, 1) : 0.0;
    }

    /**
     * Get the current number of students in this room.
     */
    public function getCurrentOccupancyAttribute(): int
    {
        return $this->attributes['current_occupancy'] ?? $this->students()->count();
    }

    /**
     * Get the available capacity for this room.
     */
    public function getAvailableCapacityAttribute(): int
    {
        $currentOccupancy = $this->current_occupancy ?? 0;
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
     * ✅ FIXED: Get display status with normalized logic
     */
    public function getDisplayStatusAttribute(): array
    {
        $status = $this->status; // Now using normalized English values
        $available_beds = $this->available_beds;

        if ($status === 'maintenance') {
            return [
                'status' => 'maintenance',
                'text' => 'मर्मत सम्भार',
                'class' => 'maintenance',
                'available_beds' => 0
            ];
        } elseif ($status === 'occupied') {
            return [
                'status' => 'occupied',
                'text' => 'पूर्ण व्यस्त',
                'class' => 'occupied',
                'available_beds' => 0
            ];
        } elseif ($status === 'partially_available') {
            return [
                'status' => 'partially_available',
                'text' => 'आंशिक उपलब्ध (' . $available_beds . ' बेड खाली)',
                'class' => 'partially-available',
                'available_beds' => $available_beds
            ];
        } else { // available
            return [
                'status' => 'available',
                'text' => 'पूर्ण उपलब्ध (' . $available_beds . ' बेड)',
                'class' => 'available',
                'available_beds' => $available_beds
            ];
        }
    }

    /**
     * ✅ FIXED: Get Nepali room type with unified types
     */
    public function getNepaliTypeAttribute(): string
    {
        $types = [
            '1 seater' => 'एक सिटर कोठा',
            '2 seater' => 'दुई सिटर कोठा',
            '3 seater' => 'तीन सिटर कोठा',
            '4 seater' => 'चार सिटर कोठा',
            'साझा कोठा' => 'साझा कोठा',
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * Get Nepali status (for backward compatibility)
     */
    public function getNepaliStatusAttribute(): string
    {
        $statuses = [
            'available' => 'उपलब्ध',
            'occupied' => 'अधिभृत',
            'maintenance' => 'मर्मतमा',
            'उपलब्ध' => 'उपलब्ध',
            'व्यस्त' => 'व्यस्त',
            'मर्मत सम्भार' => 'मर्मत सम्भार',
            'आंशिक उपलब्ध' => 'आंशिक उपलब्ध',
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
     * ✅ FIXED: Sync room image to gallery system using TYPE not capacity
     */
    public function syncImageToGallery(): void
    {
        // Delete existing room galleries
        $this->galleries()->delete();

        if (!$this->image) {
            return;
        }

        // ✅ FIXED: Use room TYPE for gallery category
        $galleryCategory = $this->getGalleryCategoryByType();

        // Create gallery entry
        Gallery::create([
            'title' => "Room {$this->room_number} - {$this->type}",
            'description' => $this->description ?? "{$this->type} room at {$this->hostel->name}",
            'category' => $galleryCategory,
            'media_type' => 'photo',
            'file_path' => $this->image,
            'thumbnail' => $this->image,
            'is_featured' => false,
            'is_active' => true,
            'user_id' => auth()->id() ?? 1,
            'hostel_id' => $this->hostel_id,
            'room_id' => $this->id,
            'hostel_name' => $this->hostel->name ?? 'Unknown Hostel'
        ]);
    }

    /**
     * ✅ FIXED: Get gallery category by ROOM TYPE (not capacity)
     */
    private function getGalleryCategoryByType(): string
    {
        // Use ROOM TYPE to determine category
        switch ($this->type) {
            case '1 seater':
                return '1 seater';
            case '2 seater':
                return '2 seater';
            case '3 seater':
                return '3 seater';
            case '4 seater':
                return '4 seater';
            case 'साझा कोठा':
                return 'साझा कोठा'; // ✅ FIXED: Changed from '4 seater' to 'साझा कोठा'
            default:
                // Fallback for old types
                if ($this->capacity == 1) return '1 seater';
                if ($this->capacity == 2) return '2 seater';
                if ($this->capacity == 3) return '3 seater';
                return '4 seater';
        }
    }

    /**
     * ✅ FIXED: Get gallery category for room type
     */
    public function getGalleryCategoryAttribute(): string
    {
        return $this->getGalleryCategoryByType();
    }

    /**
     * Check if room has gallery images
     */
    public function getHasGalleryImagesAttribute(): bool
    {
        return $this->galleries()->where('is_active', true)->exists();
    }

    /**
     * Get active gallery images for this room
     */
    public function getGalleryImagesAttribute()
    {
        return $this->galleries()->where('is_active', true)->get();
    }

    /**
     * Get primary gallery image (room image or first gallery image)
     */
    public function getPrimaryGalleryImageAttribute(): string
    {
        if ($this->image) {
            return $this->image_url;
        }

        $firstGallery = $this->galleries()->where('is_active', true)->first();
        return $firstGallery ? $firstGallery->media_url : asset('images/no-image.png');
    }

    /**
     * ✅ NEW: Helper method to validate if capacity matches room type
     */
    public function validateCapacityWithType(): bool
    {
        $typeCapacityRules = [
            '1 seater' => 1,
            '2 seater' => 2,
            '3 seater' => 3,
            '4 seater' => 4,
            'साझा कोठा' => 'custom'
        ];

        if (array_key_exists($this->type, $typeCapacityRules)) {
            $expectedCapacity = $typeCapacityRules[$this->type];

            if ($expectedCapacity === 'custom') {
                return $this->capacity >= 5;
            } else {
                return $this->capacity == $expectedCapacity;
            }
        }

        return true;
    }

    /**
     * ✅ NEW: Get capacity validation error message
     */
    public function getCapacityValidationError(): ?string
    {
        $typeCapacityRules = [
            '1 seater' => 1,
            '2 seater' => 2,
            '3 seater' => 3,
            '4 seater' => 4,
            'साझा कोठा' => 'custom'
        ];

        if (array_key_exists($this->type, $typeCapacityRules)) {
            $expectedCapacity = $typeCapacityRules[$this->type];

            if ($expectedCapacity === 'custom') {
                if ($this->capacity < 5) {
                    return 'साझा कोठाको लागि क्षमता कम्तिमा 5 हुनुपर्छ';
                }
            } else {
                if ($this->capacity != $expectedCapacity) {
                    $nepaliTypes = [
                        '1 seater' => '१ सिटर कोठा',
                        '2 seater' => '२ सिटर कोठा',
                        '3 seater' => '३ सिटर कोठा',
                        '4 seater' => '४ सिटर कोठा'
                    ];

                    return "{$nepaliTypes[$this->type]} को लागि क्षमता {$expectedCapacity} हुनुपर्छ";
                }
            }
        }

        return null;
    }

    /**
     * ✅ NEW: Get gallery category in Nepali for display
     */
    public function getGalleryCategoryNepaliAttribute(): string
    {
        $categories = [
            '1 seater' => '१ सिटर कोठा',
            '2 seater' => '२ सिटर कोठा',
            '3 seater' => '३ सिटर कोठा',
            '4 seater' => '४ सिटर कोठा',
            'साझा कोठा' => 'साझा कोठा',
            'living_room' => 'लिभिङ रूम',
            'bathroom' => 'बाथरूम',
            'kitchen' => 'भान्सा',
            'study_room' => 'अध्ययन कोठा',
            'events' => 'कार्यक्रम',
            'video_tour' => 'भिडियो टुर'
        ];

        return $categories[$this->gallery_category] ?? $this->gallery_category;
    }
}
