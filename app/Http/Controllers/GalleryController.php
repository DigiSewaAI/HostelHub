<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GalleryController extends Controller
{
    /**
     * Display the public gallery with filtering and caching.
     */
    public function publicIndex(Request $request)
    {
        $categories = [
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

        $selectedCategory = $request->input('category', 'all');
        if (!array_key_exists($selectedCategory, $categories)) {
            $selectedCategory = 'all';
        }

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

        $cacheKey = 'public_gallery_' . $selectedCategory;

        $galleryItems = Cache::remember($cacheKey, 3600, function () use ($selectedCategory, $categoryMap) {
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
                    ]);
                });
        });

        $stats = [
            'total_students' => Cache::remember('stats_students', 3600, fn() => 125),
            'total_hostels' => Cache::remember('stats_hostels', 3600, fn() => 24),
            'cities_available' => Cache::remember('stats_cities', 3600, fn() => 5),
            'satisfaction_rate' => Cache::remember('stats_satisfaction', 3600, fn() => '98%')
        ];

        return view('gallery', compact(
            'galleryItems',
            'categories',
            'selectedCategory',
            'stats'
        ));
    }

    /**
     * Display admin gallery management page.
     */
    public function index(Request $request)
    {
        $galleryItems = Gallery::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.gallery.index', compact('galleryItems'));
    }

    /**
     * Store a new gallery item (Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'media_type' => 'required|in:photo,local_video,external_video',
            'category' => [
                'required',
                'string',
                'in:1 seater,2 seater,3 seater,4 seater,common,bathroom,kitchen,study room,event,video'
            ],
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'featured' => 'boolean',
            'image' => $request->media_type === 'photo' ? 'required|image|max:5120' : 'nullable',
            'local_video' => $request->media_type === 'local_video' ? 'required|mimes:mp4,mov,webm|max:51200' : 'nullable',
            'external_link' => $request->media_type === 'external_video' ? 'required|url' : 'nullable',
        ]);

        $gallery = new Gallery();
        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->category = $request->category;
        $gallery->media_type = $request->media_type;
        $gallery->is_active = $request->status === 'active';
        $gallery->is_featured = (bool) $request->featured;
        $gallery->user_id = auth()->id();

        if ($request->media_type === 'photo' && $request->hasFile('image')) {
            $path = $request->file('image')->store('gallery/images', 'public');
            $gallery->file_path = $path;
            $gallery->thumbnail = $path;
        }

        if ($request->media_type === 'local_video' && $request->hasFile('local_video')) {
            $path = $request->file('local_video')->store('gallery/videos', 'public');
            $gallery->file_path = $path;
            $gallery->thumbnail = 'images/video-default.jpg';
        }

        if ($request->media_type === 'external_video') {
            $gallery->external_link = $request->external_link;
            $youtubeId = $this->getYoutubeIdFromUrl($request->external_link);
            $gallery->thumbnail = $youtubeId
                ? "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg"
                : 'images/video-default.jpg';
        }

        $gallery->save();

        // Clear cache
        Cache::forget('public_gallery_all');
        Cache::forget('public_gallery_items_all');
        Cache::tags(['gallery'])->flush();

        return redirect()->route('admin.gallery.index')->with('success', '🎉 ग्यालेरी आइटम सफलतापूर्वक थपियो!');
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
        $absolutePath = storage_path('app/public/' . $item->file_path);
        $absolutePath = str_replace('/', DIRECTORY_SEPARATOR, $absolutePath);
        $fileExists = file_exists($absolutePath);

        $result['file_exists'] = $fileExists ? '✅ हुन्छ' : '❌ हुँदैन';
        $result['absolute_path'] = $absolutePath;

        if ($fileExists) {
            $result['file_url'] = asset('storage/' . $item->file_path);
            $result['thumbnail_url'] = $item->thumbnail
                ? asset('storage/' . $item->thumbnail)
                : $result['file_url'];
        }

        return $result;
    }

    /**
     * Process local video items
     */
    private function processLocalVideoItem(Gallery $item, array $result): array
    {
        $absolutePath = storage_path('app/public/' . $item->file_path);
        $absolutePath = str_replace('/', DIRECTORY_SEPARATOR, $absolutePath);
        $fileExists = file_exists($absolutePath);

        $result['file_exists'] = $fileExists ? '✅ हुन्छ' : '❌ हुँदैन';
        $result['absolute_path'] = $absolutePath;

        if ($fileExists) {
            $result['file_url'] = asset('storage/' . $item->file_path);
            $result['video_url'] = $result['file_url'];
            $result['thumbnail_url'] = $item->thumbnail
                ? asset('storage/' . $item->thumbnail)
                : asset('images/video-default.jpg');
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
        $result['thumbnail_url'] = $item->thumbnail
            ? asset('storage/' . $item->thumbnail)
            : ($youtubeId
                ? "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg"
                : asset('images/video-default.jpg'));

        return $result;
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
