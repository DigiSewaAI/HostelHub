<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'media_type',
        'file_path',
        'thumbnail',
        'external_link',
        'is_featured',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        if ($this->media_type === 'external_video' && $this->external_link) {
            return $this->external_link;
        }

        if ($this->file_path) {
            return asset('storage/' . ltrim($this->file_path, '/'));
        }

        return asset('images/default-gallery.jpg');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . ltrim($this->thumbnail, '/'));
        }

        if ($this->media_type === 'external_video' && $this->external_link) {
            $youtubeId = $this->getYoutubeId($this->external_link);
            if ($youtubeId) {
                return 'https://img.youtube.com/vi/' . $youtubeId . '/mqdefault.jpg';
            }
            return asset('images/video-thumbnail.jpg');
        }

        if ($this->media_type === 'local_video') {
            return asset('images/video-thumbnail.jpg');
        }

        return asset('images/default-thumbnail.jpg');
    }

    public function getYoutubeId($url): ?string
    {
        if (empty($url)) return null;

        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }
}
