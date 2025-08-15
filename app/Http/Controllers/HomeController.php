<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Hostel;
use App\Models\Review;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Cache frequently accessed data
        $cities = Cache::remember('home_cities', 3600, function () {
            return Hostel::distinct()->pluck('city');
        });

        $hostels = Cache::remember('home_hostels', 3600, function () {
            return Hostel::with('images')->take(4)->get();
        });

        $reviews = Cache::remember('home_reviews', 3600, function () {
            return Review::take(3)->get();
        });

        $roomTypes = Cache::remember('home_room_types', 3600, function () {
            return Room::distinct()->pluck('type');
        });

        // Fetch 10 random active gallery items with optimized media handling
        $galleryItems = Cache::remember('home_gallery_items_random', 3600, function () {
            return Gallery::where('is_active', true)
                ->inRandomOrder()
                ->take(10)
                ->get()
                ->map(function ($item) {
                    $processed = [
                        'id' => $item->id,
                        'title' => $item->title,
                        'media_type' => $item->media_type,
                        'description' => $item->description,
                        'is_featured' => $item->is_featured,
                        'created_at' => $item->created_at->format('M d, Y'),
                        'thumbnail_url' => '',
                        'file_url' => '',
                        'youtube_id' => null,
                    ];

                    // Handle photo media
                    if ($item->media_type === 'photo') {
                        $processed['file_url'] = asset('storage/' . $item->file_path);
                        $processed['thumbnail_url'] = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : $processed['file_url'];
                    }
                    // Handle local video media
                    elseif ($item->media_type === 'local_video') {
                        $processed['file_url'] = asset('storage/' . $item->file_path);
                        $processed['thumbnail_url'] = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : asset('images/video-default.jpg');
                    }
                    // Handle external YouTube videos
                    elseif ($item->media_type === 'external_video') {
                        $youtubeId = $this->getYoutubeIdFromUrl($item->external_link);
                        $processed['youtube_id'] = $youtubeId;

                        $processed['thumbnail_url'] = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : ($youtubeId
                                ? "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg"
                                : asset('images/video-default.jpg'));
                    }

                    return $processed;
                });
        });

        return view('welcome', compact(
            'cities',
            'hostels',
            'reviews',
            'roomTypes',
            'galleryItems'
        ));
    }

    /**
     * Handle search request
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $request->validate([
            'city' => 'required|string',
            'hostel_id' => 'nullable|exists:hostels,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $rooms = Room::where('status', 'available')
            ->whereHas('hostel', function ($query) use ($request) {
                $query->where('city', $request->city)
                    ->when($request->hostel_id, function ($q) use ($request) {
                        return $q->where('id', $request->hostel_id);
                    });
            })
            ->with('hostel')
            ->get();

        return view('search-results', compact('rooms'));
    }

    /**
     * Robust YouTube ID extraction (handles all URL formats)
     *
     * @param string $url
     * @return string|null
     */
    private function getYoutubeIdFromUrl(string $url): ?string
    {
        if (empty($url)) return null;

        $pattern = '%^ (?:https?://)? (?:www\.)? (?: youtu\.be/ | youtube\.com (?: /embed/ | /v/ | /watch\?v= | /watch\?.+&v= ) ) ([\w-]{11}) $%x';
        preg_match($pattern, $url, $matches);

        return $matches[1] ?? null;
    }
}
