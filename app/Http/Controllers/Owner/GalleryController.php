<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of gallery items.
     */
    public function index()
    {
        $hostel = Auth::user()->hostel;

        // ✅ FIXED: Updated categories to match unified room types
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

        $galleries = Gallery::where('hostel_id', $hostel->id)
            ->when($selectedCategory !== 'all', function ($query) use ($selectedCategory) {
                $query->where('category', $selectedCategory);
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('owner.galleries.index', compact('galleries', 'hostel', 'categories', 'selectedCategory'));
    }

    /**
     * Show the form for creating a new gallery item.
     */
    public function create()
    {
        $hostel = Auth::user()->hostel;

        // ✅ FIXED: Updated categories to match unified room types
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

        // ✅ NEW: Get available rooms for room-specific galleries
        $rooms = Room::where('hostel_id', $hostel->id)
            ->where('status', 'उपलब्ध')
            ->get();

        return view('owner.galleries.create', compact('hostel', 'categories', 'rooms'));
    }

    /**
     * Store a newly created gallery item.
     */
    public function store(Request $request)
    {
        $hostel = Auth::user()->hostel;

        // ✅ FIXED: Updated validation to include room_id and unified categories
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_type' => 'required|in:photo,external_video,local_video',
            'external_link' => 'required_if:media_type,external_video|nullable|url',
            'media' => 'required_if:media_type,photo,local_video|array|min:1',
            'media.*' => 'required_if:media_type,photo,local_video|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,wmv|max:102400',
            'category' => [
                'required',
                Rule::in(['video', '1 seater', '2 seater', '3 seater', '4 seater', 'common', 'bathroom', 'kitchen', 'living room', 'study room', 'event'])
            ],
            'room_id' => 'nullable|exists:rooms,id',
            'status' => 'required|in:active,inactive'
        ], [
            'media.required_if' => 'कृपया कम्तिमा एक फाइल अपलोड गर्नुहोस्।',
            'media.*.mimes' => 'फाइलको प्रकार अमान्य। जेपीइजी, पिएनजी, जिआइएफ, एमपी४, एमओभी, एभीआई, डब्ल्यूएमभी मात्र स्वीकार्य छन्।',
            'media.*.max' => 'फाइल साइज १०० एमबी भन्दा कम हुनुपर्छ।'
        ]);

        // Convert status to is_active
        $validated['is_active'] = $validated['status'] === 'active';
        unset($validated['status']);

        // Set default value for is_featured
        $validated['is_featured'] = $request->has('featured');

        // Add current user and hostel
        $validated['user_id'] = auth()->id();
        $validated['hostel_id'] = $hostel->id;

        // ✅ NEW: Validate room belongs to hostel
        if ($request->has('room_id') && $request->room_id) {
            $room = Room::where('id', $request->room_id)
                ->where('hostel_id', $hostel->id)
                ->first();

            if (!$room) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'यो कोठा तपाईंको होस्टलमा छैन');
            }
        }

        // Handle media upload with consistent paths - FIXED: Use 'galleries' directory
        if ($validated['media_type'] === 'external_video') {
            // Handle external video
            $validated['file_path'] = null;
            $validated['mime_type'] = 'video/youtube';

            // Generate YouTube thumbnail
            $youtubeId = $this->getYoutubeIdFromUrl($request->external_link);
            $validated['thumbnail'] = $youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg" : 'images/video-default.jpg';

            Gallery::create($validated);
        } else {
            // Handle multiple file uploads - FIXED: Use 'galleries' directory consistently
            foreach ($request->file('media') as $file) {
                $mediaData = $validated;

                // Determine media type based on file MIME type
                $mimeType = $file->getMimeType();
                $isVideo = Str::startsWith($mimeType, 'video/');

                $mediaData['media_type'] = $isVideo ? 'local_video' : 'photo';
                $mediaData['mime_type'] = $mimeType;

                if ($isVideo) {
                    // FIXED: Store in galleries directory
                    $path = $file->store('galleries', 'public');
                    $mediaData['file_path'] = $path;

                    // Generate thumbnail for video using FFmpeg
                    $thumbnailPath = $this->generateVideoThumbnail($path);
                    $mediaData['thumbnail'] = $thumbnailPath;
                } else {
                    // FIXED: Store in galleries directory
                    $path = $file->store('galleries', 'public');
                    $mediaData['file_path'] = $path;
                    $mediaData['thumbnail'] = $path;
                }

                Gallery::create($mediaData);
            }
        }

        // Clear gallery caches
        $this->clearGalleryCaches();

        return redirect()->route('owner.galleries.index')
            ->with('success', 'ग्यालरी वस्तु सफलतापूर्वक थपियो');
    }

    /**
     * Display the specified gallery item.
     */
    public function show(Gallery $gallery)
    {
        // Check authorization
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403, 'तपाईंसँग यो ग्यालरी हेर्ने अनुमति छैन');
        }

        $hostel = Auth::user()->hostel;
        return view('owner.galleries.show', compact('gallery', 'hostel'));
    }

    /**
     * Show the form for editing the specified gallery item.
     */
    public function edit(Gallery $gallery)
    {
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403, 'तपाईंसँग यो ग्यालरी सम्पादन गर्ने अनुमति छैन');
        }

        $hostel = Auth::user()->hostel;

        // ✅ FIXED: Updated categories to match unified room types
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

        // ✅ NEW: Get available rooms for room-specific galleries
        $rooms = Room::where('hostel_id', $hostel->id)
            ->where('status', 'उपलब्ध')
            ->get();

        return view('owner.galleries.edit', compact('gallery', 'hostel', 'categories', 'rooms'));
    }

    /**
     * Update the specified gallery item.
     */
    public function update(Request $request, Gallery $gallery)
    {
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403, 'तपाईंसँग यो ग्यालरी अपडेट गर्ने अनुमति छैन');
        }

        // ✅ FIXED: Updated validation to include room_id and unified categories
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_type' => 'required|in:photo,external_video,local_video',
            'external_link' => 'required_if:media_type,external_video|nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'local_video' => 'nullable|file|mimes:mp4,mov,avi|max:102400',
            'category' => [
                'required',
                Rule::in(['video', '1 seater', '2 seater', '3 seater', '4 seater', 'common', 'bathroom', 'kitchen', 'living room', 'study room', 'event'])
            ],
            'room_id' => 'nullable|exists:rooms,id',
            'status' => 'required|in:active,inactive'
        ]);

        // Convert status to is_active
        $validated['is_active'] = $validated['status'] === 'active';
        unset($validated['status']);

        // Get featured status from request
        $validated['is_featured'] = $request->has('featured');

        // ✅ NEW: Validate room belongs to hostel
        if ($request->has('room_id') && $request->room_id) {
            $room = Room::where('id', $request->room_id)
                ->where('hostel_id', $gallery->hostel_id)
                ->first();

            if (!$room) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'यो कोठा तपाईंको होस्टलमा छैन');
            }
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

        // Handle new file upload based on new media type - FIXED: Use 'galleries' directory
        if ($newMediaType === 'photo' && $request->hasFile('image')) {
            $path = $request->file('image')->store('galleries', 'public');
            $validated['file_path'] = $path;
            $validated['thumbnail'] = $path;
            $validated['mime_type'] = $request->file('image')->getMimeType();
        } elseif ($newMediaType === 'local_video' && $request->hasFile('local_video')) {
            $path = $request->file('local_video')->store('galleries', 'public');
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

        // Clear gallery caches
        $this->clearGalleryCaches();

        return redirect()->route('owner.galleries.index')
            ->with('success', 'ग्यालरी वस्तु सफलतापूर्वक अपडेट गरियो');
    }

    /**
     * Remove the specified gallery item.
     */
    public function destroy(Gallery $gallery)
    {
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
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

        // Clear gallery caches
        $this->clearGalleryCaches();

        return redirect()->route('owner.galleries.index')
            ->with('success', 'ग्यालरी वस्तु सफलतापूर्वक हटाइयो');
    }

    /**
     * Toggle featured status of gallery item.
     */
    public function toggleFeatured(Gallery $gallery)
    {
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403, 'तपाईंसँग यो ग्यालरी अपडेट गर्ने अनुमति छैन');
        }

        $gallery->update(['is_featured' => !$gallery->is_featured]);

        // Clear gallery caches
        $this->clearGalleryCaches();

        return back()->with('success', $gallery->is_featured ?
            'ग्यालरी वस्तु फिचर्ड गरियो' :
            'ग्यालरी वस्तु फिचर्ड हटाइयो');
    }

    /**
     * Toggle active status of gallery item.
     */
    public function toggleActive(Gallery $gallery)
    {
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403, 'तपाईंसँग यो ग्यालरी अपडेट गर्ने अनुमति छैन');
        }

        $gallery->update(['is_active' => !$gallery->is_active]);

        // Clear gallery caches
        $this->clearGalleryCaches();

        return back()->with('success', $gallery->is_active ?
            'ग्यालरी वस्तु सक्रिय गरियो' :
            'ग्यालरी वस्तु निष्क्रिय गरियो');
    }

    /**
     * Toggle status of gallery item (alias for toggleActive).
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


    public function mealGallery()
    {
        $organizationId = session('current_organization_id');

        $mealMenus = MealMenu::whereHas('hostel', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->where('is_active', true)->get();

        return view('owner.gallery.meal-gallery', compact('mealMenus'));
    }

    /**
     * Generate thumbnail for video using FFmpeg
     */
    private function generateVideoThumbnail($videoPath)
    {
        $videoFullPath = storage_path('app/public/' . $videoPath);

        // Check if video file exists
        if (!file_exists($videoFullPath)) {
            \Log::error("Video file not found: {$videoFullPath}");
            return 'images/video-default.jpg';
        }

        $thumbnailName = 'thumb_' . uniqid() . '.jpg';
        // FIXED: Use 'galleries/thumbnails' directory
        $thumbnailPath = 'galleries/thumbnails/' . $thumbnailName;
        $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);

        // Create thumbnails directory if it doesn't exist
        $thumbnailDir = storage_path('app/public/galleries/thumbnails');
        if (!file_exists($thumbnailDir)) {
            mkdir($thumbnailDir, 0777, true);
        }

        // Generate thumbnail using FFmpeg (capture at 2 seconds)
        $cmd = "ffmpeg -i \"{$videoFullPath}\" -ss 00:00:02 -vframes 1 -q:v 2 \"{$thumbnailFullPath}\" 2>&1";
        $output = shell_exec($cmd);

        // Check if thumbnail was generated successfully
        if (file_exists($thumbnailFullPath)) {
            return $thumbnailPath;
        }

        // Log error for debugging
        \Log::error("FFmpeg thumbnail generation failed", [
            'video_path' => $videoFullPath,
            'command' => $cmd,
            'output' => $output
        ]);

        // Fallback to default thumbnail if FFmpeg fails
        return 'images/video-default.jpg';
    }

    /**
     * Clear gallery caches
     */
    private function clearGalleryCaches()
    {
        Cache::forget('public_gallery_all');
        Cache::forget('public_gallery_items_all');
        Cache::forget('public_gallery_featured');
    }

    /**
     * Get video URL for playback
     */
    public function getVideoUrl(Gallery $gallery)
    {
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403);
        }

        if ($gallery->media_type === 'local_video' && $gallery->file_path) {
            return response()->json([
                'video_url' => asset('storage/' . $gallery->file_path)
            ]);
        } elseif ($gallery->media_type === 'external_video' && $gallery->external_link) {
            return response()->json([
                'video_url' => $gallery->external_link
            ]);
        }

        return response()->json(['error' => 'Video not found'], 404);
    }
}
