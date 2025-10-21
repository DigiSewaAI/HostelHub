<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $hostel = Auth::user()->hostel;
        $galleries = Gallery::where('hostel_id', $hostel->id)
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('owner.galleries.index', compact('galleries', 'hostel'));
    }

    public function create()
    {
        $hostel = Auth::user()->hostel;
        return view('owner.galleries.create', compact('hostel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:room,common_area,facility,event,other',
            'media_type' => 'required|string|in:image,video,external_video',
            'file' => 'required_if:media_type,image,video|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // 10MB max
            'external_link' => 'required_if:media_type,external_video|url',
            'is_featured' => 'boolean',
        ]);

        $hostel = Auth::user()->hostel;

        $galleryData = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'media_type' => $request->media_type,
            'is_featured' => $request->is_featured ?? false,
            'is_active' => true,
            'user_id' => Auth::id(),
            'hostel_id' => $hostel->id,
        ];

        // Handle file upload
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $filePath = $file->store('galleries', 'public');
            $galleryData['file_path'] = $filePath;

            // Generate thumbnail for images
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                // You can add thumbnail generation logic here if needed
                $galleryData['thumbnail'] = $filePath;
            }
        }

        // Handle external video
        if ($request->media_type === 'external_video' && $request->external_link) {
            $galleryData['external_link'] = $request->external_link;
        }

        Gallery::create($galleryData);

        return redirect()->route('owner.galleries.index')
            ->with('success', 'ग्यालरी सामग्री सफलतापूर्वक थपियो।');
    }

    public function edit(Gallery $gallery)
    {
        // Authorization - ensure owner can only edit their hostel's gallery
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403);
        }

        $hostel = Auth::user()->hostel;
        return view('owner.galleries.edit', compact('gallery', 'hostel'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        // Authorization
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:room,common_area,facility,event,other',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'is_featured' => $request->is_featured ?? false,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('owner.galleries.index')
            ->with('success', 'ग्यालरी सामग्री सफलतापूर्वक अपडेट भयो।');
    }

    public function destroy(Gallery $gallery)
    {
        // Authorization
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403);
        }

        // Delete file from storage
        if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        if ($gallery->thumbnail && Storage::disk('public')->exists($gallery->thumbnail)) {
            Storage::disk('public')->delete($gallery->thumbnail);
        }

        $gallery->delete();

        return redirect()->route('owner.galleries.index')
            ->with('success', 'ग्यालरी सामग्री सफलतापूर्वक मेटियो।');
    }

    public function toggleFeatured(Gallery $gallery)
    {
        // Authorization
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403);
        }

        $gallery->update([
            'is_featured' => !$gallery->is_featured
        ]);

        return back()->with('success', 'फिचर्ड स्थिति परिवर्तन भयो।');
    }

    public function toggleActive(Gallery $gallery)
    {
        // Authorization
        if ($gallery->hostel_id !== Auth::user()->hostel->id) {
            abort(403);
        }

        $gallery->update([
            'is_active' => !$gallery->is_active
        ]);

        return back()->with('success', 'सक्रिय स्थिति परिवर्तन भयो।');
    }
}
