<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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
        'hostel_id'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = ['thumbnail_url', 'media_url', 'is_video', 'is_youtube_video', 'youtube_embed_url', 'category_nepali', 'media_type_nepali'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function getMediaUrlAttribute(): string
    {
        if ($this->media_type === 'external_video' && $this->external_link) {
            return $this->external_link;
        }

        if ($this->file_path) {
            // FIXED: Use proper storage URL
            return Storage::disk('public')->exists($this->file_path)
                ? asset('storage/' . $this->file_path)
                : asset('images/default-gallery.jpg');
        }

        return asset('images/default-gallery.jpg');
    }

    public function getThumbnailUrlAttribute(): string
    {
        // YouTube videos
        if ($this->media_type === 'external_video' && $this->external_link) {
            $youtubeId = $this->getYoutubeId($this->external_link);
            if ($youtubeId) {
                return 'https://img.youtube.com/vi/' . $youtubeId . '/hqdefault.jpg';
            }
            return asset('images/video-thumbnail.jpg');
        }

        // Local videos
        if ($this->media_type === 'local_video') {
            if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
                return asset('storage/' . $this->thumbnail);
            }
            return asset('images/video-thumbnail.jpg');
        }

        // Images - FIXED: Check if file exists
        if ($this->media_type === 'photo' && $this->file_path) {
            return Storage::disk('public')->exists($this->file_path)
                ? asset('storage/' . $this->file_path)
                : asset('images/default-image.jpg');
        }

        return asset('images/default-image.jpg');
    }

    public function getIsVideoAttribute(): bool
    {
        return in_array($this->media_type, ['local_video', 'external_video']);
    }

    public function getIsYoutubeVideoAttribute(): bool
    {
        return $this->media_type === 'external_video';
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if ($this->media_type === 'external_video' && $this->external_link) {
            $youtubeId = $this->getYoutubeId($this->external_link);
            if ($youtubeId) {
                return 'https://www.youtube.com/embed/' . $youtubeId . '?autoplay=1';
            }
        }
        return null;
    }

    private function getYoutubeId($url): ?string
    {
        if (empty($url)) return null;

        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    public function getCategoryNepaliAttribute(): string
    {
        $categories = [
            'video'       => 'भिडियो टुर',
            '1 seater'    => '१ सिटर कोठा',
            '2 seater'    => '२ सिटर कोठा',
            '3 seater'    => '३ सिटर कोठा',
            '4 seater'    => '४ सिटर कोठा',
            'common'      => 'लिभिङ रूम',
            'bathroom'    => 'बाथरूम',
            'kitchen'     => 'भान्सा',
            'living room' => 'लिभिङ रूम',
            'study room'  => 'अध्ययन कोठा',
            'event'       => 'कार्यक्रम'
        ];

        return $categories[$this->category] ?? $this->category;
    }

    public function getMediaTypeNepaliAttribute(): string
    {
        $types = [
            'photo' => 'तस्बिर',
            'local_video' => 'भिडियो',
            'external_video' => 'यूट्युब भिडियो'
        ];

        return $types[$this->media_type] ?? $this->media_type;
    }

    /**
     * Check if file exists in storage
     */
    public function fileExists(): bool
    {
        if (!$this->file_path) return false;

        return Storage::disk('public')->exists($this->file_path);
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeAttribute(): string
    {
        if (!$this->file_path || !Storage::disk('public')->exists($this->file_path)) {
            return '0 KB';
        }

        $size = Storage::disk('public')->size($this->file_path);

        if ($size >= 1048576) {
            return round($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return $size . ' bytes';
        }
    }
}
