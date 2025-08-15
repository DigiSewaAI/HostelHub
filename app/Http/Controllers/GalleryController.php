<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Public gallery list page with filtering capabilities and file existence checks
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function publicIndex(Request $request)
    {
        // Define gallery categories with Nepali labels using "सिटर" terminology
        $categories = [
            'all'    => 'सबै',
            'single' => '१ सिटर कोठा',
            'double' => '२ सिटर कोठा',
            'triple' => '३ सिटर कोठा',
            'quad'   => '४ सिटर कोठा',
            'common' => 'लिभिङ रूम',
            'dining' => 'बाथरूम',
            'video'  => 'भिडियो टुर'
        ];

        // Get selected category from request, default to 'all'
        $selectedCategory = $request->input('category', 'all');

        // Validate category
        if (!array_key_exists($selectedCategory, $categories)) {
            $selectedCategory = 'all';
        }

        // Map request category keys to database category values
        $categoryMap = [
            'single' => '1 seater',
            'double' => '2 seater',
            'triple' => '3 seater',
            'quad'   => '4 seater',
            'common' => 'common',
            'dining' => 'dining',
            'video'  => 'video'
        ];

        // FIXED: Include category in cache key to prevent cross-category caching
        $cacheKey = 'public_gallery_items_' . $selectedCategory;

        $galleryItems = Cache::remember($cacheKey, 3600, function () use ($selectedCategory, $categoryMap) {
            return Gallery::where('is_active', true)
                ->when($selectedCategory !== 'all', function ($query) use ($categoryMap, $selectedCategory) {
                    // Use mapped category value for database query
                    $query->where('category', $categoryMap[$selectedCategory]);
                })
                ->orderByRaw("FIELD(category, 'video', '1 seater', '2 seater', '3 seater', '4 seater', 'common', 'dining')")
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    // Handle Windows path formatting
                    $absolutePath = storage_path('app/public/' . $item->file_path);
                    $absolutePath = str_replace('/', DIRECTORY_SEPARATOR, $absolutePath);

                    // Create processed item with base data
                    $processedItem = [
                        'id' => $item->id,
                        'title' => $item->title,
                        'category' => $item->category,
                        'media_type' => $item->media_type,
                        'description' => $item->description,
                        'is_featured' => $item->is_featured,
                        'created_at' => $item->created_at->format('M d, Y'),
                        'external_link' => $item->external_link,
                        'file_exists' => '❌ हुँदैन', // Default to not exists
                        'absolute_path' => $absolutePath,
                        'file_url' => '', // Default empty file URL
                        'thumbnail_url' => ''
                    ];

                    // Handle different media types
                    if ($item->media_type === 'photo') {
                        // Check if file exists
                        $fileExists = file_exists($absolutePath);
                        $processedItem['file_exists'] = $fileExists ? '✅ हुन्छ' : '❌ हुँदैन';

                        // Set file URL
                        $processedItem['file_url'] = $fileExists
                            ? asset('storage/' . $item->file_path)
                            : '';

                        // Set image URL (same as file URL for photos)
                        $processedItem['image_url'] = $processedItem['file_url'];

                        // Set thumbnail URL
                        $processedItem['thumbnail_url'] = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : $processedItem['file_url'];
                    } elseif ($item->media_type === 'local_video') {
                        // Check if file exists
                        $fileExists = file_exists($absolutePath);
                        $processedItem['file_exists'] = $fileExists ? '✅ हुन्छ' : '❌ हुँदैन';

                        // Set file URL
                        $processedItem['file_url'] = $fileExists
                            ? asset('storage/' . $item->file_path)
                            : '';

                        // Set video URL (same as file URL for local videos)
                        $processedItem['video_url'] = $processedItem['file_url'];

                        // Set thumbnail URL
                        $processedItem['thumbnail_url'] = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : asset('images/video-default.jpg');
                    } elseif ($item->media_type === 'youtube') {
                        // YouTube always "exists" (it's external)
                        $processedItem['file_exists'] = '✅ हुन्छ (External)';

                        // Get YouTube ID
                        $youtubeId = $this->getYoutubeIdFromUrl($item->external_link);
                        $processedItem['youtube_id'] = $youtubeId;

                        // Set thumbnail URL (with fixed URL format - no extra space)
                        $processedItem['thumbnail_url'] = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg";
                    }

                    return $processedItem;
                });
        });

        // Stats section data with caching
        $stats = [
            'total_students' => Cache::remember('total_students', 3600, fn() => 125),
            'total_hostels' => Cache::remember('total_hostels', 3600, fn() => 24),
            'cities_available' => Cache::remember('cities_available', 3600, fn() => 5),
            'satisfaction_rate' => Cache::remember('satisfaction_rate', 3600, fn() => '98%')
        ];

        return view('gallery', compact('galleryItems', 'categories', 'selectedCategory', 'stats'));
    }

    /**
     * Admin gallery management page
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $galleryItems = Gallery::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.gallery.index', compact('galleryItems'));
    }

    /**
     * Extract YouTube video ID from URL (ROBUST VERSION)
     *
     * @param string $url
     * @return string|null
     */
    private function getYoutubeIdFromUrl(string $url): ?string
    {
        $pattern = '%^ (?:https?://)? (?:www\.)? (?: youtu\.be/ | youtube\.com (?: /embed/ | /v/ | /watch\?v= ) ) ([\w-]{11}) $%x';
        preg_match($pattern, $url, $matches);

        return $matches[1] ?? null;
    }
}
