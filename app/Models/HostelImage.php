<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostelImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
        'image_path',
        'caption',
        'is_primary',
        'is_active',
        'is_featured'
    ];

    protected $appends = [
        'image_url',
        'thumbnail_url',
        'is_room_image',
        'room_type',
        'room_number_detected'
    ];

    /**
     * Validation rules for HostelImage model
     */
    public static function validationRules($id = null): array
    {
        return [
            'hostel_id' => 'required|exists:hostels,id',
            'image_path' => 'required|string|max:500',
            'caption' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ];
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Scope for primary images
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope for active images
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured images
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for hostel images
     */
    public function scopeForHostel($query, $hostelId)
    {
        return $query->where('hostel_id', $hostelId);
    }

    /**
     * Scope for room images (non-primary)
     */
    public function scopeRoomImages($query)
    {
        return $query->where('is_primary', false);
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return asset('images/default-hostel.png');
    }

    /**
     * Get thumbnail URL (same as image for now, can be enhanced later)
     */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->image_url;
    }

    /**
     * Check if this is likely a room image based on caption
     */
    public function getIsRoomImageAttribute(): bool
    {
        if (!$this->caption) {
            return false;
        }

        $roomKeywords = [
            'room',
            'bedroom',
            'bed',
            '1 seater',
            '2 seater',
            '3 seater',
            '4 seater',
            'single',
            'double',
            'triple',
            'quad',
            'shared',
            'dorm',
            'कोठा',
            'बेडरूम',
            'सिंगल',
            'डबल',
            'ट्रिपल',
            'शेयर',
            'साझा'
        ];

        $caption = strtolower($this->caption);

        foreach ($roomKeywords as $keyword) {
            if (str_contains($caption, strtolower($keyword))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect room type from caption
     */
    public function getRoomTypeAttribute(): ?string
    {
        if (!$this->is_room_image) {
            return null;
        }

        $caption = strtolower($this->caption);

        $roomTypes = [
            '1 seater' => 'single',
            'single' => 'single',
            'one' => 'single',
            'एक' => 'single',

            '2 seater' => 'double',
            'double' => 'double',
            'two' => 'double',
            'दुई' => 'double',

            '3 seater' => 'triple',
            'triple' => 'triple',
            'three' => 'triple',
            'तीन' => 'triple',

            '4 seater' => 'quad',
            'quad' => 'quad',
            'four' => 'quad',
            'चार' => 'quad',

            'shared' => 'shared',
            'dorm' => 'shared',
            'साझा' => 'shared',
            'शेयर' => 'shared'
        ];

        foreach ($roomTypes as $keyword => $type) {
            if (str_contains($caption, $keyword)) {
                return $type;
            }
        }

        return 'other';
    }

    /**
     * Try to extract room number from caption
     */
    public function getRoomNumberDetectedAttribute(): ?string
    {
        if (!$this->is_room_image || !$this->caption) {
            return null;
        }

        // Try to find patterns like "Room 101", "Room: 202", "101", etc.
        $patterns = [
            '/room\s*[#:]?\s*(\d+)/i',
            '/room\s+(\d+)/i',
            '/कोठा\s*[#:]?\s*(\d+)/i',
            '/कोठा\s+(\d+)/i',
            '/\b(\d{3})\b/',
            '/\b(\d{2,3}[a-zA-Z]?)\b/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->caption, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Check if user can modify this image
     */
    public function canBeModifiedBy($user): bool
    {
        return $this->hostel && $this->hostel->canBeModifiedBy($user);
    }
}
