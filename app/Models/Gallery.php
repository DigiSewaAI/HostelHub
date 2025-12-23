<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        'hostel_id',
        'room_id',
        'hostel_name',
        'video_duration',      // ✅ NEW: Video duration in seconds or MM:SS format
        'video_resolution',    // ✅ NEW: Video resolution (e.g., 1080p, 4K)
        'is_360_video',        // ✅ NEW: Whether it's a 360° video
        'video_thumbnail',     // ✅ NEW: Custom video thumbnail path
        'hd_file_path'         // ✅ NEW: HD version file path
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_360_video' => 'boolean', // ✅ NEW: Cast to boolean
    ];

    protected $appends = [
        'thumbnail_url',
        'media_url',
        'is_video',
        'is_youtube_video',
        'youtube_embed_url',
        'category_nepali',
        'media_type_nepali',
        'hostel_slug',
        'public_url',
        'is_from_published_hostel',
        'file_size',
        'hd_image_url',           // ✅ NEW: HD image URL
        'video_duration_formatted', // ✅ NEW: Formatted video duration
        'hostel_details_url',     // ✅ NEW: URL to hostel details page
        'is_hd_available',        // ✅ NEW: Whether HD version is available
        'video_metadata',         // ✅ NEW: Video metadata array
        'room_number',           // ✅ Already exists but ensuring it's in appends
        'room_type',             // ✅ Already exists but ensuring it's in appends
        'formatted_date',        // ✅ Already exists but ensuring it's in appends
        'short_description',     // ✅ Already exists but ensuring it's in appends
        'video_player_type',     // ✅ NEW: Video player type
        'aspect_ratio',          // ✅ NEW: Aspect ratio
        'download_url',          // ✅ NEW: Download URL
        'file_extension',        // ✅ NEW: File extension
        'mime_type',             // ✅ NEW: MIME type
        'video_resolution_class', // ✅ NEW: Video resolution badge class
        'is_virtual_tour',       // ✅ NEW: Virtual tour check
        'meal_gallery_url',      // ✅ NEW: Meal gallery URL
        'optimized_thumbnail',   // ✅ NEW: Optimized thumbnail
        'share_url'              // ✅ NEW: Social share URL
    ];

    /**
     * Validation rules for Gallery model
     */
    public static function validationRules($id = null): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:100',
            'media_type' => 'required|in:photo,local_video,external_video',
            'file_path' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|string|max:500',
            'external_link' => 'nullable|url|max:500',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'user_id' => 'required|exists:users,id',
            'hostel_id' => 'nullable|exists:hostels,id',
            'room_id' => 'nullable|exists:rooms,id',
            'hostel_name' => 'nullable|string|max:255',
            'video_duration' => 'nullable|string|max:10',      // ✅ NEW
            'video_resolution' => 'nullable|string|max:20',    // ✅ NEW
            'is_360_video' => 'boolean',                       // ✅ NEW
            'video_thumbnail' => 'nullable|string|max:500',    // ✅ NEW
            'hd_file_path' => 'nullable|string|max:500'        // ✅ NEW
        ];
    }

    /**
     * ✅ UPDATED: Use media_url helper for consistent URL generation
     */
    private function storagePathToUrl($path)
    {
        // Use media_url helper if available
        if (function_exists('media_url')) {
            return \media_url($path);
        }

        // Fallback: original logic
        $path = ltrim($path, '/');
        $path = preg_replace('#^storage/#', '', $path);
        return url('storage/' . $path);
    }

    /**
     * ✅ UPDATED: Use media_exists helper for consistent file checking
     */
    private function resolveMediaPath($path)
    {
        if (empty($path)) {
            return null;
        }

        // If it's already a URL, return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Use media_exists helper if available
        if (function_exists('media_exists')) {
            if (media_exists($path)) {
                return $path;
            }

            // Try alternative paths
            $pathsToTry = [
                'galleries/images/' . $path,
                'galleries/videos/' . $path,
                'galleries/' . $path,
                'room_images/' . $path,
                'hostels/' . $path,
                'meals/' . $path
            ];

            foreach ($pathsToTry as $tryPath) {
                if (media_exists($tryPath)) {
                    return $tryPath;
                }
            }
        } else {
            // Fallback: original logic
            if (str_contains($path, '/')) {
                if (Storage::disk('public')->exists($path)) {
                    return $path;
                }
            }
        }

        return null;
    }

    /**
     * ✅ FIXED: Get thumbnail URL - ALWAYS returns string
     */
    public function getThumbnailUrlAttribute(): string
    {
        // 1. Try thumbnail
        if (!empty($this->thumbnail)) {
            // Check if it's already a URL
            if (str_starts_with($this->thumbnail, 'http')) {
                return $this->thumbnail;
            }

            // Try to get URL
            try {
                $url = \media_url($this->thumbnail);
                if ($url !== asset('images/no-image.png')) {
                    return $url;
                }
            } catch (\Exception $e) {
                // Fall through
            }
        }

        // 2. Try file_path
        if (!empty($this->file_path)) {
            if (str_starts_with($this->file_path, 'http')) {
                return $this->file_path;
            }

            try {
                $url = \media_url($this->file_path);
                if ($url !== asset('images/no-image.png')) {
                    return $url;
                }
            } catch (\Exception $e) {
                // Fall through
            }
        }

        // 3. For YouTube videos, get thumbnail
        if ($this->media_type === 'external_video' && $this->external_link) {
            $youtubeId = $this->getYoutubeId($this->external_link);
            if ($youtubeId) {
                return "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
            }
        }

        // 4. Default images based on media type
        if ($this->media_type === 'photo') {
            return asset('images/default-gallery.jpg');
        } elseif (in_array($this->media_type, ['local_video', 'external_video'])) {
            return asset('images/video-default.jpg');
        }

        // 5. Ultimate fallback
        return asset('images/no-image.png');
    }

    /**
     * ✅ FIXED: Get media URL - ALWAYS returns string
     */
    public function getMediaUrlAttribute(): string
    {
        // YouTube video
        if ($this->media_type === 'external_video' && $this->external_link) {
            return $this->external_link;
        }

        // Local files
        if (!empty($this->file_path)) {
            if (str_starts_with($this->file_path, 'http')) {
                return $this->file_path;
            }

            try {
                $url = \media_url($this->file_path);
                if ($url !== asset('images/no-image.png')) {
                    return $url;
                }
            } catch (\Exception $e) {
                // Fall through
            }
        }

        // Default based on media type
        if ($this->media_type === 'photo') {
            return asset('images/default-gallery.jpg');
        } elseif (in_array($this->media_type, ['local_video', 'external_video'])) {
            return asset('images/video-default.jpg');
        }

        return asset('images/no-image.png');
    }

    /**
     * ✅ ENHANCED: Room relationship with proper constraints
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class)->withDefault([
            'room_number' => 'N/A',
            'type' => 'N/A'
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class)->withDefault([
            'name' => 'Unknown Hostel'
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        // ✅ NEW: Check if default images exist, if not create placeholder (only in local)
        static::retrieved(function ($gallery) {
            // यो मात्र development मा helper को लागि हो
            if (app()->environment('local')) {
                $defaultImages = [
                    public_path('images/default-gallery.jpg'),
                    public_path('images/default-room.jpg'),
                    public_path('images/video-thumbnail.jpg'),
                ];

                foreach ($defaultImages as $imagePath) {
                    if (!file_exists($imagePath)) {
                        // Create directory if not exists
                        if (!is_dir(dirname($imagePath))) {
                            mkdir(dirname($imagePath), 0755, true);
                        }

                        // Create a simple colored image
                        $im = imagecreate(100, 100);
                        $backgroundColor = imagecolorallocate($im, 238, 238, 238); // Light gray
                        imagefilledrectangle($im, 0, 0, 99, 99, $backgroundColor);

                        if (str_contains($imagePath, 'video')) {
                            // Blue for video thumbnails
                            $color = imagecolorallocate($im, 66, 133, 244); // YouTube blue
                        } else {
                            // Green for gallery images
                            $color = imagecolorallocate($im, 52, 168, 83); // Google green
                        }

                        imagefilledrectangle($im, 25, 25, 75, 75, $color);
                        imagejpeg($im, $imagePath, 80);
                        imagedestroy($im);
                    }
                }
            }
        });

        static::saving(function ($gallery) {
            // Auto-set hostel name when hostel_id is set/changed
            if ($gallery->isDirty('hostel_id') && $gallery->hostel_id) {
                $hostel = Hostel::find($gallery->hostel_id);
                if ($hostel) {
                    $gallery->hostel_name = $hostel->name;
                }
            }

            // ✅ FIXED: Better room-gallery synchronization
            if ($gallery->isDirty('room_id') && $gallery->room_id) {
                $room = Room::with('hostel')->find($gallery->room_id);
                if ($room && $room->hostel) {
                    $gallery->hostel_id = $room->hostel_id;
                    $gallery->hostel_name = $room->hostel->name;

                    // ✅ Auto-set category based on room type if not set
                    if (!$gallery->category) {
                        $gallery->category = $room->gallery_category;
                    }
                }
            }

            // ✅ NEW: Auto-generate video thumbnail for YouTube videos
            if ($gallery->isDirty('external_link') && $gallery->media_type === 'external_video') {
                $youtubeId = $gallery->getYoutubeId($gallery->external_link);
                if ($youtubeId && !$gallery->video_thumbnail) {
                    $gallery->video_thumbnail = 'https://img.youtube.com/vi/' . $youtubeId . '/hqdefault.jpg';
                }
            }
        });

        // ✅ NEW: Auto-update gallery when room is updated
        static::updated(function ($gallery) {
            if ($gallery->room_id && $gallery->isDirty('category')) {
                // If gallery category changed, update room's gallery category
                $gallery->room->update(['gallery_category' => $gallery->category]);
            }
        });

        // ✅ NEW: Delete associated files when gallery is deleted
        static::deleting(function ($gallery) {
            if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
                Storage::disk('public')->delete($gallery->file_path);
            }

            if ($gallery->thumbnail && Storage::disk('public')->exists($gallery->thumbnail)) {
                Storage::disk('public')->delete($gallery->thumbnail);
            }

            if ($gallery->hd_file_path && Storage::disk('public')->exists($gallery->hd_file_path)) {
                Storage::disk('public')->delete($gallery->hd_file_path);
            }

            if ($gallery->video_thumbnail && Storage::disk('public')->exists($gallery->video_thumbnail)) {
                Storage::disk('public')->delete($gallery->video_thumbnail);
            }
        });
    }

    /**
     * Scope for active galleries
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured galleries
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    /**
     * Scope for user's galleries
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for hostel galleries
     */
    public function scopeForHostel($query, $hostelId)
    {
        return $query->where('hostel_id', $hostelId);
    }

    /**
     * ✅ NEW: Scope for published hostels only
     */
    public function scopePublishedHostels($query)
    {
        return $query->whereHas('hostel', function ($q) {
            $q->where('is_published', true);
        });
    }

    /**
     * ✅ NEW: Scope for videos only
     */
    public function scopeVideos($query)
    {
        return $query->whereIn('media_type', ['external_video', 'local_video']);
    }

    /**
     * ✅ NEW: Scope for HD available images
     */
    public function scopeWithHd($query)
    {
        return $query->where('media_type', 'photo')
            ->whereNotNull('file_path')
            ->whereNotNull('hd_file_path');
    }

    /**
     * ✅ NEW: Scope for virtual tours (360 videos)
     */
    public function scopeVirtualTours($query)
    {
        return $query->where('is_360_video', true)
            ->orWhere('category', 'virtual_tour');
    }

    /**
     * ✅ NEW: Scope for room galleries
     */
    public function scopeRoomGalleries($query)
    {
        return $query->whereNotNull('room_id');
    }

    /**
     * ✅ NEW: Scope for public display
     */
    public function scopeForPublic($query)
    {
        return $query->where('is_active', true)
            ->whereHas('hostel', function ($q) {
                $q->where('is_published', true);
            });
    }

    /**
     * ✅ NEW: Scope for searching galleries
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhereHas('hostel', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('room', function ($q3) use ($search) {
                    $q3->where('room_number', 'like', "%{$search}%");
                });
        });
    }

    /**
     * ✅ NEW: Scope for filtering by category
     */
    public function scopeByCategory($query, $category)
    {
        if (!$category || $category === 'all') {
            return $query;
        }

        return $query->where('category', $category);
    }

    /**
     * ✅ FIXED: Get hostel name - ALWAYS returns string
     */
    public function getHostelNameAttribute(): string
    {
        if (!empty($this->attributes['hostel_name'])) {
            return (string) $this->attributes['hostel_name'];
        }

        if ($this->hostel) {
            return $this->hostel->name;
        }

        if ($this->room && $this->room->hostel) {
            return $this->room->hostel->name;
        }

        return 'Unknown Hostel';
    }

    /**
     * ✅ NEW: Get hostel slug for URLs
     */
    public function getHostelSlugAttribute(): ?string
    {
        if ($this->hostel) {
            return $this->hostel->slug;
        }
        return null;
    }

    /**
     * ✅ NEW: Get public URL for this gallery item
     */
    public function getPublicUrlAttribute(): string
    {
        if ($this->hostel_slug) {
            return route('gallery.hostel', $this->hostel_slug) . '?gallery=' . $this->id;
        }

        // Fallback to gallery index if no hostel slug
        return route('gallery.index') . '?gallery=' . $this->id;
    }

    /**
     * ✅ NEW: Get hostel details page URL
     */
    public function getHostelDetailsUrlAttribute(): ?string
    {
        if (!$this->hostel_slug) {
            return null;
        }

        return route('hostels.show', $this->hostel_slug);
    }

    /**
     * ✅ NEW: Check if this is from a published hostel
     */
    public function getIsFromPublishedHostelAttribute(): bool
    {
        return $this->hostel && $this->hostel->is_published;
    }

    /**
     * ✅ UPDATED: Get HD image URL using media_url helper
     */
    public function getHdImageUrlAttribute(): ?string
    {
        if ($this->media_type !== 'photo') {
            return null;
        }

        // Check if HD file exists
        if ($this->hd_file_path) {
            if (function_exists('media_exists') && media_exists($this->hd_file_path)) {
                if (function_exists('media_url')) {
                    return \media_url($this->hd_file_path);
                } else {
                    return Storage::disk('public')->url($this->hd_file_path);
                }
            }
        }

        // Fallback to regular image URL
        return $this->media_url;
    }

    /**
     * ✅ UPDATED: Check if HD version is available
     */
    public function getIsHdAvailableAttribute(): bool
    {
        if ($this->media_type !== 'photo') {
            return false;
        }

        // Check HD file path
        if ($this->hd_file_path) {
            if (function_exists('media_exists')) {
                return media_exists($this->hd_file_path);
            } else {
                return Storage::disk('public')->exists($this->hd_file_path);
            }
        }

        return false;
    }

    /**
     * ✅ UPDATED: Generate HD path from regular path
     */
    private function generateHdPath(): string
    {
        if (!$this->file_path) {
            return '';
        }

        // First try to resolve the original file path
        $resolvedPath = $this->resolveMediaPath($this->file_path);
        if (!$resolvedPath) {
            return '';
        }

        $pathInfo = pathinfo($resolvedPath);
        $extension = $pathInfo['extension'] ?? '';
        $filename = $pathInfo['filename'] ?? '';
        $dirname = $pathInfo['dirname'] ?? '';

        // Remove any existing -hd suffix
        $filename = preg_replace('/-hd$/', '', $filename);

        return ($dirname !== '.' ? $dirname . '/' : '') . $filename . '-hd.' . $extension;
    }

    /**
     * ✅ NEW: Get formatted video duration
     */
    public function getVideoDurationFormattedAttribute(): ?string
    {
        if (!$this->video_duration || !$this->is_video) {
            return null;
        }

        // If already in MM:SS format, return as is
        if (preg_match('/^\d+:\d{2}$/', $this->video_duration)) {
            return $this->video_duration;
        }

        // Convert seconds to MM:SS
        if (is_numeric($this->video_duration)) {
            $minutes = floor($this->video_duration / 60);
            $seconds = $this->video_duration % 60;
            return sprintf('%02d:%02d', $minutes, $seconds);
        }

        return $this->video_duration;
    }

    /**
     * ✅ NEW: Get video metadata
     */
    public function getVideoMetadataAttribute(): array
    {
        if (!$this->is_video) {
            return [];
        }

        return [
            'duration' => $this->video_duration_formatted,
            'resolution' => $this->video_resolution,
            'is_360' => (bool) $this->is_360_video,
            'type' => $this->media_type === 'external_video' ? 'youtube' : 'local',
            'embed_url' => $this->youtube_embed_url
        ];
    }

    /**
     * Check if item is a video
     */
    public function getIsVideoAttribute(): bool
    {
        return in_array($this->media_type, ['local_video', 'external_video']);
    }

    /**
     * Check if item is a YouTube video
     */
    public function getIsYoutubeVideoAttribute(): bool
    {
        return $this->media_type === 'external_video';
    }

    /**
     * Get YouTube embed URL
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if ($this->media_type === 'external_video' && $this->external_link) {
            $youtubeId = $this->getYoutubeId($this->external_link);
            if ($youtubeId) {
                return 'https://www.youtube.com/embed/' . $youtubeId . '?autoplay=1&rel=0';
            }
        }
        return null;
    }

    /**
     * Extract YouTube ID from URL
     */
    private function getYoutubeId($url): ?string
    {
        if (empty($url)) return null;

        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * ✅ FIXED: Get category in Nepali - ALWAYS returns string
     */
    public function getCategoryNepaliAttribute(): string
    {
        $categories = [
            'video' => 'भिडियो टुर',
            '1 seater' => '१ सिटर कोठा',
            '2 seater' => '२ सिटर कोठा',
            '3 seater' => '३ सिटर कोठा',
            '4 seater' => '४ सिटर कोठा',
            'common' => 'लिभिङ रूम',
            'bathroom' => 'बाथरूम',
            'kitchen' => 'भान्सा',
            'living room' => 'लिभिङ रूम',
            'study room' => 'अध्ययन कोठा',
            'event' => 'कार्यक्रम',
            'hostel_tour' => 'होस्टल टुर',
            'room_tour' => 'कोठा टुर',
            'student_life' => 'विद्यार्थी जीवन',
            'virtual_tour' => 'भर्चुअल टुर',
            'testimonial' => 'विद्यार्थी अनुभव',
            'facility' => 'सुविधाहरू',
            'food' => 'खाना',
            'menu' => 'मेनु'
        ];

        $category = $this->category ?? 'other';
        return $categories[$category] ?? $category;
    }

    /**
     * ✅ FIXED: Get media type in Nepali - ALWAYS returns string
     */
    public function getMediaTypeNepaliAttribute(): string
    {
        $types = [
            'photo' => 'तस्बिर',
            'local_video' => 'स्थानिय भिडियो',
            'external_video' => 'यूट्युब भिडियो'
        ];

        $mediaType = $this->media_type ?? 'photo';
        return $types[$mediaType] ?? $mediaType;
    }

    /**
     * ✅ UPDATED: Check if file exists using media_exists helper
     */
    public function fileExists(): bool
    {
        if (!$this->file_path) return false;

        if (str_starts_with($this->file_path, 'http')) {
            return true;
        }

        // Use media_exists helper if available
        if (function_exists('media_exists')) {
            return media_exists($this->file_path) ||
                media_exists('galleries/images/' . $this->file_path) ||
                media_exists('galleries/' . $this->file_path) ||
                media_exists('room_images/' . $this->file_path);
        }

        // Fallback: original logic
        $pathsToCheck = [
            $this->file_path,
            'galleries/images/' . $this->file_path,
            'galleries/' . $this->file_path,
            'room_images/' . $this->file_path
        ];

        foreach ($pathsToCheck as $path) {
            if (Storage::disk('public')->exists($path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * ✅ FIXED: Get file size - ALWAYS returns string
     */
    public function getFileSizeAttribute(): string
    {
        try {
            $resolvedPath = $this->resolveMediaPath($this->file_path);
            if (!$resolvedPath || !Storage::disk('public')->exists($resolvedPath)) {
                return '0 KB';
            }

            $size = Storage::disk('public')->size($resolvedPath);

            if ($size >= 1048576) {
                return round($size / 1048576, 2) . ' MB';
            } elseif ($size >= 1024) {
                return round($size / 1024, 2) . ' KB';
            } else {
                return $size . ' bytes';
            }
        } catch (\Exception $e) {
            return '0 KB';
        }
    }

    /**
     * ✅ ENHANCED: Check if user can modify this gallery (with admin role)
     */
    public function canBeModifiedBy($user): bool
    {
        // User owns the gallery or is admin/manager of the hostel
        return $user->id === $this->user_id ||
            ($this->hostel && $this->hostel->manager_id === $user->id) ||
            ($this->hostel && $this->hostel->owner_id === $user->id) ||
            $user->hasRole('admin') || // ✅ NEW: Admin can modify any gallery
            $user->hasRole('super_admin'); // ✅ NEW: Super admin can modify any gallery
    }

    /**
     * ✅ NEW: Get the room number (with fallback)
     */
    public function getRoomNumberAttribute(): string
    {
        if ($this->room && $this->room->room_number) {
            return $this->room->room_number;
        }

        return $this->attributes['room_number'] ?? 'N/A';
    }

    /**
     * ✅ NEW: Get the room type (with fallback)
     */
    public function getRoomTypeAttribute(): string
    {
        if ($this->room && $this->room->type) {
            return $this->room->type;
        }

        return $this->attributes['room_type'] ?? 'N/A';
    }

    /**
     * ✅ NEW: Get formatted created date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('F d, Y');
    }

    /**
     * ✅ NEW: Get short description
     */
    public function getShortDescriptionAttribute(): string
    {
        return Str::limit($this->description, 100);
    }

    /**
     * ✅ NEW: Get video resolution badge class
     */
    public function getVideoResolutionClassAttribute(): string
    {
        if (!$this->video_resolution) {
            return '';
        }

        $resolution = strtolower($this->video_resolution);

        if (strpos($resolution, '4k') !== false || strpos($resolution, '2160') !== false) {
            return 'badge-4k';
        } elseif (strpos($resolution, '1080') !== false || strpos($resolution, 'full hd') !== false) {
            return 'badge-hd';
        } elseif (strpos($resolution, '720') !== false) {
            return 'badge-hd';
        } else {
            return 'badge-sd';
        }
    }

    /**
     * ✅ NEW: Check if this is a virtual tour
     */
    public function getIsVirtualTourAttribute(): bool
    {
        return $this->is_360_video || $this->category === 'virtual_tour';
    }

    /**
     * ✅ NEW: Get meal gallery URL (for food-related galleries)
     */
    public function getMealGalleryUrlAttribute(): ?string
    {
        if (in_array($this->category, ['food', 'menu'])) {
            return route('menu-gallery');
        }

        return null;
    }

    /**
     * ✅ UPDATED: Get optimized thumbnail using media_url helper
     */
    public function getOptimizedThumbnailAttribute(): string
    {
        // For videos, use custom thumbnail or generate from YouTube
        if ($this->is_video) {
            return $this->thumbnail_url;
        }

        // For photos, use smaller version if available
        if ($this->media_type === 'photo' && $this->file_path) {
            $resolvedPath = $this->resolveMediaPath($this->file_path);
            if ($resolvedPath) {
                $thumbPath = str_replace('.', '-thumb.', $resolvedPath);
                if (function_exists('media_exists') && media_exists($thumbPath)) {
                    if (function_exists('media_url')) {
                        return \media_url($thumbPath);
                    } else {
                        return Storage::disk('public')->url($thumbPath);
                    }
                }
            }
        }

        return $this->thumbnail_url;
    }

    /**
     * ✅ NEW: Generate social share URL
     */
    public function getShareUrlAttribute(): string
    {
        $url = urlencode($this->public_url);
        $title = urlencode($this->title);

        return "https://www.facebook.com/sharer/sharer.php?u={$url}&t={$title}";
    }

    /**
     * ✅ NEW: Get related galleries (same hostel or category)
     */
    public function getRelatedGalleries($limit = 6)
    {
        return self::where('id', '!=', $this->id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('hostel_id', $this->hostel_id)
                    ->orWhere('category', $this->category);
            })
            ->limit($limit)
            ->get();
    }

    /**
     * ✅ NEW: Get download URL for media
     */
    public function getDownloadUrlAttribute(): ?string
    {
        $resolvedPath = $this->resolveMediaPath($this->file_path);
        if (!$resolvedPath || !Storage::disk('public')->exists($resolvedPath)) {
            return null;
        }

        return route('gallery.download', $this->id);
    }

    /**
     * ✅ NEW: Get the file extension
     */
    public function getFileExtensionAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        $resolvedPath = $this->resolveMediaPath($this->file_path);
        if (!$resolvedPath) {
            return null;
        }

        return pathinfo($resolvedPath, PATHINFO_EXTENSION);
    }

    /**
     * ✅ NEW: Get the MIME type
     */
    public function getMimeTypeAttribute(): ?string
    {
        $resolvedPath = $this->resolveMediaPath($this->file_path);
        if (!$resolvedPath || !Storage::disk('public')->exists($resolvedPath)) {
            return null;
        }

        return Storage::disk('public')->mimeType($resolvedPath);
    }

    /**
     * ✅ NEW: Scope for meal galleries (food and menu)
     */
    public function scopeMealGalleries($query)
    {
        return $query->whereIn('category', ['food', 'menu']);
    }

    /**
     * ✅ NEW: Scope for HD photos only
     */
    public function scopeHdPhotos($query)
    {
        return $query->where('media_type', 'photo')
            ->whereNotNull('hd_file_path');
    }

    /**
     * ✅ NEW: Scope for 360 videos only
     */
    public function scopeThreeSixtyVideos($query)
    {
        return $query->where('is_360_video', true);
    }

    /**
     * ✅ NEW: Get video player type
     */
    public function getVideoPlayerTypeAttribute(): string
    {
        if ($this->media_type === 'external_video') {
            return 'youtube';
        } elseif ($this->media_type === 'local_video') {
            return 'html5';
        }

        return 'image';
    }

    /**
     * ✅ NEW: Get aspect ratio for media
     */
    public function getAspectRatioAttribute(): string
    {
        if ($this->is_video) {
            return '16:9';
        }

        // For images, try to get from metadata or default to 4:3
        return '4:3';
    }
}
