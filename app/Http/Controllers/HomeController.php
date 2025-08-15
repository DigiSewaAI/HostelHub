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

        // Fetch and process gallery items with CORRECTED media handling
        $galleryItems = Cache::remember('home_gallery_items', 3600, function () {
            return Gallery::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get()
                ->map(function ($item) {
                    // Handle Windows path formatting
                    $absolutePath = storage_path('app/public/' . $item->file_path);
                    $absolutePath = str_replace('/', DIRECTORY_SEPARATOR, $absolutePath);

                    $processedItem = [
                        'id' => $item->id,
                        'title' => $item->title,
                        'category' => $item->category,
                        'media_type' => $item->media_type,
                        'description' => $item->description,
                        'is_featured' => $item->is_featured,
                        'created_at' => $item->created_at->format('M d, Y'),
                        'external_link' => $item->external_link,
                        'file_exists' => '❌ हुँदैन', // Default to not exists
                        'absolute_path' => $absolutePath,
                        'file_url' => '', // Default empty file URL
                        'thumbnail_url' => ''
                    ];

                    // Handle different media types with CORRECTED paths
                    if ($item->media_type === 'photo') {
                        // Check if file exists
                        $fileExists = file_exists($absolutePath);
                        $processedItem['file_exists'] = $fileExists ? '✅ हुन्छ' : '❌ हुँदैन';

                        // Set file URL
                        $processedItem['file_url'] = $fileExists
                            ? asset('storage/' . $item->file_path)
                            : '';

                        // Set image URL (same as file URL for photos)
                        $processedItem['image_url'] = $processedItem['file_url'];

                        // Set thumbnail URL
                        $processedItem['thumbnail_url'] = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : $processedItem['file_url'];
                    } elseif ($item->media_type === 'local_video') {
                        // Check if file exists
                        $fileExists = file_exists($absolutePath);
                        $processedItem['file_exists'] = $fileExists ? '✅ हुन्छ' : '❌ हुँदैन';

                        // Set file URL
                        $processedItem['file_url'] = $fileExists
                            ? asset('storage/' . $item->file_path)
                            : '';

                        // Set video URL (same as file URL for local videos)
                        $processedItem['video_url'] = $processedItem['file_url'];

                        // Set thumbnail URL
                        $processedItem['thumbnail_url'] = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : asset('images/video-default.jpg');
                    } elseif ($item->media_type === 'youtube') {
                        // YouTube always "exists" (it's external)
                        $processedItem['file_exists'] = '✅ हुन्छ (External)';

                        // Get YouTube ID
                        $youtubeId = $this->getYoutubeIdFromUrl($item->external_link);
                        $processedItem['youtube_id'] = $youtubeId;

                        // Set thumbnail URL (with fixed URL format - no extra space)
                        $processedItem['thumbnail_url'] = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg";
                    }

                    return $processedItem;
                });
        });

        return view('welcome', compact('cities', 'hostels', 'reviews', 'roomTypes', 'galleryItems'));
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
        // Handle case where URL might be null or empty
        if (empty($url)) {
            return null;
        }

        // Comprehensive regex to handle all YouTube URL formats
        $pattern = '%^ (?:https?://)? (?:www\.)? (?: youtu\.be/ | youtube\.com (?: /embed/ | /v/ | /watch\?v= | /watch\?.+&v= ) ) ([\w-]{11}) $%x';
        preg_match($pattern, $url, $matches);

        return $matches[1] ?? null;
    }
}
