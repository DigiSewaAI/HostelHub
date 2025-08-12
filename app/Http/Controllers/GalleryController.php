<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GalleryController extends Controller
{
    /**
     * Public gallery list page with filtering capabilities
     *
     * @return \Illuminate\View\View
     */
    public function publicIndex(Request $request)
    {
        // Define gallery categories with Nepali labels
        $categories = [
            'all' => 'सबै',
            'single' => 'एकल कोठा',
            'double' => 'दुई ब्यक्ति कोठा',
            'common' => 'सामान्य क्षेत्र',
            'dining' => 'भोजन कक्ष',
            'video' => 'भिडियो टुर'
        ];

        // Get selected category from request, default to 'all'
        $selectedCategory = $request->input('category', 'all');

        // Validate category
        if (!array_key_exists($selectedCategory, $categories)) {
            $selectedCategory = 'all';
        }

        // Cache gallery items for 1 hour to improve performance
        $galleryItems = Cache::remember('public_gallery_items_'.$selectedCategory, 3600, function () use ($selectedCategory) {
            return Gallery::where('status', 'active')
                ->when($selectedCategory !== 'all', function ($query) use ($selectedCategory) {
                    $query->where('category', $selectedCategory);
                })
                ->orderByRaw("FIELD(category, 'video', 'single', 'double', 'dining', 'common')")
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'category' => $item->category,
                        'type' => $item->type,
                        'description' => $item->description,
                        'location' => $item->location,
                        'is_featured' => $item->is_featured,
                        'image' => $item->image ? asset('storage/'.$item->image) : null,
                        'video' => $item->video ? asset('storage/'.$item->video) : null,
                        'thumbnail' => $item->thumbnail ? asset('storage/'.$item->thumbnail) :
                                      ($item->type === 'video' ? asset('images/video-default.jpg') : null),
                        'created_at' => $item->created_at->format('M d, Y')
                    ];
                });
        });

        // Get statistics for the stats section
        $stats = [
            'total_students' => Cache::remember('total_students', 3600, function () {
                return 125; // This would come from your database in a real implementation
            }),
            'total_hostels' => Cache::remember('total_hostels', 3600, function () {
                return 24; // This would come from your database in a real implementation
            }),
            'cities_available' => Cache::remember('cities_available', 3600, function () {
                return 5; // This would come from your database in a real implementation
            }),
            'satisfaction_rate' => Cache::remember('satisfaction_rate', 3600, function () {
                return '98%'; // This would come from your database in a real implementation
            })
        ];

        return view('gallery', compact('galleryItems', 'categories', 'selectedCategory', 'stats'));
    }
}
