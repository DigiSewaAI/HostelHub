<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class GalleriesController extends Controller
{
    /**
     * Display a listing of gallery items.
     */
    public function index()
    {
        $this->authorize('viewAny', Gallery::class);

        // Updated categories with all options
        $categories = [
            'all'         => 'सबै',
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

        // Updated categories with all options
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

        return view('admin.gallery.create', compact('categories'));
    }

    /**
     * Store a newly created gallery item.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Gallery::class);

        // Updated validation rules with all categories
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_type' => 'required|in:photo,external_video,local_video',
            'external_link' => 'required_if:media_type,external_video|nullable|url',
            'image' => 'required_if:media_type,photo|nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'local_video' => 'required_if:media_type,local_video|nullable|file|mimes:mp4,mov,avi|max:51200',
            'category' => [
                'required',
                Rule::in(['video', '1 seater', '2 seater', '3 seater', '4 seater', 'common', 'bathroom', 'kitchen', 'living room', 'study room', 'event'])
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

            // Generate thumbnail for video using FFmpeg
            $thumbnailPath = $this->generateVideoThumbnail($path);
            $validated['thumbnail'] = $thumbnailPath;
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

        // Updated categories with all options
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

        return view('admin.gallery.edit', compact('gallery', 'categories'));
    }

    /**
     * Update the specified gallery item.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        // Updated validation rules with all categories
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_type' => 'required|in:photo,external_video,local_video',
            'external_link' => 'required_if:media_type,external_video|nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'local_video' => 'nullable|file|mimes:mp4,mov,avi|max:51200',
            'category' => [
                'required',
                Rule::in(['video', '1 seater', '2 seater', '3 seater', '4 seater', 'common', 'bathroom', 'kitchen', 'living room', 'study room', 'event'])
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
            // Remove existing thumbnail if it's not the default
            if ($gallery->thumbnail && $gallery->thumbnail !== 'images/video-default.jpg' && Storage::disk('public')->exists($gallery->thumbnail)) {
                Storage::disk('public')->delete($gallery->thumbnail);
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

            // Generate thumbnail for video using FFmpeg
            $thumbnailPath = $this->generateVideoThumbnail($path);
            $validated['thumbnail'] = $thumbnailPath;
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

        // Delete associated thumbnail if it's not the default
        if ($gallery->thumbnail && $gallery->thumbnail !== 'images/video-default.jpg' && Storage::disk('public')->exists($gallery->thumbnail)) {
            Storage::disk('public')->delete($gallery->thumbnail);
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
     * Stream video with proper support for byte-range requests
     */
    /**
    public function streamVideo(Gallery $gallery)
    {
    // Check if file exists
    $path = storage_path('app/public/' . $gallery->file_path);
    
    if (!file_exists($path)) {
        abort(404, 'File not found');
    }

    $fileSize = filesize($path);
    $file = fopen($path, 'rb');

    // Set basic headers
    $headers = [
        'Content-Type' => 'video/mp4',
        'Content-Length' => $fileSize,
        'Accept-Ranges' => 'bytes',
        'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ];

    // Handle range requests (for video seeking)
    if (isset($_SERVER['HTTP_RANGE'])) {
        $range = $_SERVER['HTTP_RANGE'];
        $range = str_replace('bytes=', '', $range);
        list($start, $end) = explode('-', $range, 2);
        
        $start = intval($start);
        $end = $end === '' ? $fileSize - 1 : intval($end);
        $length = $end - $start + 1;

        // Validate range
        if ($start >= $fileSize || $end >= $fileSize || $start > $end) {
            header('HTTP/1.1 416 Requested Range Not Satisfiable');
            header("Content-Range: bytes 0-{$fileSize}/{$fileSize}");
            exit;
        }

        fseek($file, $start);
        
        header('HTTP/1.1 206 Partial Content');
        $headers['Content-Length'] = $length;
        $headers['Content-Range'] = "bytes {$start}-{$end}/{$fileSize}";
    }

    // Stream the video
    return response()->stream(function () use ($file, $fileSize) {
        $buffer = 8192; // 8KB buffer
        $bytesSent = 0;
        
        while (!feof($file) && $bytesSent < $fileSize && connection_status() == 0) {
            $bytesToRead = min($buffer, $fileSize - $bytesSent);
            $data = fread($file, $bytesToRead);
            echo $data;
            flush();
            $bytesSent += $bytesToRead;
        }
        
        fclose($file);
    }, isset($_SERVER['HTTP_RANGE']) ? 206 : 200, $headers);
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

    /**
     * Generate thumbnail for video using FFmpeg
     */
    private function generateVideoThumbnail($videoPath)
    {
        $videoFullPath = storage_path('app/public/' . $videoPath);

        // Check if video file exists
        if (!file_exists($videoFullPath)) {
            Log::error("Video file not found: {$videoFullPath}");
            return 'images/video-default.jpg';
        }

        $thumbnailName = 'thumb_' . uniqid() . '.jpg';
        $thumbnailPath = 'gallery/thumbnails/' . $thumbnailName;
        $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);

        // Create thumbnails directory if it doesn't exist
        $thumbnailDir = storage_path('app/public/gallery/thumbnails');
        if (!file_exists($thumbnailDir)) {
            mkdir($thumbnailDir, 0777, true);
        }

        // Generate thumbnail using FFmpeg (capture at 2 seconds)
        // Use quotes to handle paths with spaces
        $cmd = "ffmpeg -i \"{$videoFullPath}\" -ss 00:00:02 -vframes 1 -q:v 2 \"{$thumbnailFullPath}\" 2>&1";
        $output = shell_exec($cmd);

        // Check if thumbnail was generated successfully
        if (file_exists($thumbnailFullPath)) {
            return $thumbnailPath;
        }

        // Log error for debugging
        Log::error("FFmpeg thumbnail generation failed", [
            'video_path' => $videoFullPath,
            'command' => $cmd,
            'output' => $output
        ]);

        // Fallback to default thumbnail if FFmpeg fails
        return 'images/video-default.jpg';
    }
}
