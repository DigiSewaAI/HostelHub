<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    /**
     * Display a listing of gallery items.
     */
    public function index()
    {
        $this->authorize('viewAny', Gallery::class);

        // Updated categories with event
        $categories = [
            'all'         => 'सबै',
            'video'       => 'भिडियो टुर',
            '1 seater'    => '१ सिटर कोठा',
            '2 seater'    => '२ सिटर कोठा',
            '3 seater'    => '३ सिटर कोठा',
            '4 seater'    => '४ सिटर कोठा',
            'common'      => 'लिभिङ रूम',
            'bathroom'    => 'बाथरूम',
            'event'       => 'कार्यक्रम'
        ];

        $selectedCategory = request('category', 'all');

        $galleries = Gallery::when($selectedCategory !== 'all', function ($query) use ($selectedCategory) {
            $query->where('category', $selectedCategory);
        })
            ->latest()
            ->paginate(12);

        return view('admin.gallery.index', compact('galleries', 'categories', 'selectedCategory'));
    }

    /**
     * Show the form for creating a new gallery item.
     */
    public function create()
    {
        $this->authorize('create', Gallery::class);

        // Updated categories with event
        $categories = [
            'video'       => 'भिडियो टुर',
            '1 seater'    => '१ सिटर कोठा',
            '2 seater'    => '२ सिटर कोठा',
            '3 seater'    => '३ सिटर कोठा',
            '4 seater'    => '४ सिटर कोठा',
            'common'      => 'लिभिङ रूम',
            'bathroom'    => 'बाथरूम',
            'event'       => 'कार्यक्रम'
        ];

        return view('admin.gallery.create', compact('categories'));
    }

    /**
     * Store a newly created gallery item.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Gallery::class);

        // Updated validation rules with event category
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_type' => 'required|in:photo,external_video,local_video',
            'external_link' => 'required_if:media_type,external_video|nullable|url',
            'image' => 'required_if:media_type,photo|nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'local_video' => 'required_if:media_type,local_video|nullable|file|mimes:mp4,mov,avi|max:51200',
            'category' => [
                'required',
                Rule::in(['video', '1 seater', '2 seater', '3 seater', '4 seater', 'common', 'bathroom', 'event'])
            ],
            'status' => 'required|in:active,inactive',
        ]);

        // Convert status to is_active
        $validated['is_active'] = $validated['status'] === 'active';
        unset($validated['status']);

        // Set default value for is_featured
        $validated['is_featured'] = $request->has('featured');

        // Add current user
        $validated['user_id'] = auth()->id();

        // Handle media upload with consistent paths
        if ($validated['media_type'] === 'photo' && $request->hasFile('image')) {
            $path = $request->file('image')->store('gallery/images', 'public');
            $validated['file_path'] = $path;
            $validated['thumbnail'] = $path;
        } elseif ($validated['media_type'] === 'local_video' && $request->hasFile('local_video')) {
            $path = $request->file('local_video')->store('gallery/videos', 'public');
            $validated['file_path'] = $path;
            $validated['thumbnail'] = 'images/video-default.jpg';
        } elseif ($validated['media_type'] === 'external_video') {
            $validated['file_path'] = null;
            // Generate YouTube thumbnail
            $youtubeId = $this->getYoutubeIdFromUrl($request->external_link);
            $validated['thumbnail'] = $youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg" : 'images/video-default.jpg';
        }

        Gallery::create($validated);

        // Clear all gallery caches
        Cache::forget('public_gallery_all');
        Cache::forget('public_gallery_items_all');
        Cache::forget('public_gallery_featured');

        return redirect()->route('admin.gallery.index')
            ->with('success', 'ग्यालरी वस्तु सफलतापूर्वक थपियो');
    }

    /**
     * Display the specified gallery item.
     */
    public function show(Gallery $gallery)
    {
        $this->authorize('view', $gallery);

        return view('admin.gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified gallery item.
     */
    public function edit(Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        // Updated categories with event
        $categories = [
            'video'       => 'भिडियो टुर',
            '1 seater'    => '१ सिटर कोठा',
            '2 seater'    => '२ सिटर कोठा',
            '3 seater'    => '३ सिटर कोठा',
            '4 seater'    => '४ सिटर कोठा',
            'common'      => 'लिभिङ रूम',
            'bathroom'    => 'बाथरूम',
            'event'       => 'कार्यक्रम'
        ];

        return view('admin.gallery.edit', compact('gallery', 'categories'));
    }

    /**
     * Update the specified gallery item.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        // Updated validation rules with event category
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_type' => 'required|in:photo,external_video,local_video',
            'external_link' => 'required_if:media_type,external_video|nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'local_video' => 'nullable|file|mimes:mp4,mov,avi|max:51200',
            'category' => [
                'required',
                Rule::in(['video', '1 seater', '2 seater', '3 seater', '4 seater', 'common', 'bathroom', 'event'])
            ],
            'status' => 'required|in:active,inactive',
        ]);

        // Convert status to is_active
        $validated['is_active'] = $validated['status'] === 'active';
        unset($validated['status']);

        // Get featured status from request
        $validated['is_featured'] = $request->has('featured');

        // Handle media changes
        $currentMediaType = $gallery->media_type;
        $newMediaType = $validated['media_type'];

        // If switching from photo to video (external or local)
        if ($currentMediaType === 'photo' && ($newMediaType === 'external_video' || $newMediaType === 'local_video')) {
            // Remove existing photo
            if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
                Storage::disk('public')->delete($gallery->file_path);
            }
        }
        // If switching from external video to photo or local video
        elseif ($currentMediaType === 'external_video' && ($newMediaType === 'photo' || $newMediaType === 'local_video')) {
            // No file to delete, but we'll handle new file below
        }
        // If switching from local video to photo or external video
        elseif ($currentMediaType === 'local_video' && ($newMediaType === 'photo' || $newMediaType === 'external_video')) {
            // Remove existing video
            if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
                Storage::disk('public')->delete($gallery->file_path);
            }
        }

        // Handle new file upload based on new media type
        if ($newMediaType === 'photo' && $request->hasFile('image')) {
            $path = $request->file('image')->store('gallery/images', 'public');
            $validated['file_path'] = $path;
            $validated['thumbnail'] = $path;
        } elseif ($newMediaType === 'local_video' && $request->hasFile('local_video')) {
            $path = $request->file('local_video')->store('gallery/videos', 'public');
            $validated['file_path'] = $path;
            $validated['thumbnail'] = 'images/video-default.jpg';
        } elseif ($newMediaType === 'external_video') {
            $validated['file_path'] = null;
            // Generate YouTube thumbnail
            $youtubeId = $this->getYoutubeIdFromUrl($request->external_link);
            $validated['thumbnail'] = $youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg" : 'images/video-default.jpg';
        }

        $gallery->update($validated);

        // Clear all gallery caches
        Cache::forget('public_gallery_all');
        Cache::forget('public_gallery_items_all');
        Cache::forget('public_gallery_featured');

        return redirect()->route('admin.gallery.index')
            ->with('success', 'ग्यालरी वस्तु सफलतापूर्वक अपडेट गरियो');
    }

    /**
     * Remove the specified gallery item.
     */
    public function destroy(Gallery $gallery)
    {
        $this->authorize('delete', $gallery);

        // Delete associated file
        if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        $gallery->delete();

        // Clear all gallery caches
        Cache::forget('public_gallery_all');
        Cache::forget('public_gallery_items_all');
        Cache::forget('public_gallery_featured');

        return redirect()->route('admin.gallery.index')
            ->with('success', 'ग्यालरी वस्तु सफलतापूर्वक हटाइयो');
    }

    /**
     * Toggle featured status of gallery item.
     */
    public function toggleFeatured(Gallery $gallery)
    {
        $this->authorize('update', $gallery);
        $gallery->update(['is_featured' => !$gallery->is_featured]);

        // Clear all gallery caches
        Cache::forget('public_gallery_all');
        Cache::forget('public_gallery_items_all');
        Cache::forget('public_gallery_featured');


        return back()->with('success', $gallery->is_featured ?
            'ग्यालरी वस्तु फिचर्ड गरियो' :
            'ग्यालरी वस्तु फिचर्ड हटाइयो');
    }

    /**
     * Toggle active status of gallery item.
     */
    public function toggleActive(Gallery $gallery)
    {
        $this->authorize('update', $gallery);
        $gallery->update(['is_active' => !$gallery->is_active]);

        // Clear all gallery caches
        Cache::forget('public_gallery_all');
        Cache::forget('public_gallery_items_all');
        Cache::forget('public_gallery_featured');

        return back()->with('success', $gallery->is_active ?
            'ग्यालरी वस्तु सक्रिय गरियो' :
            'ग्यालरी वस्तु निष्क्रिय गरियो');
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
