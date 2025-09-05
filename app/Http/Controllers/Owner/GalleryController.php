<?php

namespace App\Http\Controllers\Owner;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::where('hostel_id', auth()->user()->hostel_id)->get();
        return view('owner.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('owner.galleries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'type' => 'required|in:image,video',
            'video_url' => 'nullable|url'
        ]);

        // इमेज अपलोड गर्नुहोस्
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/galleries'), $imageName);
            $imagePath = 'images/galleries/' . $imageName;
        }

        Gallery::create([
            'hostel_id' => auth()->user()->hostel_id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'type' => $request->type,
            'video_url' => $request->video_url
        ]);

        return redirect()->route('owner.galleries.index')
            ->with('success', 'ग्यालरी आइटम सफलतापूर्वक थपियो!');
    }

    public function edit(Gallery $gallery)
    {
        if ($gallery->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो ग्यालरी आइटम सम्पादन गर्ने अनुमति छैन');
        }

        return view('owner.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        if ($gallery->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो ग्यालरी आइटम सम्पादन गर्ने अनुमति छैन');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'type' => 'required|in:image,video',
            'video_url' => 'nullable|url'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'video_url' => $request->video_url
        ];

        // नयाँ इमेज अपलोड गर्नुहोस्
        if ($request->hasFile('image')) {
            // पुरानो इमेज हटाउनुहोस्
            if ($gallery->image && file_exists(public_path($gallery->image))) {
                unlink(public_path($gallery->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/galleries'), $imageName);
            $data['image'] = 'images/galleries/' . $imageName;
        }

        $gallery->update($data);

        return redirect()->route('owner.galleries.index')
            ->with('success', 'ग्यालरी आइटम सफलतापूर्वक अद्यावधिक गरियो!');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो ग्यालरी आइटम हटाउने अनुमति छैन');
        }

        // इमेज हटाउनुहोस्
        if ($gallery->image && file_exists(public_path($gallery->image))) {
            unlink(public_path($gallery->image));
        }

        $gallery->delete();

        return redirect()->route('owner.galleries.index')
            ->with('success', 'ग्यालरी आइटम सफलतापूर्वक हटाइयो!');
    }
}
