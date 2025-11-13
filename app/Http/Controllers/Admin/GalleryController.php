<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Process;

class GalleryController extends Controller
{
    /**
     * Get data based on user role
     */
    private function getDataByRole()
    {
        if (Auth::user()->hasRole('owner')) {
            return Gallery::where('hostel_id', Auth::user()->hostel_id);
        }

        return Gallery::query();
    }

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

        $query = $this->getDataByRole();

        $galleries = $query->when($selectedCategory !== 'all', function ($query) use ($selectedCategory) {
            $query->where('category', $selectedCategory);
        })
            ->latest()
            ->paginate(12);

        return view('admin.galleries.index', compact('galleries', 'categories', 'selectedCategory'));
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

        // Get hostels for admin to select
        $hostels = [];
        if (Auth::user()->hasRole('admin')) {
            $hostels = Hostel::pluck('name', 'id');
        }

        return view('admin.galleries.create', compact('categories', 'hostels'));
    }

    /**
     * Store a newly created gallery item.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Gallery::class);

        // Updated validation rules with all categories - SECURITY FIX: Reduced max file size
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_type' => 'required|in:photo,external_video,local_video',
            'external_link' => 'required_if:media_type,external_video|nullable|url',
            'media' => 'required_if:media_type,photo,local_video|array|min:1',
            'media.*' => 'required_if:media_type,photo,local_video|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,wmv|max:51200', // SECURITY FIX: Reduced from 102400 to 51200 (50MB)
            'category' => [
                'required',
                Rule::in(['video', '1 seater', '2 seater', '3 seater', '4 seater', 'common', 'bathroom', 'kitchen', 'living room', 'study room', 'event'])
            ],
            'status' => 'required|in:active,inactive',
            'hostel_id' => 'sometimes|required_if:role,admin|exists:hostels,id'
        ], [
            'media.required_if' => 'कृपया कम्तिमा एक फाइल अपलोड गर्नुहोस्।',
            'media.*.mimes' => 'फाइलको प्रकार अमान्य। जेपीइजी, पिएनजी, जिआइएफ, एमपी४, एमओभी, एभीआई, डब्ल्यूएमभी मात्र स्वीकार्य छन्।',
            'media.*.max' => 'फाइल साइज ५० एमबी भन्दा कम हुनुपर्छ।' // SECURITY FIX: Updated message
        ]);

        // Convert status to is_active
        $validated['is_active'] = $validated['status'] === 'active';
        unset($validated['status']);

        // Set default value for is_featured
        $validated['is_featured'] = $request->has('featured');

        // Add current user
        $validated['user_id'] = auth()->id();

        // Set hostel_id based on role
        if (Auth::user()->hasRole('owner')) {
            $validated['hostel_id'] = Auth::user()->hostel_id;
        }

        // Handle media upload with consistent paths
        if ($validated['media_type'] === 'external_video') {
            // Handle external video
            $validated['file_path'] = null;
            $validated['mime_type'] = 'video/youtube';

            // Generate YouTube thumbnail
            $youtubeId = $this->getYoutubeIdFromUrl($request->external_link);
            $validated['thumbnail'] = $youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg" : 'images/video-default.jpg';

            Gallery::create($validated);
        } else {
            // Handle multiple file uploads
            foreach ($request->file('media') as $file) {
                // SECURITY FIX: Additional file type validation
                $this->validateFileType($file);

                $mediaData = $validated;

                // Determine media type based on file MIME type
                $mimeType = $file->getMimeType();
                $isVideo = Str::startsWith($mimeType, 'video/');

                $mediaData['media_type'] = $isVideo ? 'local_video' : 'photo';
                $mediaData['mime_type'] = $mimeType;

                if ($isVideo) {
                    $path = $file->store('gallery/videos', 'public');
                    $mediaData['file_path'] = $path;

                    // Generate thumbnail for video using FFmpeg
                    $thumbnailPath = $this->generateVideoThumbnail($path);
                    $mediaData['thumbnail'] = $thumbnailPath;
                } else {
                    $path = $file->store('gallery/images', 'public');
                    $mediaData['file_path'] = $path;
                    $mediaData['thumbnail'] = $path;
                }

                Gallery::create($mediaData);
            }
        }

        // Clear all gallery caches
        Cache::forget('public_gallery_all');
        Cache::forget('public_gallery_items_all');
        Cache::forget('public_gallery_featured');

        return redirect()->route('admin.galleries.index')
            ->with('success', 'ग्यालरी वस्तु सफलतापूर्वक थपियो');
    }

    /**
     * Display the specified gallery item.
     */
    public function show(Gallery $gallery)
    {
        $this->authorize('view', $gallery);

        // Ensure owner can only view their hostel's galleries
        if (Auth::user()->hasRole('owner') && $gallery->hostel_id !== Auth::user()->hostel_id) {
            abort(403, 'तपाईंसँग यो ग्यालरी हेर्ने अनुमति छैन');
        }

        return view('admin.galleries.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified gallery item.
     */
    public function edit(Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        // Ensure owner can only edit their hostel's galleries
        if (Auth::user()->hasRole('owner') && $gallery->hostel_id !== Auth::user()->hostel_id) {
            abort(403, 'तपाईंसँग यो ग्यालरी सम्पादन गर्ने अनुमति छैन');
        }

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

        // Get hostels for admin to select
        $hostels = [];
        if (Auth::user()->hasRole('admin')) {
            $hostels = Hostel::pluck('name', 'id');
        }

        return view('admin.galleries.edit', compact('gallery', 'categories', 'hostels'));
    }

    /**
     * Update the specified gallery item.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        // Ensure owner can only update their hostel's galleries
        if (Auth::user()->hasRole('owner') && $gallery->hostel_id !== Auth::user()->hostel_id) {
            abort(403, 'तपाईंसँग यो ग्यालरी अपडेट गर्ने अनुमति छैन');
        }

        // Updated validation rules with all categories - SECURITY FIX: Reduced max file size
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_type' => 'required|in:photo,external_video,local_video',
            'external_link' => 'required_if:media_type,external_video|nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'local_video' => 'nullable|file|mimes:mp4,mov,avi|max:51200', // SECURITY FIX: Reduced from 102400 to 51200 (50MB)
            'category' => [
                'required',
                Rule::in(['video', '1 seater', '2 seater', '3 seater', '4 seater', 'common', 'bathroom', 'kitchen', 'living room', 'study room', 'event'])
            ],
            'status' => 'required|in:active,inactive',
            'hostel_id' => 'sometimes|required_if:role,admin|exists:hostels,id'
        ]);

        // SECURITY FIX: Additional file validation for uploaded files
        if ($request->hasFile('image')) {
            $this->validateFileType($request->file('image'));
        }
        if ($request->hasFile('local_video')) {
            $this->validateFileType($request->file('local_video'));
        }

        // Convert status to is_active
        $validated['is_active'] = $validated['status'] === 'active';
        unset($validated['status']);

        // Get featured status from request
        $validated['is_featured'] = $request->has('featured');

        // Set hostel_id based on role
        if (Auth::user()->hasRole('owner')) {
            $validated['hostel_id'] = Auth::user()->hostel_id;
        }

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
            $validated['mime_type'] = $request->file('image')->getMimeType();
        } elseif ($newMediaType === 'local_video' && $request->hasFile('local_video')) {
            $path = $request->file('local_video')->store('gallery/videos', 'public');
            $validated['file_path'] = $path;
            $validated['mime_type'] = $request->file('local_video')->getMimeType();

            // Generate thumbnail for video using FFmpeg
            $thumbnailPath = $this->generateVideoThumbnail($path);
            $validated['thumbnail'] = $thumbnailPath;
        } elseif ($newMediaType === 'external_video') {
            $validated['file_path'] = null;
            $validated['mime_type'] = 'video/youtube';
            // Generate YouTube thumbnail
            $youtubeId = $this->getYoutubeIdFromUrl($request->external_link);
            $validated['thumbnail'] = $youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg" : 'images/video-default.jpg';
        }

        $gallery->update($validated);

        // Clear all gallery caches
        Cache::forget('public_gallery_all');
        Cache::forget('public_gallery_items_all');
        Cache::forget('public_gallery_featured');

        return redirect()->route('admin.galleries.index')
            ->with('success', 'ग्यालरी वस्तु सफलतापूर्वक अपडेट गरियो');
    }

    /**
     * Remove the specified gallery item.
     */
    public function destroy(Gallery $gallery)
    {
        $this->authorize('delete', $gallery);

        // Ensure owner can only delete their hostel's galleries
        if (Auth::user()->hasRole('owner') && $gallery->hostel_id !== Auth::user()->hostel_id) {
            abort(403, 'तपाईंसँग यो ग्यालरी हटाउने अनुमति छैन');
        }

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

        return redirect()->route('admin.galleries.index')
            ->with('success', 'ग्यालरी वस्तु सफलतापूर्वक हटाइयो');
    }

    /**
     * Toggle featured status of gallery item.
     */
    public function toggleFeatured(Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        // Ensure owner can only update their hostel's galleries
        if (Auth::user()->hasRole('owner') && $gallery->hostel_id !== Auth::user()->hostel_id) {
            abort(403, 'तपाईंसँग यो ग्यालरी अपडेट गर्ने अनुमति छैन');
        }

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

        // Ensure owner can only update their hostel's galleries
        if (Auth::user()->hasRole('owner') && $gallery->hostel_id !== Auth::user()->hostel_id) {
            abort(403, 'तपाईंसँग यो ग्यालरी अपडेट गर्ने अनुमति छैन');
        }

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
     * Toggle status of gallery item (alias for toggleActive).
     * This method is added to fix the RouteNotFoundException.
     */
    public function toggleStatus(Gallery $gallery)
    {
        return $this->toggleActive($gallery);
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
     * Generate thumbnail for video using FFmpeg - SECURITY FIX: Command injection protection
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

        // SECURITY FIX: Use Laravel Process facade instead of shell_exec to prevent command injection
        try {
            $process = Process::run([
                'ffmpeg',
                '-i',
                $videoFullPath,
                '-ss',
                '00:00:02',
                '-vframes',
                '1',
                '-q:v',
                '2',
                $thumbnailFullPath
            ]);

            // Check if thumbnail was generated successfully
            if (file_exists($thumbnailFullPath)) {
                return $thumbnailPath;
            }

            // Log error for debugging
            Log::error("FFmpeg thumbnail generation failed", [
                'video_path' => $videoFullPath,
                'process_output' => $process->output(),
                'process_error' => $process->errorOutput(),
                'process_exit_code' => $process->exitCode()
            ]);
        } catch (\Exception $e) {
            Log::error("FFmpeg process exception", [
                'video_path' => $videoFullPath,
                'exception' => $e->getMessage()
            ]);
        }

        // Fallback to default thumbnail if FFmpeg fails
        return 'images/video-default.jpg';
    }

    /**
     * SECURITY FIX: Additional file type validation
     */
    private function validateFileType($file)
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'video/mp4', 'video/mov', 'video/avi', 'video/wmv'];

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('अमान्य फाइल प्रकार');
        }

        // Additional security check: verify file extension matches MIME type
        $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'mp4', 'mov', 'avi', 'wmv'];
        $extension = $file->getClientOriginalExtension();

        if (!in_array(strtolower($extension), $allowedExtensions)) {
            throw new \Exception('अमान्य फाइल एक्सटेन्सन');
        }

        return true;
    }
}
