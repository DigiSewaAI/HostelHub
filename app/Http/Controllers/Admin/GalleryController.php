<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    /**
     * Display a listing of gallery items.
     */
    public function index()
    {
        $this->authorize('viewAny', Gallery::class);

        $categories = [
            'all'    => 'सबै',
            'single' => '१ सिटर',
            'double' => '२ सिटर',
            'triple' => '३ सिटर',
            'quad'   => '४ सिटर',
            'common' => 'लिभिङ रूम',
            'dining' => 'बाथरूम',
            'video'  => 'भिडियो टुर'
        ];

        $selectedCategory = request('category', 'all');

        // यहाँ galleryItems लाई galleries मा परिवर्तन गर्नुहोस्
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

        $categories = [
            'single' => '१ सिटर',
            'double' => '२ सिटर',
            'triple' => '३ सिटर',
            'quad'   => '४ सिटर',
            'common' => 'लिभिङ रूम',
            'dining' => 'बाथरूम',
            'video'  => 'भिडियो टुर'
        ];

        return view('admin.gallery.create', compact('categories'));
    }

    /**
     * Store a newly created gallery item.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Gallery::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => [
                'required',
                Rule::in(['single', 'double', 'triple', 'quad', 'common', 'dining', 'video'])
            ],
            'type' => [
                'required',
                Rule::in(['photo', 'local_video', 'youtube'])
            ],
            'file' => 'required_if:type,photo,local_video|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm,mov,avi|max:102400',
            'external_link' => 'required_if:type,youtube|url',
            'is_featured' => 'sometimes|boolean',
            'status' => 'required|in:active,inactive,draft'
        ]);

        try {
            $galleryData = [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'type' => $validated['type'],
                'is_featured' => $request->boolean('is_featured'),
                'status' => $validated['status'],
                'user_id' => auth()->id()
            ];

            // Handle file upload based on type
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('public/gallery');

                $galleryData['image'] = str_replace('public/', '', $path);

                // For videos, set default thumbnail
                if ($validated['type'] === 'local_video') {
                    $galleryData['thumbnail'] = 'gallery/thumbnails/video-default.jpg';
                }
            }

            // Handle YouTube link
            if ($validated['type'] === 'youtube') {
                $galleryData['external_link'] = $validated['external_link'];
                $galleryData['thumbnail'] = 'gallery/thumbnails/youtube-default.jpg';
            }

            Gallery::create($galleryData);

            return redirect()->route('admin.gallery.index')
                ->with('success', 'ग्यालरी आइटम सफलतापूर्वक थपियो');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'ग्यालरी आइटम थप्दा त्रुटि आयो: ' . $e->getMessage());
        }
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

        $categories = [
            'single' => '१ सिटर',
            'double' => '२ सिटर',
            'triple' => '३ सिटर',
            'quad'   => '४ सिटर',
            'common' => 'लिभिङ रूम',
            'dining' => 'बाथरूम',
            'video'  => 'भिडियो टुर'
        ];

        return view('admin.gallery.edit', compact('gallery', 'categories'));
    }

    /**
     * Update the specified gallery item.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => [
                'required',
                Rule::in(['single', 'double', 'triple', 'quad', 'common', 'dining', 'video'])
            ],
            'type' => [
                'required',
                Rule::in(['photo', 'local_video', 'youtube'])
            ],
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm,mov,avi|max:102400',
            'external_link' => 'nullable|required_if:type,youtube|url',
            'is_featured' => 'sometimes|boolean',
            'status' => 'required|in:active,inactive,draft'
        ]);

        try {
            $galleryData = [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'type' => $validated['type'],
                'is_featured' => $request->boolean('is_featured'),
                'status' => $validated['status']
            ];

            // Handle file upload if provided
            if ($request->hasFile('file')) {
                // Delete old file
                if ($gallery->image && Storage::exists('public/' . $gallery->image)) {
                    Storage::delete('public/' . $gallery->image);
                }

                $file = $request->file('file');
                $path = $file->store('public/gallery');
                $galleryData['image'] = str_replace('public/', '', $path);

                // Update thumbnail for videos
                if ($validated['type'] === 'local_video') {
                    $galleryData['thumbnail'] = 'gallery/thumbnails/video-default.jpg';
                }
            }

            // Handle YouTube link
            if ($validated['type'] === 'youtube') {
                $galleryData['external_link'] = $validated['external_link'];
                $galleryData['thumbnail'] = 'gallery/thumbnails/youtube-default.jpg';

                // Clear previous image if exists
                if ($gallery->image && Storage::exists('public/' . $gallery->image)) {
                    Storage::delete('public/' . $gallery->image);
                }
                $galleryData['image'] = null;
            } else if ($validated['type'] !== 'youtube' && $gallery->type === 'youtube') {
                // Clear YouTube link when switching to non-youtube
                $galleryData['external_link'] = null;
            }

            $gallery->update($galleryData);

            return redirect()->route('admin.gallery.index')
                ->with('success', "ग्यालरी आइटम सफलतापूर्वक अपडेट गरियो");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'ग्यालरी आइटम अपडेट गर्दा त्रुटि आयो: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified gallery item.
     */
    public function destroy(Gallery $gallery)
    {
        $this->authorize('delete', $gallery);

        try {
            // Delete associated files
            if ($gallery->image && Storage::exists('public/' . $gallery->image)) {
                Storage::delete('public/' . $gallery->image);
            }

            if ($gallery->thumbnail && Storage::exists('public/' . $gallery->thumbnail)) {
                Storage::delete('public/' . $gallery->thumbnail);
            }

            $gallery->delete();

            return redirect()->route('admin.gallery.index')
                ->with('success', 'ग्यालरी आइटम सफलतापूर्वक हटाइयो');
        } catch (\Exception $e) {
            return redirect()->route('admin.gallery.index')
                ->with('error', 'ग्यालरी आइटम हटाउँदा त्रुटि आयो: ' . $e->getMessage());
        }
    }

    /**
     * Toggle featured status of gallery item.
     */
    public function toggleFeatured(Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        $gallery->update(['is_featured' => !$gallery->is_featured]);

        return response()->json([
            'success' => true,
            'is_featured' => $gallery->is_featured,
            'message' => $gallery->is_featured
                ? 'ग्यालरी आइटम फिचर्ड गरियो'
                : 'ग्यालरी आइटम फिचर्ड हटाइयो'
        ]);
    }

    /**
     * Toggle active status of gallery item.
     */
    public function toggleActive(Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        $newStatus = $gallery->status === 'active' ? 'inactive' : 'active';
        $gallery->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => $newStatus === 'active'
                ? 'ग्यालरी आइटम सक्रिय गरियो'
                : 'ग्यालरी आइटम निष्क्रिय गरियो'
        ]);
    }
}
