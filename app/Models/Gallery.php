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
        'user_id',
        'hostel_id' // ✅ Make sure this exists
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Append thumbnail_url to model attributes
    protected $appends = ['thumbnail_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ ADDED: Hostel relationship
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
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
        // If thumbnail exists locally
        if ($this->thumbnail) {
            return asset('storage/' . ltrim($this->thumbnail, '/'));
        }

        // For external videos (YouTube)
        if ($this->media_type === 'external_video' && $this->external_link) {
            $youtubeId = $this->getYoutubeId($this->external_link);
            if ($youtubeId) {
                return 'https://img.youtube.com/vi/' . $youtubeId . '/mqdefault.jpg';
            }
            return asset('images/video-thumbnail.jpg');
        }

        // For local videos
        if ($this->media_type === 'local_video') {
            return asset('images/video-thumbnail.jpg');
        }

        // Default thumbnail
        return asset('images/default-thumbnail.jpg');
    }

    private function getYoutubeId($url): ?string
    {
        if (empty($url)) return null;

        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    // ✅ ADDED: Get category name in Nepali
    public function getCategoryNepaliAttribute(): string
    {
        $categories = [
            'room' => 'कोठाका तस्बिरहरू',
            'common_area' => 'साझा क्षेत्रहरू',
            'facility' => 'सुविधाहरू',
            'event' => 'कार्यक्रमहरू',
            'other' => 'अन्य'
        ];

        return $categories[$this->category] ?? $this->category;
    }

    // ✅ ADDED: Get media type in Nepali
    public function getMediaTypeNepaliAttribute(): string
    {
        $types = [
            'image' => 'तस्बिर',
            'video' => 'भिडियो',
            'external_video' => 'यूट्युब भिडियो'
        ];

        return $types[$this->media_type] ?? $this->media_type;
    }
}
