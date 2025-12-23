<?php

use App\Models\Gallery;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getGalleryCategories')) {
    function getGalleryCategories()
    {
        return [
            'room' => 'कोठाका तस्बिरहरू',
            'common_area' => 'साझा क्षेत्रहरू',
            'facility' => 'सुविधाहरू',
            'event' => 'कार्यक्रमहरू',
            'other' => 'अन्य'
        ];
    }
}

if (!function_exists('getGalleryMediaTypes')) {
    function getGalleryMediaTypes()
    {
        return [
            'image' => 'तस्बिर',
            'video' => 'भिडियो',
            'external_video' => 'यूट्युब भिडियो'
        ];
    }
}

if (!function_exists('getGalleryStatuses')) {
    function getGalleryStatuses()
    {
        return [
            'active' => 'सक्रिय',
            'inactive' => 'निष्क्रिय'
        ];
    }
}

if (!function_exists('formatFileSize')) {
    function formatFileSize($bytes)
    {
        if ($bytes == 0) return '0 Bytes';

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}

if (!function_exists('getYouTubeThumbnail')) {
    function getYouTubeThumbnail($url)
    {
        $videoId = getYouTubeId($url);
        if ($videoId) {
            return "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg";
        }
        return asset('images/video-thumbnail.jpg');
    }
}

if (!function_exists('getYouTubeId')) {
    function getYouTubeId($url)
    {
        if (empty($url)) return null;

        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }
}

if (!function_exists('getYouTubeEmbedUrl')) {
    function getYouTubeEmbedUrl($url)
    {
        $videoId = getYouTubeId($url);
        if ($videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }
        return null;
    }
}

if (!function_exists('isVideoFile')) {
    function isVideoFile($mimeType)
    {
        return str_starts_with($mimeType, 'video/');
    }
}

if (!function_exists('isImageFile')) {
    function isImageFile($mimeType)
    {
        return str_starts_with($mimeType, 'image/');
    }
}

if (!function_exists('getGalleryItemType')) {
    function getGalleryItemType($gallery)
    {
        if ($gallery->media_type === 'external_video') {
            return 'youtube';
        } elseif ($gallery->media_type === 'video') {
            return 'video';
        } else {
            return 'image';
        }
    }
}

if (!function_exists('getGalleryItemUrl')) {
    function getGalleryItemUrl($gallery)
    {
        if ($gallery->media_type === 'external_video') {
            return getYouTubeEmbedUrl($gallery->external_link);
        } else {
            return $gallery->media_url;
        }
    }
}

if (!function_exists('getFeaturedGalleries')) {
    function getFeaturedGalleries($limit = 6)
    {
        return Cache::remember('featured_galleries_' . $limit, 3600, function () use ($limit) {
            return Gallery::where('is_featured', true)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }
}

if (!function_exists('getActiveGalleries')) {
    function getActiveGalleries($category = null, $limit = null)
    {
        $cacheKey = 'active_galleries_' . ($category ?: 'all') . '_' . ($limit ?: 'all');

        return Cache::remember($cacheKey, 3600, function () use ($category, $limit) {
            $query = Gallery::where('is_active', true);

            if ($category && $category !== 'all') {
                $query->where('category', $category);
            }

            $query->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc');

            if ($limit) {
                $query->limit($limit);
            }

            return $query->get();
        });
    }
}

if (!function_exists('clearGalleryCache')) {
    function clearGalleryCache()
    {
        Cache::forget('featured_galleries_6');
        Cache::forget('active_galleries_all_all');
        Cache::forget('active_galleries_room_all');
        Cache::forget('active_galleries_common_area_all');
        Cache::forget('active_galleries_facility_all');
        Cache::forget('active_galleries_event_all');
        Cache::forget('active_galleries_other_all');
    }
}

if (!function_exists('getGalleryStats')) {
    function getGalleryStats($hostelId = null)
    {
        $query = Gallery::query();

        if ($hostelId) {
            $query->where('hostel_id', $hostelId);
        }

        return [
            'total' => $query->count(),
            'images' => $query->where('media_type', 'image')->count(),
            'videos' => $query->whereIn('media_type', ['video', 'external_video'])->count(),
            'featured' => $query->where('is_featured', true)->count(),
            'active' => $query->where('is_active', true)->count(),
        ];
    }
}

// ========== MEDIA HELPER FUNCTIONS ==========
// ✅ FIXED: Proper media URL handling for Railway

if (!function_exists('media_url')) {
    /**
     * Generate a consistent media URL for Railway deployment
     * ✅ CRITICAL FIX: ALWAYS returns string, never null
     */
    function media_url($path): string
    {
        if (empty($path)) {
            return asset('images/no-image.png');
        }

        // If it's already a full URL, return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // For Railway, check if file exists in storage
        try {
            // Remove storage/ prefix if present
            $cleanPath = str_replace('storage/', '', $path);

            // Try to get the URL
            if (Storage::disk('public')->exists($cleanPath)) {
                return Storage::disk('public')->url($cleanPath);
            }

            // Try with original path
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }

            // Try common directories
            $directories = [
                'galleries/',
                'galleries/images/',
                'galleries/videos/',
                'room_images/',
                'hostels/',
                'meals/',
                'users/'
            ];

            foreach ($directories as $dir) {
                $testPath = $dir . $cleanPath;
                if (Storage::disk('public')->exists($testPath)) {
                    return Storage::disk('public')->url($testPath);
                }
            }

            // Final fallback
            return asset('images/no-image.png');
        } catch (\Exception $e) {
            // Log error but return default image
            \Log::error("media_url error for {$path}: " . $e->getMessage());
            return asset('images/no-image.png');
        }
    }
}

if (!function_exists('thumbnail_url')) {
    /**
     * Generate thumbnail URL
     * ✅ CRITICAL FIX: ALWAYS returns string, never null
     */
    function thumbnail_url($path, $thumbnailPath = null): string
    {
        // Try thumbnail first
        if (!empty($thumbnailPath)) {
            $url = media_url($thumbnailPath);
            if ($url !== asset('images/no-image.png')) {
                return $url;
            }
        }

        // Fallback to main image
        return media_url($path);
    }
}

if (!function_exists('media_exists')) {
    /**
     * Check if media file exists (Railway-specific)
     */
    function media_exists($path): bool
    {
        if (empty($path)) {
            return false;
        }

        // Remove storage/ prefix if present
        $cleanPath = str_replace('storage/', '', $path);

        try {
            return Storage::disk('public')->exists($cleanPath) ||
                Storage::disk('public')->exists($path);
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('railway_media_url')) {
    /**
     * Temporary helper for immediate testing
     */
    function railway_media_url($path)
    {
        if (!$path) return asset('images/no-image.png');

        // Remove storage/ or public/ prefixes
        $path = str_replace(['storage/', 'public/'], '', $path);

        // For Railway - direct path
        return '/media/' . ltrim($path, '/');
    }
}

if (!function_exists('getRoomImageUrl')) {
    /**
     * Get room image URL with fallback
     * ✅ CRITICAL FIX: ALWAYS returns string, never null
     */
    function getRoomImageUrl($room): string
    {
        if (!$room) {
            return asset('images/default-room.jpg');
        }

        // Try to get image using model's accessor
        if (method_exists($room, 'getImageUrlAttribute')) {
            try {
                $url = $room->image_url;
                if (!empty($url) && $url !== asset('images/no-image.png')) {
                    return $url;
                }
            } catch (\Exception $e) {
                // Fall through
            }
        }

        // Try room image
        if ($room->image ?? false) {
            $url = media_url($room->image);
            if ($url !== asset('images/no-image.png')) {
                return $url;
            }
        }

        // Try first gallery image
        if (method_exists($room, 'galleries') && $room->galleries->count() > 0) {
            foreach ($room->galleries as $gallery) {
                if ($gallery->file_path) {
                    $url = media_url($gallery->file_path);
                    if ($url !== asset('images/no-image.png')) {
                        return $url;
                    }
                }
            }
        }

        return asset('images/default-room.jpg');
    }
}

if (!function_exists('getHostelImageUrl')) {
    /**
     * Get hostel image URL with fallback
     * ✅ CRITICAL FIX: ALWAYS returns string, never null
     */
    function getHostelImageUrl($hostel): string
    {
        if (!$hostel) {
            return asset('images/default-hostel.jpg');
        }

        // Try hostel's main image
        if ($hostel->image ?? false) {
            $url = media_url($hostel->image);
            if ($url !== asset('images/no-image.png')) {
                return $url;
            }
        }

        // Try hostel images relationship
        if (method_exists($hostel, 'images') && $hostel->images->count() > 0) {
            foreach ($hostel->images as $image) {
                if ($image->file_path) {
                    $url = media_url($image->file_path);
                    if ($url !== asset('images/no-image.png')) {
                        return $url;
                    }
                }
            }
        }

        return asset('images/default-hostel.jpg');
    }
}

// ✅ FIXED: Add new helper for video thumbnails
if (!function_exists('getVideoThumbnailUrl')) {
    function getVideoThumbnailUrl($gallery): string
    {
        if (!$gallery) {
            return asset('images/video-default.jpg');
        }

        // Try custom thumbnail
        if (!empty($gallery->video_thumbnail)) {
            $url = media_url($gallery->video_thumbnail);
            if ($url !== asset('images/no-image.png')) {
                return $url;
            }
        }

        // Try regular thumbnail
        if (!empty($gallery->thumbnail)) {
            $url = media_url($gallery->thumbnail);
            if ($url !== asset('images/no-image.png')) {
                return $url;
            }
        }

        // Try file path
        if (!empty($gallery->file_path)) {
            $url = media_url($gallery->file_path);
            if ($url !== asset('images/no-image.png')) {
                return $url;
            }
        }

        // YouTube video thumbnail
        if ($gallery->media_type === 'external_video' && $gallery->external_link) {
            $youtubeId = getYouTubeId($gallery->external_link);
            if ($youtubeId) {
                return "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
            }
        }

        return asset('images/video-default.jpg');
    }
}
