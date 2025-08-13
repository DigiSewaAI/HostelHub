<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'category',
        'type',
        'file_path',
        'thumbnail',
        'external_link',
        'is_featured',
        'is_active',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user who uploaded this gallery item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the display URL for the gallery item.
     */
    public function getUrlAttribute(): string
    {
        if ($this->type === 'youtube') {
            return $this->external_link;
        }

        return $this->file_path
            ? asset('storage/' . $this->file_path)
            : asset('images/default-gallery.jpg');
    }

    /**
     * Get the thumbnail URL for the gallery item.
     */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : ($this->type === 'youtube'
                ? 'https://img.youtube.com/vi/' . getYoutubeId($this->external_link) . '/mqdefault.jpg'
                : asset('images/default-thumbnail.jpg'));
    }
}
