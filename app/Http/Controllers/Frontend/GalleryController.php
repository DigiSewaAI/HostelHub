<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display the public gallery with filtering and caching.
     */
    public function index(Request $request)
    {
        $categories = $this->getCategoriesList();
        $selectedCategory = $request->input('category', 'all');

        if (!array_key_exists($selectedCategory, $categories)) {
            $selectedCategory = 'all';
        }

        $galleryItems = $this->getGalleryItems($selectedCategory);
        $stats = $this->getStats();

        return view('frontend.gallery.index', compact(
            'galleryItems',
            'categories',
            'selectedCategory',
            'stats'
        ));
    }

    /**
     * API Endpoint: Get gallery data
     */
    public function getGalleryData(Request $request)
    {
        $selectedCategory = $request->input('category', 'all');
        $galleryItems = $this->getGalleryItems($selectedCategory);

        return response()->json($galleryItems);
    }

    /**
     * API Endpoint: Get categories
     */
    public function getGalleryCategories()
    {
        $categories = $this->getCategoriesList();
        return response()->json($categories);
    }

    /**
     * API Endpoint: Get stats
     */
    public function getGalleryStats()
    {
        $stats = $this->getStats();
        return response()->json($stats);
    }

    /**
     * Helper method to get gallery items
     */
    private function getGalleryItems($selectedCategory)
    {
        $categoryMap = [
            'single'    => '1 seater',
            'double'    => '2 seater',
            'triple'    => '3 seater',
            'quad'      => '4 seater',
            'common'    => 'common',
            'bathroom'  => 'bathroom',
            'kitchen'   => 'kitchen',
            'study'     => 'study room',
            'event'     => 'event',
            'video'     => 'video',
        ];

        return Cache::remember('public_gallery_' . $selectedCategory, 3600, function () use ($selectedCategory, $categoryMap) {
            $query = Gallery::where('is_active', true);

            if ($selectedCategory !== 'all') {
                $dbCategory = $categoryMap[$selectedCategory];
                $query->where('category', $dbCategory);
            }

            return $query
                ->orderByRaw("FIELD(category, 'video', '1 seater', '2 seater', '3 seater', '4 seater', 'common', 'bathroom', 'kitchen', 'study room', 'event')")
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    $mediaData = $this->processMediaItem($item);
                    return array_merge($mediaData, [
                        'id' => $item->id,
                        'title' => $item->title,
                        'category' => $item->category,
                        'description' => $item->description,
                        'is_featured' => $item->is_featured,
                        'created_at' => $item->created_at->format('M d, Y'),
                        'media_type' => $item->media_type,
                        'external_link' => $item->external_link,
                    ]);
                });
        });
    }

    /**
     * Helper method to get categories list
     */
    private function getCategoriesList()
    {
        return [
            'all'       => 'सबै',
            'single'    => '१ सिटर कोठा',
            'double'    => '२ सिटर कोठा',
            'triple'    => '३ सिटर कोठा',
            'quad'      => '४ सिटर कोठा',
            'common'    => 'लिभिङ रूम',
            'bathroom'  => 'बाथरूम',
            'kitchen'   => 'भान्सा',
            'study'     => 'अध्ययन कोठा',
            'event'     => 'कार्यक्रम',
            'video'     => 'भिडियो टुर',
        ];
    }

    /**
     * Helper method to get stats
     */
    private function getStats()
    {
        return [
            'total_students' => Cache::remember('stats_students', 3600, fn() => 125),
            'total_hostels' => Cache::remember('stats_hostels', 3600, fn() => 24),
            'cities_available' => Cache::remember('stats_cities', 3600, fn() => 5),
            'satisfaction_rate' => Cache::remember('stats_satisfaction', 3600, fn() => '98%')
        ];
    }

    /**
     * Process media item based on its type
     */
    private function processMediaItem(Gallery $item): array
    {
        $result = [
            'file_exists' => '❌ हुँदैन',
            'file_url' => '',
            'thumbnail_url' => asset('images/default-thumbnail.jpg'),
            'absolute_path' => '',
            'media_type' => $item->media_type,
        ];

        if ($item->media_type === 'photo') {
            return $this->processPhotoItem($item, $result);
        }

        if ($item->media_type === 'local_video') {
            return $this->processLocalVideoItem($item, $result);
        }

        if ($item->media_type === 'external_video') {
            return $this->processExternalVideoItem($item, $result);
        }

        return $result;
    }

    /**
     * Process photo media items
     */
    private function processPhotoItem(Gallery $item, array $result): array
    {
        // Fix the file path by removing duplicate 'admin/' prefixes
        $filePath = $this->fixFilePath($item->file_path);
        
        $fileExists = Storage::disk('public')->exists($filePath);

        $result['file_exists'] = $fileExists ? '✅ हुन्छ' : '❌ हुँदैन';
        $result['absolute_path'] = Storage::disk('public')->path($filePath);

        if ($fileExists) {
            $result['file_url'] = Storage::disk('public')->url($filePath);

            // Handle thumbnail
            if ($item->thumbnail) {
                $thumbnailPath = $this->fixFilePath($item->thumbnail);
                if (Storage::disk('public')->exists($thumbnailPath)) {
                    $result['thumbnail_url'] = Storage::disk('public')->url($thumbnailPath);
                } else {
                    $result['thumbnail_url'] = $result['file_url'];
                }
            } else {
                $result['thumbnail_url'] = $result['file_url'];
            }
        }

        return $result;
    }

    /**
     * Process local video items
     */
    private function processLocalVideoItem(Gallery $item, array $result): array
    {
        // Fix the file path by removing duplicate 'admin/' prefixes
        $filePath = $this->fixFilePath($item->file_path);
        
        $fileExists = Storage::disk('public')->exists($filePath);

        $result['file_exists'] = $fileExists ? '✅ हुन्छ' : '❌ हुँदैन';
        $result['absolute_path'] = Storage::disk('public')->path($filePath);

        if ($fileExists) {
            $result['file_url'] = Storage::disk('public')->url($filePath);
            $result['video_url'] = $result['file_url'];

            // Handle thumbnail
            if ($item->thumbnail) {
                $thumbnailPath = $this->fixFilePath($item->thumbnail);
                
                if (filter_var($thumbnailPath, FILTER_VALIDATE_URL)) {
                    $result['thumbnail_url'] = $thumbnailPath;
                } else {
                    if (Storage::disk('public')->exists($thumbnailPath)) {
                        $result['thumbnail_url'] = Storage::disk('public')->url($thumbnailPath);
                    } else {
                        $result['thumbnail_url'] = asset('images/video-default.jpg');
                    }
                }
            } else {
                $result['thumbnail_url'] = asset('images/video-default.jpg');
            }
        }

        return $result;
    }

    /**
     * Process External Video (YouTube/Vimeo)
     */
    private function processExternalVideoItem(Gallery $item, array $result): array
    {
        $result['file_exists'] = '✅ (External)';
        $youtubeId = $this->getYoutubeIdFromUrl($item->external_link);

        $result['youtube_id'] = $youtubeId;

        // Handle thumbnail
        if ($item->thumbnail) {
            if (filter_var($item->thumbnail, FILTER_VALIDATE_URL)) {
                $result['thumbnail_url'] = $item->thumbnail;
            } else {
                $result['thumbnail_url'] = asset($item->thumbnail);
            }
        } else {
            $result['thumbnail_url'] = asset('images/video-default.jpg');
        }

        return $result;
    }

    /**
     * Fix file path by removing duplicate 'admin/' prefixes
     */
    private function fixFilePath($path)
    {
        if (empty($path)) {
            return $path;
        }

        // Remove any duplicate 'admin/' prefixes
        while (strpos($path, 'admin/admin/') === 0) {
            $path = substr($path, 6); // Remove the first 'admin/'
        }
        
        // Also remove single leading 'admin/' if present
        if (strpos($path, 'admin/') === 0) {
            $path = substr($path, 6);
        }
        
        return $path;
    }

    /**
     * Extract YouTube ID from URL
     */
    private function getYoutubeIdFromUrl(string $url): ?string
    {
        $patterns = [
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i',
            '%^https?://(?:www\.)?youtube\.com/watch\?v=([\w-]{11})%',
            '%^https?://youtu\.be/([\w-]{11})%'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}