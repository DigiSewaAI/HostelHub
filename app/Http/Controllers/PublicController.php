<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Hostel;
use App\Models\Meal;
use App\Models\Room;
use App\Models\Student;
use App\Models\Review;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Show the public home page.
     */
    public function home(): View
    {
        // 1. Featured Rooms (Available rooms with at least one vacancy)
        $featuredRooms = Room::where('status', 'available')
            ->withCount('students')
            ->having('students_count', '<', DB::raw('capacity'))
            ->orderBy('price')
            ->limit(3)
            ->get();

        // 2. System Metrics
        $metrics = [
            'total_hostels'     => Hostel::count(),
            'total_students'    => Student::where('status', 'active')->count(),
            'total_rooms'       => Room::count(),
            'available_rooms'   => Room::where('status', 'available')->count(),
            'occupancy_rate'    => $this->getOccupancyRate(),
        ];

        // 3. Cities List - Temporarily disable caching
        $cities = Hostel::distinct()->pluck('city');

        // 4. Featured Hostels - Temporarily disable caching
        $hostels = Hostel::with('images')->take(4)->get();

        // 5. Recent Reviews - Temporarily disable caching
        $reviews = Review::take(3)->get();

        // 6. Hero Slider Items - Temporarily disable caching
        $heroSliderItems = Gallery::where('is_active', true)
            ->whereNotNull('file_path')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return $this->processGalleryItem($item);
            });

        // 7. Gallery Items - Temporarily disable caching
        $galleryItems = Gallery::where('is_active', true)
            ->whereNotNull('file_path')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return $this->processGalleryItem($item);
            });

        // 8. Upcoming Meals (Today + next 2 days)
        $meals = collect();
        if (class_exists(Meal::class)) {
            $meals = Meal::whereDate('date', '>=', now())
                ->orderBy('date')
                ->limit(3)
                ->get();
        }

        // Debug: Check what's in heroSliderItems
        Log::debug('Hero Slider Items:', $heroSliderItems->toArray());

        // Debug: Check what's in galleryItems
        Log::debug('Gallery Items:', $galleryItems->toArray());

        return view('public.home', compact(
            'featuredRooms',
            'metrics',
            'cities',
            'hostels',
            'reviews',
            'heroSliderItems',
            'galleryItems',
            'meals'
        ));
    }

    /**
     * Process gallery item for frontend display
     */
    private function processGalleryItem($item): array
    {
        $processed = [
            'id' => $item->id,
            'title' => $item->title,
            'media_type' => $item->media_type,
            'file_path' => $item->file_path,
            'thumbnail_url' => null,
            'media_url' => null
        ];

        \Log::debug('Processing item:', ['id' => $item->id, 'type' => $item->media_type, 'path' => $item->file_path]);

        // Check if file_path is not null
        if ($item->file_path) {
            if ($item->media_type === 'image' || $item->media_type === 'photo') {
                $url = $this->imageService->getUrl($item->file_path);
                $processed['media_url'] = $url;
                $processed['thumbnail_url'] = $url;
                \Log::debug('Image URL:', ['url' => $url]);
            } elseif ($item->media_type === 'video' || $item->media_type === 'local_video') {
                // For local videos, use the video file itself as media_url
                $url = $this->imageService->getUrl($item->file_path);
                $processed['media_url'] = $url;

                // For local videos, we need to generate a thumbnail differently
                // For now, let's use a default video thumbnail
                $processed['thumbnail_url'] = asset('images/default-video-thumbnail.jpg');

                // If it's a YouTube video, try to get the thumbnail
                if (strpos($item->file_path, 'youtube.com') !== false || strpos($item->file_path, 'youtu.be') !== false) {
                    $youtubeThumbnail = $this->getYoutubeThumbnail($item->file_path);
                    if ($youtubeThumbnail) {
                        $processed['thumbnail_url'] = $youtubeThumbnail;
                    }
                }

                \Log::debug('Video URLs:', ['media_url' => $url, 'thumbnail_url' => $processed['thumbnail_url']]);
            }
        } else {
            // If file_path is null, use a default image
            $url = $this->imageService->getUrl(null);
            $processed['thumbnail_url'] = $url;
            \Log::debug('Using default URL:', ['url' => $url]);
        }

        return $processed;
    }

    /**
     * Extract YouTube ID from URL
     */
    private function getYoutubeIdFromUrl(string $url): ?string
    {
        preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&?\n]+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Generate YouTube thumbnail URL
     */
    private function getYoutubeThumbnail(string $url): ?string
    {
        $id = $this->getYoutubeIdFromUrl($url);
        return $id ? "https://img.youtube.com/vi/{$id}/maxresdefault.jpg" : null;
    }

    /**
     * Calculate room occupancy rate.
     */
    private function getOccupancyRate(): float
    {
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();

        return $totalRooms > 0
            ? round(($occupiedRooms / $totalRooms) * 100, 2)
            : 0.0;
    }

    // Basic page routes
    public function about(): View
    {
        return view('public.about');
    }

    public function features(): View
    {
        return view('features');
    }

    public function howItWorks(): View
    {
        return view('how-it-works');
    }

    public function pricing(): View
    {
        return view('public.pricing.index');
    }

    public function reviews(): View
    {
        return view('reviews');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    // Legal pages
    public function privacy(): View
    {
        return view('legal.privacy');
    }

    public function terms(): View
    {
        return view('legal.terms');
    }

    public function cookies(): View
    {
        return view('legal.cookies');
    }

    public function demo(): View
    {
        return view('pages.demo');
    }

    // SEO routes
    public function sitemap()
    {
        $content = view('public.seo.sitemap')->render();
        return Response::make($content, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots()
    {
        $content = view('public.seo.robots')->render();
        return Response::make($content, 200, ['Content-Type' => 'text/plain']);
    }
}
