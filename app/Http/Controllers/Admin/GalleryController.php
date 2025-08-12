<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Gallery::class, 'gallery');
    }

    /**
     * Display a listing of gallery items.
     */
    public function index()
    {
        $categories = [
            'all' => 'सबै',
            'single' => 'एकल कोठा',
            'double' => 'दुई ब्यक्ति कोठा',
            'common' => 'सामान्य क्षेत्र',
            'dining' => 'भोजन कक्ष',
            'video' => 'भिडियो टुर'
        ];

        $selectedCategory = request('category', 'all');

        $galleryItems = Gallery::when($selectedCategory !== 'all', function ($query) use ($selectedCategory) {
                $query->where('category', $selectedCategory);
            })
            ->latest()
            ->paginate(12);

        return view('admin.gallery.index', compact('galleryItems', 'categories', 'selectedCategory'));
    }

    /**
     * Show the form for creating a new gallery item.
     */
    public function create()
    {
        $categories = [
            'single' => 'एकल कोठा',
            'double' => 'दुई ब्यक्ति कोठा',
            'common' => 'सामान्य क्षेत्र',
            'dining' => 'भोजन कक्ष',
            'video' => 'भिडियो टुर'
        ];

        return view('admin.gallery.create', compact('categories'));
    }

    /**
     * Store a newly created gallery item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => [
                'required',
                Rule::in(['single', 'double', 'common', 'dining', 'video'])
            ],
            'type' => [
                'required',
                Rule::in(['image', 'video'])
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video' => 'nullable|mimes:mp4,webm,mov,avi|max:102400',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'sometimes|boolean',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $galleryData = $validated;
            $galleryData['is_featured'] = $request->has('is_featured');
            $galleryData['user_id'] = auth()->id();

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('public/hostel/gallery');
                $galleryData['image'] = str_replace('public/', '', $imagePath);
            }

            // Handle video upload
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('public/hostel/gallery/videos');
                $galleryData['video'] = str_replace('public/', '', $videoPath);
            }

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('public/hostel/gallery/thumbnails');
                $galleryData['thumbnail'] = str_replace('public/', '', $thumbnailPath);
            }
            // Generate thumbnail from video if video is uploaded but no thumbnail
            elseif ($request->hasFile('video')) {
                // In a real implementation, you would generate a thumbnail from the video
                // For now, we'll use a default thumbnail
                $galleryData['thumbnail'] = 'hostel/gallery/thumbnails/video-default.jpg';
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
        return view('admin.gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified gallery item.
     */
    public function edit(Gallery $gallery)
    {
        $categories = [
            'single' => 'एकल कोठा',
            'double' => 'दुई ब्यक्ति कोठा',
            'common' => 'सामान्य क्षेत्र',
            'dining' => 'भोजन कक्ष',
            'video' => 'भिडियो टुर'
        ];

        return view('admin.gallery.edit', compact('gallery', 'categories'));
    }

    /**
     * Update the specified gallery item.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => [
                'required',
                Rule::in(['single', 'double', 'common', 'dining', 'video'])
            ],
            'type' => [
                'required',
                Rule::in(['image', 'video'])
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video' => 'nullable|mimes:mp4,webm,mov,avi|max:102400',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'sometimes|boolean',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $galleryData = $validated;
            $galleryData['is_featured'] = $request->has('is_featured');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($gallery->image && Storage::exists('public/' . $gallery->image)) {
                    Storage::delete('public/' . $gallery->image);
                }

                $imagePath = $request->file('image')->store('public/hostel/gallery');
                $galleryData['image'] = str_replace('public/', '', $imagePath);
            }

            // Handle video upload
            if ($request->hasFile('video')) {
                // Delete old video if exists
                if ($gallery->video && Storage::exists('public/' . $gallery->video)) {
                    Storage::delete('public/' . $gallery->video);
                }

                $videoPath = $request->file('video')->store('public/hostel/gallery/videos');
                $galleryData['video'] = str_replace('public/', '', $videoPath);
            }

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($gallery->thumbnail && Storage::exists('public/' . $gallery->thumbnail)) {
                    Storage::delete('public/' . $gallery->thumbnail);
                }

                $thumbnailPath = $request->file('thumbnail')->store('public/hostel/gallery/thumbnails');
                $galleryData['thumbnail'] = str_replace('public/', '', $thumbnailPath);
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
        try {
            // Delete associated files
            if ($gallery->image && Storage::exists('public/' . $gallery->image)) {
                Storage::delete('public/' . $gallery->image);
            }

            if ($gallery->video && Storage::exists('public/' . $gallery->video)) {
                Storage::delete('public/' . $gallery->video);
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
        $gallery->is_featured = !$gallery->is_featured;
        $gallery->save();

        return response()->json([
            'success' => true,
            'message' => $gallery->is_featured ?
                'ग्यालरी आइटम फिचर्ड गरियो' :
                'ग्यालरी आइटम फिचर्ड हटाइयो'
        ]);
    }
}
