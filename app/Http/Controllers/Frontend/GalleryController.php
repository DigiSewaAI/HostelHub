<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    /**
     * Display the public gallery with filtering and caching.
     */
    public function publicIndex(Request $request)
    {
        $categories = $this->getCategoriesList();
        $selectedCategory = $request->input('category', 'all');

        if (!array_key_exists($selectedCategory, $categories)) {
            $selectedCategory = 'all';
        }

        $cacheKey = 'public_gallery_' . $selectedCategory;
        $galleryItems = $this->getGalleryItems($selectedCategory);
        $stats = $this->getStats();

        return view('gallery', compact(
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

        $cacheKey = 'public_gallery_' . $selectedCategory;

        return Cache::remember($cacheKey, 3600, function () use ($selectedCategory, $categoryMap) {
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
     * Display admin gallery management page.
     */
    public function index(Request $request)
    {
        $galleryItems = Gallery::orderBy('created_at', 'desc')->paginate(20);
        return view('frontend.gallery.index', compact('galleryItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = [
            '1 seater' => '1 Seater Room',
            '2 seater' => '2 Seater Room',
            '3 seater' => '3 Seater Room',
            '4 seater' => '4 Seater Room',
            'common' => 'Common Living Room',
            'bathroom' => 'Bathroom',
            'kitchen' => 'Kitchen',
            'study room' => 'Study Room',
            'event' => 'Event',
            'video' => 'Video Tour',
        ];

        return view('admin.gallery.create', compact('categories'));
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

        // Clear all gallery caches
        $categories = array_keys($this->getCategoriesList());
        foreach ($categories as $category) {
            Cache::forget('public_gallery_' . $category);
        }

        return redirect()->route('admin.gallery.index')->with('success', '🎉 ग्यालेरी आइटम सफलतापूर्वक थपियो!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        return view('admin.gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        $categories = [
            '1 seater' => '1 Seater Room',
            '2 seater' => '2 Seater Room',
            '3 seater' => '3 Seater Room',
            '4 seater' => '4 Seater Room',
            'common' => 'Common Living Room',
            'bathroom' => 'Bathroom',
            'kitchen' => 'Kitchen',
            'study room' => 'Study Room',
            'event' => 'Event',
            'video' => 'Video Tour',
        ];

        return view('admin.gallery.edit', compact('gallery', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
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
            'image' => $request->media_type === 'photo' ? 'sometimes|image|max:5120' : 'nullable',
            'local_video' => $request->media_type === 'local_video' ? 'sometimes|mimes:mp4,mov,webm|max:51200' : 'nullable',
            'external_link' => $request->media_type === 'external_video' ? 'required|url' : 'nullable',
        ]);

        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->category = $request->category;
        $gallery->media_type = $request->media_type;
        $gallery->is_active = $request->status === 'active';
        $gallery->is_featured = (bool) $request->featured;

        if ($request->media_type === 'photo' && $request->hasFile('image')) {
            // Delete old file if exists
            if ($gallery->file_path) {
                Storage::disk('public')->delete($gallery->file_path);
            }

            $path = $request->file('image')->store('gallery/images', 'public');
            $gallery->file_path = $path;
            $gallery->thumbnail = $path;
        }

        if ($request->media_type === 'local_video' && $request->hasFile('local_video')) {
            // Delete old file if exists
            if ($gallery->file_path) {
                Storage::disk('public')->delete($gallery->file_path);
            }

            $path = $request->file('local_video')->store('gallery/videos', 'public');
            $gallery->file_path = $path;
            $gallery->thumbnail = 'images/video-default.jpg';
        }

        if ($request->media_type === 'external_video') {
            $gallery->external_link = $request->external_link;
            $gallery->file_path = null;

            $youtubeId = $this->getYoutubeIdFromUrl($request->external_link);
            $gallery->thumbnail = $youtubeId
                ? "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg"
                : 'images/video-default.jpg';
        }

        $gallery->save();

        // Clear all gallery caches
        $categories = array_keys($this->getCategoriesList());
        foreach ($categories as $category) {
            Cache::forget('public_gallery_' . $category);
        }

        return redirect()->route('admin.gallery.index')->with('success', '🎉 ग्यालेरी आइटम सफलतापूर्वक अद्यावधिक गरियो!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        // Delete associated files
        if ($gallery->file_path) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        $gallery->delete();

        // Clear all gallery caches
        $categories = array_keys($this->getCategoriesList());
        foreach ($categories as $category) {
            Cache::forget('public_gallery_' . $category);
        }

        return redirect()->route('admin.gallery.index')->with('success', '🗑️ ग्यालेरी आइटम सफलतापूर्वक मेटियो!');
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
