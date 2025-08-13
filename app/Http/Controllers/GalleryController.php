<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GalleryController extends Controller
{
    /**
     * Public gallery list page with filtering capabilities
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
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

        // Cache gallery items for 1 hour to improve performance
        $galleryItems = Cache::remember('public_gallery_items_' . $selectedCategory, 3600, function () use ($selectedCategory) {
            return Gallery::where('status', 'active')
                ->when($selectedCategory !== 'all', function ($query) use ($selectedCategory) {
                    $query->where('category', $selectedCategory);
                })
                ->orderByRaw("FIELD(category, 'video', 'single', 'double', 'triple', 'quad', 'common', 'dining')")
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    $processedItem = [
                        'id' => $item->id,
                        'title' => $item->title,
                        'category' => $item->category,
                        'type' => $item->type,
                        'description' => $item->description,
                        'is_featured' => $item->is_featured,
                        'created_at' => $item->created_at->format('M d, Y')
                    ];

                    // Handle different media types
                    if ($item->type === 'photo') {
                        $processedItem['image'] = $item->image ? asset('storage/' . $item->image) : null;
                        $processedItem['thumbnail'] = $processedItem['image'];
                    } elseif ($item->type === 'local_video') {
                        $processedItem['video'] = $item->image ? asset('storage/' . $item->image) : null;
                        $processedItem['thumbnail'] = $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('images/video-default.jpg');
                    } elseif ($item->type === 'youtube') {
                        $processedItem['youtube_id'] = $this->getYoutubeIdFromUrl($item->external_link);
                        $processedItem['thumbnail'] = $item->thumbnail ? asset('storage/' . $item->thumbnail) : 'https://img.youtube.com/vi/' . $processedItem['youtube_id'] . '/mqdefault.jpg';
                    }

                    return $processedItem;
                });
        });

        // Get statistics for the stats section
        $stats = [
            'total_students' => Cache::remember('total_students', 3600, function () {
                return 125;
            }),
            'total_hostels' => Cache::remember('total_hostels', 3600, function () {
                return 24;
            }),
            'cities_available' => Cache::remember('cities_available', 3600, function () {
                return 5;
            }),
            'satisfaction_rate' => Cache::remember('satisfaction_rate', 3600, function () {
                return '98%';
            })
        ];

        return view('gallery', compact('galleryItems', 'categories', 'selectedCategory', 'stats'));
    }

    /**
     * Extract YouTube video ID from URL
     *
     * @param string $url
     * @return string|null
     */
    private function getYoutubeIdFromUrl(string $url): ?string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $url, $matches);

        return $matches[1] ?? null;
    }
}
