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
        'is_primary'
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
            'is_primary' => 'boolean'
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
     * Scope for hostel images
     */
    public function scopeForHostel($query, $hostelId)
    {
        return $query->where('hostel_id', $hostelId);
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
     * Check if user can modify this image
     */
    public function canBeModifiedBy($user): bool
    {
        return $this->hostel && $this->hostel->canBeModifiedBy($user);
    }
}
