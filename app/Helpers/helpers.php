<?php

use App\Models\Gallery;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Helpers\MediaHelper;

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

if (!function_exists('generateVideoThumbnail')) {
    function generateVideoThumbnail($videoPath, $thumbnailPath, $time = 2)
    {
        $videoFullPath = storage_path('app/public/' . $videoPath);
        $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);

        // Create directory if it doesn't exist
        $thumbnailDir = dirname($thumbnailFullPath);
        if (!file_exists($thumbnailDir)) {
            mkdir($thumbnailDir, 0777, true);
        }

        // Generate thumbnail using FFmpeg
        $cmd = "ffmpeg -i \"{$videoFullPath}\" -ss 00:00:{$time} -vframes 1 -q:v 2 \"{$thumbnailFullPath}\" 2>&1";
        $output = shell_exec($cmd);

        return file_exists($thumbnailFullPath);
    }
}

if (!function_exists('sanitizeFileName')) {
    function sanitizeFileName($filename)
    {
        // Remove any path information
        $filename = basename($filename);

        // Replace spaces with underscores
        $filename = str_replace(' ', '_', $filename);

        // Remove any non-alphanumeric characters except dots, underscores, and hyphens
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);

        // Ensure the filename is not empty
        if (empty($filename)) {
            $filename = uniqid() . '_file';
        }

        return $filename;
    }
}

if (!function_exists('getHumanReadableFileSize')) {
    function getHumanReadableFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}

if (!function_exists('validateYouTubeUrl')) {
    function validateYouTubeUrl($url)
    {
        $pattern = '/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/';
        return preg_match($pattern, $url);
    }
}

if (!function_exists('getGalleryNavigation')) {
    function getGalleryNavigation($currentGallery, $category = null)
    {
        $query = Gallery::where('is_active', true)
            ->where('hostel_id', $currentGallery->hostel_id);

        if ($category) {
            $query->where('category', $category);
        }

        $galleries = $query->orderBy('created_at', 'desc')->get();

        $currentIndex = $galleries->search(function ($item) use ($currentGallery) {
            return $item->id === $currentGallery->id;
        });

        $prev = $currentIndex > 0 ? $galleries[$currentIndex - 1] : null;
        $next = $currentIndex < ($galleries->count() - 1) ? $galleries[$currentIndex + 1] : null;

        return compact('prev', 'next');
    }
}

// ========== MEDIA HELPER FUNCTIONS ==========
// Added as per the instructions for Railway deployment fix

if (!function_exists('media_url')) {
    /**
     * Generate a consistent media URL for Railway deployment
     */
    function media_url($path)
    {
        return MediaHelper::getMediaUrl($path);
    }
}

if (!function_exists('thumbnail_url')) {
    /**
     * Generate thumbnail URL
     */
    function thumbnail_url($path, $thumbnailPath = null)
    {
        return MediaHelper::getThumbnailUrl($path, $thumbnailPath);
    }
}

if (!function_exists('media_exists')) {
    /**
     * Check if media file exists (Railway-specific)
     */
    function media_exists($path)
    {
        return MediaHelper::mediaExists($path);
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
     */
    function getRoomImageUrl($room)
    {
        if (method_exists($room, 'getImageUrlAttribute')) {
            return $room->image_url;
        }

        if ($room->image) {
            return media_url($room->image);
        }

        return asset('images/default-room.jpg');
    }
}

if (!function_exists('getHostelImageUrl')) {
    /**
     * Get hostel image URL with fallback
     */
    function getHostelImageUrl($hostel)
    {
        if (!$hostel->image) {
            return asset('images/default-hostel.jpg');
        }

        return media_url($hostel->image);
    }
}
