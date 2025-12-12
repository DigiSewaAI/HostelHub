<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Hostel;
use App\Models\Meal;
use App\Models\Room;
use App\Models\Student;
use App\Models\Review;
use App\Models\Newsletter;
use App\Models\MealMenu;
use App\Models\BookingRequest;
use App\Models\Booking;
use App\Models\HostelImage;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

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
        try {
            // 1. Featured Rooms (Available rooms with at least one vacancy)
            $featuredRooms = Room::where('status', 'available')
                ->with(['students'])
                ->get()
                ->filter(function ($room) {
                    return $room->students->count() < $room->capacity;
                })
                ->sortBy('price')
                ->take(3);

            // 2. System Metrics
            $metrics = [
                'total_hostels'     => Hostel::count(),
                'total_students'    => Student::where('status', 'active')->count(),
                'total_rooms'       => Room::count(),
                'available_rooms'   => Room::where('status', 'available')->count(),
                'occupancy_rate'    => $this->getOccupancyRate(),
            ];

            // 3. Cities List (with caching)
            $cities = Cache::remember('home_cities', 3600, function () {
                return Hostel::where('is_published', true)
                    ->whereNotNull('city')
                    ->distinct()
                    ->pluck('city')
                    ->filter();
            });

            // 4. Featured Hostels (with caching)
            $hostels = Cache::remember('home_hostels_all', 3600, function () {
                return Hostel::where('is_published', true)
                    ->where('status', 'active')
                    ->with(['images'])
                    ->get();
            });

            // ✅ NEW: Admin-Managed Featured Hostels for Hero Slider
            $featuredHostels = Cache::remember('home_featured_hostels', 3600, function () {
                return Hostel::where('is_published', true)
                    ->where('status', 'active')
                    ->where('is_featured', true)
                    ->whereNotNull('image')
                    ->orderBy('featured_order', 'asc')
                    ->limit(10)
                    ->with(['images'])
                    ->get()
                    ->map(function ($hostel) {
                        return [
                            'id' => $hostel->id,
                            'name' => $hostel->name,
                            'city' => $hostel->city,
                            'cover_image' => $hostel->image ? asset('storage/' . $hostel->image) : asset('images/default-hostel.jpg'),
                            'slug' => $hostel->slug,
                            'public_url' => route('hostels.show', $hostel->slug),
                            'is_featured' => $hostel->is_featured,
                            'featured_order' => $hostel->featured_order,
                            'commission_rate' => $hostel->commission_rate
                        ];
                    });
            });

            // 5. Recent Testimonials (with caching)
            $testimonials = Cache::remember('home_testimonials', 3600, function () {
                return Review::with('student')
                    ->where('is_published', true)
                    ->take(3)
                    ->get();
            });

            // 6. Room Types (with caching)
            $roomTypes = Cache::remember('home_room_types', 3600, function () {
                return Room::distinct()->pluck('type');
            });

            // 7. Hero Slider Items (with caching) - UPDATED: Use featured hostels
            $heroSliderItems = $featuredHostels->isNotEmpty() ? $featuredHostels : Cache::remember('home_hero_slider_items', 3600, function () {
                $items = Gallery::with(['hostel', 'room.hostel'])
                    ->where('is_active', true)
                    ->whereNotNull('file_path')
                    ->orderBy('is_featured', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get()
                    ->map(function ($item) {
                        return $this->processGalleryItemForHome($item);
                    });

                if ($items->isEmpty()) {
                    return collect([
                        [
                            'media_type' => 'image',
                            'thumbnail_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=450&fit=crop',
                            'title' => 'Comfortable Hostel Rooms',
                            'hostel_name' => 'HostelHub',
                            'description' => 'Modern and comfortable hostel accommodations'
                        ],
                        [
                            'media_type' => 'image',
                            'thumbnail_url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=450&fit=crop',
                            'title' => 'Modern Hostel Facilities',
                            'hostel_name' => 'HostelHub',
                            'description' => 'State-of-the-art facilities for students'
                        ]
                    ]);
                }

                return $items;
            });

            // ✅ UPDATED: Gallery Items - DUAL-MODE SYSTEM
            $galleryItems = $this->getSmartGalleryItems();

            // 9. Upcoming Meals (Temporarily commented out)
            $meals = collect();

            // 10. Featured Meal Menus
            $featuredMealMenus = Cache::remember('home_featured_meal_menus', 3600, function () {
                return MealMenu::with('hostel')
                    ->where('is_active', true)
                    ->whereHas('hostel', function ($query) {
                        $query->where('is_published', true);
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get();
            });

            return view('frontend.home', compact(
                'featuredRooms',
                'metrics',
                'cities',
                'hostels',
                'testimonials',
                'roomTypes',
                'heroSliderItems',
                'galleryItems',
                'meals',
                'featuredMealMenus',
                'featuredHostels'
            ));
        } catch (\Exception $e) {
            Log::error('PublicController@home error: ' . $e->getMessage());
            return view('frontend.home', [
                'featuredRooms' => collect(),
                'metrics' => [
                    'total_hostels' => 0,
                    'total_students' => 0,
                    'total_rooms' => 0,
                    'available_rooms' => 0,
                    'occupancy_rate' => 0.0,
                ],
                'cities' => collect(),
                'hostels' => collect(),
                'testimonials' => collect(),
                'roomTypes' => collect(),
                'heroSliderItems' => collect([
                    [
                        'media_type' => 'image',
                        'thumbnail_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=450&fit=crop',
                        'title' => 'Comfortable Hostel Rooms',
                        'hostel_name' => 'HostelHub',
                        'description' => 'Modern and comfortable hostel accommodations'
                    ]
                ]),
                'galleryItems' => collect(),
                'meals' => collect(),
                'featuredMealMenus' => collect(),
                'featuredHostels' => collect()
            ]);
        }
    }

    /**
     * ✅ ENHANCED: DUAL-MODE DYNAMIC GALLERY SYSTEM
     * 
     * Mode 1: Simple Gallery (≤10 published hostels) - Only real room images, no fallback
     * Mode 2: Dynamic Gallery (>10 published hostels) - Real images + fallback, optimized for performance
     */
    public function getSmartGalleryItems()
    {
        // Count published and active hostels
        $totalPublished = Hostel::where('is_published', true)
            ->where('status', 'active')
            ->count();

        $cacheKey = 'smart_gallery_' . $totalPublished . '_' . now()->format('Y-m-d_H');

        return Cache::remember($cacheKey, 3600, function () use ($totalPublished) {
            \Log::info("Dual-Mode Gallery: Total Published Hostels = {$totalPublished}");

            // Mode 1: Simple Gallery (≤10 hostels) - Only real images
            if ($totalPublished <= 10) {
                return $this->getSimpleGalleryItems();
            }
            // Mode 2: Dynamic Gallery (>10 hostels) - Optimized with fallback
            else {
                return $this->getDynamicGalleryItemsOptimized($totalPublished);
            }
        });
    }

    /**
     * Mode 1: Simple Gallery - Only real room images from published hostels
     * Used when ≤10 published hostels exist
     */
    private function getSimpleGalleryItems()
    {
        $galleryItems = collect();

        // Get ONLY published and active hostels
        $hostels = Hostel::where('is_published', true)
            ->where('status', 'active')
            ->with(['rooms' => function ($query) {
                $query->whereNotNull('image')
                    ->where('image', '!=', '')
                    ->orderBy('created_at', 'desc');
            }])
            ->orderBy('is_featured', 'desc')
            ->orderBy('name')
            ->get();

        \Log::info("Simple Mode: Found {$hostels->count()} published hostels");

        // Collect ALL real room images from ONLY published hostels
        foreach ($hostels as $hostel) {
            foreach ($hostel->rooms as $room) {
                // ✅ STRICT: Only add if image exists in storage
                if ($room->image && Storage::disk('public')->exists($room->image)) {
                    $galleryItems->push($this->formatGalleryItem($room, $hostel));
                }
            }
        }

        $totalRealImages = $galleryItems->count();
        \Log::info("Simple Mode: Total real images collected = {$totalRealImages}");

        // ✅ IMPORTANT: No fallback images in simple mode
        // Show only what we have (up to 40)
        return $galleryItems->shuffle()->take(min(40, $totalRealImages));
    }

    /**
     * Mode 2: Dynamic Gallery - Optimized for large number of hostels
     * Used when >10 published hostels exist
     */
    private function getDynamicGalleryItemsOptimized($totalPublished)
    {
        $galleryItems = collect();

        // Calculate how many images to take from each hostel
        $imagesPerHostel = $this->calculateImagesPerHostel($totalPublished);

        // Get published hostels with room images
        $hostels = Hostel::where('is_published', true)
            ->where('status', 'active')
            ->with(['rooms' => function ($query) use ($imagesPerHostel) {
                $query->whereNotNull('image')
                    ->where('image', '!=', '')
                    ->inRandomOrder()
                    ->limit($imagesPerHostel * 2); // Get more than needed for random selection
            }])
            ->inRandomOrder()
            ->get();

        // Collect real images from each hostel
        foreach ($hostels as $hostel) {
            if ($hostel->rooms->isEmpty()) {
                continue;
            }

            // Take random rooms from this hostel
            $randomRooms = $hostel->rooms->take($imagesPerHostel);

            foreach ($randomRooms as $room) {
                // ✅ STRICT: Only add if image exists in storage
                if ($room->image && Storage::disk('public')->exists($room->image)) {
                    $galleryItems->push($this->formatGalleryItem($room, $hostel));
                }
            }
        }

        // Check if we need fallback images
        $realImagesCount = $galleryItems->count();
        $neededImages = 40 - $realImagesCount;

        \Log::info("Dynamic Mode: Real images = {$realImagesCount}, Needed = {$neededImages}");

        // ✅ Add fallback images only if we don't have enough real images
        if ($neededImages > 0) {
            $fallbackItems = $this->getFallbackGalleryItems($neededImages);
            $galleryItems = $galleryItems->merge($fallbackItems);
        }

        return $galleryItems->shuffle()->take(40);
    }

    /**
     * Calculate how many images to take from each hostel based on total count
     */
    private function calculateImagesPerHostel($totalPublished)
    {
        if ($totalPublished <= 5) {
            return 8; // 5 hostels × 8 = 40
        } elseif ($totalPublished <= 10) {
            return 4; // 10 hostels × 4 = 40
        } elseif ($totalPublished <= 20) {
            return 2; // 20 hostels × 2 = 40
        } elseif ($totalPublished <= 40) {
            return 1; // 40 hostels × 1 = 40
        } else {
            // For >40 hostels, take 1 image from 40 random hostels
            return 1;
        }
    }

    /**
     * Get fallback gallery items (dummy images)
     * Only used in Dynamic Mode when real images are insufficient
     */
    private function getFallbackGalleryItems($count)
    {
        $fallbackSet = [
            [
                'id' => 'fallback_1',
                'media_type' => 'photo',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=450&fit=crop',
                'title' => 'Comfortable Hostel Room',
                'hostel_name' => 'HostelHub',
                'hostel_id' => 0,
                'is_featured_hostel' => false,
                'city' => 'Kathmandu',
                'caption' => 'Comfortable and modern hostel accommodation',
                'is_room_image' => false,
                'room_number' => null
            ],
            [
                'id' => 'fallback_2',
                'media_type' => 'photo',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=450&fit=crop',
                'title' => 'Modern Hostel Facilities',
                'hostel_name' => 'HostelHub',
                'hostel_id' => 0,
                'is_featured_hostel' => false,
                'city' => 'Pokhara',
                'caption' => 'State-of-the-art facilities for students',
                'is_room_image' => false,
                'room_number' => null
            ],
            [
                'id' => 'fallback_3',
                'media_type' => 'photo',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=450&fit=crop',
                'title' => 'Study Room',
                'hostel_name' => 'HostelHub',
                'hostel_id' => 0,
                'is_featured_hostel' => false,
                'city' => 'Biratnagar',
                'caption' => 'Dedicated study area for students',
                'is_room_image' => false,
                'room_number' => null
            ],
            [
                'id' => 'fallback_4',
                'media_type' => 'photo',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800&h=450&fit=crop',
                'title' => 'Common Area',
                'hostel_name' => 'HostelHub',
                'hostel_id' => 0,
                'is_featured_hostel' => false,
                'city' => 'Chitwan',
                'caption' => 'Spacious common area for socializing',
                'is_room_image' => false,
                'room_number' => null
            ],
            [
                'id' => 'fallback_5',
                'media_type' => 'photo',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1558036117-15e82a2c9a9a?w=800&h=450&fit=crop',
                'title' => 'Clean Bathroom',
                'hostel_name' => 'HostelHub',
                'hostel_id' => 0,
                'is_featured_hostel' => false,
                'city' => 'Dharan',
                'caption' => 'Clean and modern bathroom facilities',
                'is_room_image' => false,
                'room_number' => null
            ]
        ];

        // Repeat the set if needed
        $items = collect();
        for ($i = 0; $i < $count; $i++) {
            $fallbackIndex = $i % count($fallbackSet);
            $items->push($fallbackSet[$fallbackIndex]);
        }

        \Log::info("Added {$count} fallback images to gallery");
        return $items;
    }

    /**
     * Format gallery item for consistent structure
     */
    private function formatGalleryItem($room, $hostel)
    {
        return [
            'id' => $room->id,
            'media_type' => 'photo',
            'thumbnail_url' => asset('storage/' . $room->image),
            'title' => $room->room_number ? 'Room ' . $room->room_number : $hostel->name . ' Room',
            'hostel_name' => $hostel->name,
            'hostel_id' => $hostel->id,
            'is_featured_hostel' => $hostel->is_featured ?? false,
            'city' => $hostel->city,
            'caption' => $room->description ?: ($room->room_number ? 'Room ' . $room->room_number : 'Hostel Room'),
            'is_room_image' => true,
            'room_number' => $room->room_number
        ];
    }

    /**
     * Clear gallery cache when hostel/room data changes
     */
    public function clearGalleryCache()
    {
        $totalPublished = Hostel::where('is_published', true)
            ->where('status', 'active')
            ->count();

        $cacheKey = 'smart_gallery_' . $totalPublished . '_' . now()->format('Y-m-d_H');
        Cache::forget($cacheKey);

        // Also clear any other gallery cache variations
        Cache::forget('smart_gallery_*');
        \Log::info("Gallery cache cleared for {$totalPublished} hostels");
    }

    /**
     * Show all hostels when user clicks "सबै होस्टल"
     */
    public function allHostels(Request $request)
    {
        try {
            $query = Hostel::where('is_published', true)
                ->where('status', 'active');

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            }

            // City filter
            if ($request->has('city') && !empty($request->city)) {
                $query->where('city', $request->city);
            }

            $hostels = $query->with([
                'images',
                'reviews' => function ($query) {
                    $query->where('is_published', true);
                },
                'rooms' => function ($roomQuery) {
                    $roomQuery->where('status', 'available')
                        ->where('available_beds', '>', 0);
                }
            ])
                ->withCount(['rooms as available_rooms_count' => function ($roomQuery) {
                    $roomQuery->where('status', 'available')
                        ->where('available_beds', '>', 0);
                }])
                ->withAvg('reviews', 'rating')
                ->paginate(12);

            $cities = Hostel::where('is_published', true)
                ->whereNotNull('city')
                ->distinct()
                ->pluck('city')
                ->filter();

            $searchFilters = [
                'city' => $request->city,
                'search' => $request->search
            ];

            return view('frontend.search-results', compact('hostels', 'cities', 'searchFilters'));
        } catch (\Exception $e) {
            \Log::error('All hostels page error: ' . $e->getMessage());

            $hostels = Hostel::where('id', 0)->paginate(12);
            $cities = collect([]);
            $searchFilters = $request->all();

            return view('frontend.search-results', compact('hostels', 'cities', 'searchFilters'))
                ->with('error', 'होस्टलहरू लोड गर्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * Process gallery item for home page WITH HOSTEL NAME
     */
    private function processGalleryItemForHome(Gallery $item): array
    {
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
            'hostel_name' => $item->hostel_name,
            'hostel_id' => $item->hostel_id,
            'is_room_image' => !is_null($item->room_id)
        ];

        if ($item->media_type === 'photo') {
            $processed['file_url'] = asset('storage/' . $item->file_path);
            $processed['thumbnail_url'] = $item->thumbnail
                ? asset('storage/' . $item->thumbnail)
                : $processed['file_url'];
        } elseif ($item->media_type === 'local_video') {
            $processed['file_url'] = asset('storage/' . $item->file_path);
            $processed['thumbnail_url'] = $item->thumbnail
                ? asset('storage/' . $item->thumbnail)
                : asset('images/video-default.jpg');
        } elseif ($item->media_type === 'external_video') {
            $youtubeId = $this->getYoutubeIdFromUrl($item->external_link);
            $processed['youtube_id'] = $youtubeId;
            $processed['thumbnail_url'] = $item->thumbnail
                ? asset('storage/' . $item->thumbnail)
                : ($youtubeId
                    ? "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg"
                    : asset('images/video-default.jpg'));
        }

        return $processed;
    }

    /**
     * Extract YouTube ID from URL
     */
    private function getYoutubeIdFromUrl(string $url): ?string
    {
        if (empty($url)) return null;

        $pattern = '%^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/.+&v=))([\w-]{11})$%x';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Calculate room occupancy rate.
     */
    private function getOccupancyRate(): float
    {
        try {
            $totalRooms = Room::count();
            $occupiedRooms = Room::where('status', 'occupied')->count();

            return $totalRooms > 0
                ? round(($occupiedRooms / $totalRooms) * 100, 2)
                : 0.0;
        } catch (\Exception $e) {
            Log::error('Occupancy rate calculation failed: ' . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * 🚨 FIXED: Unified search method with CORRECT price filtering
     */
    public function search(Request $request)
    {
        try {
            \Log::info("=== SEARCH REQUEST ===", $request->all());

            $request->validate([
                'city' => 'nullable|string|min:2',
                'hostel_id' => 'nullable|exists:hostels,id',
                'check_in' => 'nullable|date',
                'check_out' => 'nullable|date|after:check_in',
                'min_price' => 'nullable|numeric|min:0',
                'max_price' => 'nullable|numeric|min:0',
                'amenities' => 'nullable|array',
                'hostel_type' => 'nullable|string'
            ]);

            $query = Hostel::where('is_published', true)
                ->where('status', 'active');

            if ($request->filled('city')) {
                $query->where('city', 'like', '%' . $request->city . '%');
            }

            if ($request->filled('hostel_id')) {
                $query->where('id', $request->hostel_id);
            }

            if ($request->filled('q') || $request->filled('search')) {
                $searchTerm = $request->get('q') ?? $request->get('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('address', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            if ($request->filled('min_price') || $request->filled('max_price')) {
                $minPrice = $request->filled('min_price') ? (float) $request->min_price : 0;
                $maxPrice = $request->filled('max_price') ? (float) $request->max_price : 1000000;

                $hostelIds = $query->pluck('id')->toArray();
                $filteredHostelIds = [];

                foreach ($hostelIds as $hostelId) {
                    $hostel = Hostel::find($hostelId);
                    if (!$hostel) continue;

                    $startingPrice = $hostel->min_price;
                    if (is_null($startingPrice)) {
                        continue;
                    }

                    $startingPrice = (float) $startingPrice;
                    $includeHostel = true;

                    if ($minPrice > 0 && $startingPrice < $minPrice) {
                        $includeHostel = false;
                    }

                    if ($maxPrice > 0 && $maxPrice < 1000000 && $startingPrice > $maxPrice) {
                        $includeHostel = false;
                    }

                    if ($includeHostel) {
                        $filteredHostelIds[] = $hostelId;
                    }
                }

                if (!empty($filteredHostelIds)) {
                    $query->whereIn('id', $filteredHostelIds);
                } else {
                    $query->where('id', 0);
                }
            }

            if ($request->filled('hostel_type') && $request->hostel_type != 'all') {
                $hostelType = $request->hostel_type;
                $query->where(function ($q) use ($hostelType) {
                    if ($hostelType === 'boys') {
                        $q->where('name', 'like', '%boys%')
                            ->orWhere('name', 'like', '%Boys%')
                            ->orWhere('name', 'like', '%BOYS%')
                            ->orWhere('name', 'like', '%ब्वाइज%')
                            ->orWhere('name', 'like', '%पुरुष%')
                            ->orWhere('description', 'like', '%boys%')
                            ->orWhere('description', 'like', '%ब्वाइज%');
                    } elseif ($hostelType === 'girls') {
                        $q->where('name', 'like', '%girls%')
                            ->orWhere('name', 'like', '%Girls%')
                            ->orWhere('name', 'like', '%GIRLS%')
                            ->orWhere('name', 'like', '%गर्ल्स%')
                            ->orWhere('name', 'like', '%महिला%')
                            ->orWhere('description', 'like', '%girls%')
                            ->orWhere('description', 'like', '%गर्ल्स%');
                    }
                });
            }

            if ($request->filled('amenities') && is_array($request->amenities)) {
                $amenities = $request->amenities;
                $query->where(function ($q) use ($amenities) {
                    foreach ($amenities as $amenity) {
                        $q->orWhere('facilities', 'like', '%"' . $amenity . '"%')
                            ->orWhere('facilities', 'like', "%{$amenity}%");
                    }
                });
            }

            $hostels = $query->with([
                'images',
                'reviews' => function ($query) {
                    $query->where('is_published', true);
                },
                'rooms' => function ($roomQuery) {
                    $roomQuery->where('status', 'available')
                        ->where('available_beds', '>', 0);
                }
            ])
                ->withCount(['rooms as available_rooms_count' => function ($roomQuery) {
                    $roomQuery->where('status', 'available')
                        ->where('available_beds', '>', 0);
                }])
                ->withAvg('reviews', 'rating')
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            $cities = Cache::remember('search_cities', 3600, function () {
                return Hostel::where('is_published', true)
                    ->whereNotNull('city')
                    ->distinct()
                    ->pluck('city')
                    ->filter();
            });

            $searchFilters = [
                'city' => $request->city,
                'hostel_id' => $request->hostel_id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'amenities' => $request->amenities ?? [],
                'hostel_type' => $request->hostel_type,
                'q' => $request->q ?? $request->search
            ];

            \Log::info("✅ Search successful", [
                'total_hostels' => $hostels->total(),
                'filters' => $searchFilters
            ]);

            return view('frontend.search-results', compact('hostels', 'cities', 'searchFilters'));
        } catch (\Exception $e) {
            \Log::error('❌ Search error: ' . $e->getMessage());

            $hostels = Hostel::where('id', 0)->paginate(12);
            $cities = collect([]);
            $searchFilters = $request->all();

            return view('frontend.search-results', compact('hostels', 'cities', 'searchFilters'))
                ->with('error', 'खोजी प्रक्रिया असफल: ' . $e->getMessage());
        }
    }



    /**
     * ✅ FIXED: Show only available rooms gallery
     */
    public function hostelGallery($slug)
    {
        try {
            \Log::info("=== MAIN GALLERY DEBUG START ===", ['slug' => $slug]);

            $hostel = Hostel::where('slug', $slug)
                ->with('owner')
                ->first();

            if (!$hostel) {
                abort(404, 'होस्टल फेला परेन।');
            }

            \Log::info("Hostel data loaded:", [
                'hostel_id' => $hostel->id,
                'hostel_name' => $hostel->name,
                'contact_phone' => $hostel->contact_phone,
                'owner_phone' => $hostel->owner ? $hostel->owner->phone : null,
                'owner_email' => $hostel->owner ? $hostel->owner->email : null
            ]);

            $rooms = Room::where('hostel_id', $hostel->id)
                ->orderBy('room_number')
                ->get([
                    'id',
                    'room_number',
                    'type',
                    'capacity',
                    'current_occupancy',
                    'available_beds',
                    'price',
                    'status',
                    'image',
                    'description'
                ]);

            \Log::info("Room data from database:", [
                'hostel_id' => $hostel->id,
                'total_rooms' => $rooms->count(),
                'room_details' => $rooms->map(function ($room) {
                    return [
                        'room_number' => $room->room_number,
                        'type' => $room->type,
                        'capacity' => $room->capacity,
                        'current_occupancy' => $room->current_occupancy,
                        'available_beds' => $room->available_beds,
                        'status' => $room->status,
                        'price' => $room->price
                    ];
                })
            ]);

            $availableRoomCounts = [];
            $availableBedsCounts = [];

            foreach ($rooms as $room) {
                $type = $room->type;

                if (!isset($availableRoomCounts[$type])) {
                    $availableRoomCounts[$type] = 0;
                    $availableBedsCounts[$type] = 0;
                }

                if ($room->status === 'available' && $room->available_beds > 0) {
                    $availableRoomCounts[$type]++;
                    $availableBedsCounts[$type] += $room->available_beds;
                }
            }

            $availableRoomCounts = array_filter($availableRoomCounts);
            $availableBedsCounts = array_intersect_key($availableBedsCounts, $availableRoomCounts);

            $mealMenus = MealMenu::where('hostel_id', $hostel->id)
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->orderBy('meal_type')
                ->get();

            $galleries = Gallery::with(['hostel', 'room'])
                ->where('hostel_id', $hostel->id)
                ->where('is_active', true)
                ->where('media_type', 'photo')
                ->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other', 'साझा कोठा'])
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info("Gallery data loaded:", [
                'hostel_id' => $hostel->id,
                'total_rooms' => $rooms->count(),
                'available_room_counts' => $availableRoomCounts,
                'available_beds_counts' => $availableBedsCounts,
                'galleries_count' => $galleries->count(),
                'contact_info' => [
                    'phone' => $hostel->contact_phone,
                    'owner_phone' => $hostel->owner ? $hostel->owner->phone : null,
                    'email' => $hostel->owner ? $hostel->owner->email : null
                ]
            ]);

            return view('public.hostels.gallery', compact(
                'hostel',
                'rooms',
                'availableRoomCounts',
                'availableBedsCounts',
                'mealMenus',
                'galleries'
            ));
        } catch (\Exception $e) {
            \Log::error('Main gallery error: ' . $e->getMessage());
            abort(404, 'ग्यालरी लोड गर्न असफल।');
        }
    }

    // Other methods remain unchanged...

    // Add this temporary debugging method to see what's happening
    private function debugRoomOccupancy($hostelId)
    {
        $rooms = Room::where('hostel_id', $hostelId)->get();

        $debugInfo = [];
        foreach ($rooms as $room) {
            $currentOccupancy = $room->students()
                ->whereIn('status', ['active', 'approved'])
                ->count();

            $debugInfo[] = [
                'room_id' => $room->id,
                'room_number' => $room->room_number,
                'type' => $room->type,
                'capacity' => $room->capacity,
                'current_occupancy' => $currentOccupancy,
                'available_beds' => $room->capacity - $currentOccupancy,
                'students' => $room->students()
                    ->whereIn('status', ['active', 'approved'])
                    ->pluck('name')
                    ->toArray()
            ];
        }

        \Log::info("ROOM OCCUPANCY DEBUG:", $debugInfo);
        return $debugInfo;
    }

    /**
     * Show full gallery with all images/videos
     */
    public function hostelFullGallery($slug)
    {
        try {
            \Log::info("=== FULL GALLERY DEBUG START ===", ['slug' => $slug]);

            $hostel = Hostel::where('slug', $slug)
                ->with(['galleries' => function ($query) {
                    $query->where('is_active', true)
                        ->with('room')
                        ->orderBy('is_featured', 'desc')
                        ->orderBy('created_at', 'desc');
                }])
                ->first();

            if (!$hostel) {
                abort(404, 'होस्टल फेला परेन।');
            }

            $mealMenus = MealMenu::where('hostel_id', $hostel->id)
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->orderBy('meal_type')
                ->get();

            $activeGalleries = $hostel->galleries->where('is_active', true);

            $categoryCounts = [
                'rooms' => $activeGalleries->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other'])->count(),
                'kitchen' => $activeGalleries->where('category', 'kitchen')->count(),
                'facilities' => $activeGalleries->whereIn('category', ['bathroom', 'common', 'living room', 'study room'])->count(),
                'video' => $activeGalleries->whereIn('media_type', ['local_video', 'external_video'])->count(),
                'meals' => $mealMenus->count()
            ];

            \Log::info("Full gallery data:", [
                'hostel_id' => $hostel->id,
                'total_galleries' => $hostel->galleries->count(),
                'category_counts' => $categoryCounts,
                'meal_menus_count' => $mealMenus->count()
            ]);

            return view('public.hostels.full-gallery', compact(
                'hostel',
                'categoryCounts',
                'mealMenus'
            ));
        } catch (\Exception $e) {
            \Log::error('Full gallery error: ' . $e->getMessage());
            abort(404, 'पूर्ण ग्यालरी लोड गर्न असफल।');
        }
    }

    /**
     * API: Get hostel gallery data WITH HOSTEL NAME BADGES
     */
    public function getHostelGalleryData($slug)
    {
        try {
            $hostel = Hostel::where('slug', $slug)->firstOrFail();

            $galleries = $hostel->galleries()
                ->with('room')
                ->where('is_active', true)
                ->get()
                ->map(function ($item) use ($hostel) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'category' => $item->category,
                        'media_type' => $item->media_type,
                        'file_path' => $item->file_path,
                        'thumbnail' => $item->thumbnail,
                        'external_link' => $item->external_link,
                        'is_featured' => $item->is_featured,
                        'is_active' => $item->is_active,
                        'media_url' => $item->media_url,
                        'thumbnail_url' => $item->thumbnail_url,
                        'is_video' => $item->is_video,
                        'is_youtube_video' => $item->is_youtube_video,
                        'youtube_embed_url' => $item->youtube_embed_url,
                        'category_nepali' => $item->category_nepali,
                        'hostel_name' => $hostel->name,
                        'room_number' => $item->room ? $item->room->room_number : null,
                        'is_room_image' => !is_null($item->room_id)
                    ];
                });

            return response()->json($galleries);
        } catch (\Exception $e) {
            \Log::error('Hostel gallery API error: ' . $e->getMessage());
            return response()->json(['error' => 'ग्यालरी डाटा लोड गर्न असफल'], 500);
        }
    }

    // Room search functionality
    public function roomSearch(): View
    {
        return view('frontend.room-search');
    }

    public function searchRooms(Request $request)
    {
        $request->validate([
            'city' => 'required|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $rooms = Room::where('status', 'available')
            ->whereHas('hostel', function ($query) use ($request) {
                $query->where('city', $request->city);
            })
            ->with('hostel')
            ->get();

        return view('frontend.search-results', compact('rooms'));
    }

    // Hostel search functionality
    public function hostelSearch(Request $request): View
    {
        $query = Hostel::query();

        if ($request->has('city') && $request->city) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $hostels = $query->where('status', 'active')->paginate(12);

        return view('frontend.hostel-search', compact('hostels'));
    }

    // Student hostel join functionality
    public function hostelJoin(): View
    {
        $hostels = Hostel::where('status', 'active')->get();
        return view('frontend.hostel-join', compact('hostels'));
    }

    public function joinHostel(Request $request, Hostel $hostel)
    {
        $user = auth()->user();

        if (!$user || !$user->student) {
            return redirect()->route('login')->with('error', 'Please login as a student to join a hostel.');
        }

        if ($user->student->hostel_id) {
            return back()->with('error', 'You are already assigned to a hostel.');
        }

        $user->student->update(['hostel_id' => $hostel->id]);

        return redirect()->route('student.dashboard')
            ->with('success', 'Successfully joined ' . $hostel->name);
    }

    // Basic page routes
    public function about(): View
    {
        return view('frontend.about');
    }

    public function features(): View
    {
        return view('frontend.features');
    }

    public function howItWorks(): View
    {
        return view('frontend.how-it-works');
    }

    public function pricing(): View
    {
        return view('frontend.pricing');
    }

    /**
     * Display testimonials page
     */
    public function testimonials(): View
    {
        $testimonials = Review::with('student')
            ->where('is_published', true)
            ->latest()
            ->paginate(6);

        return view('frontend.testimonials', compact('testimonials'));
    }

    public function contact(): View
    {
        return view('frontend.contact');
    }

    // Legal pages
    public function privacy(): View
    {
        return view('frontend.legal.privacy');
    }

    public function terms(): View
    {
        return view('frontend.legal.terms');
    }

    public function demo(): View
    {
        return view('frontend.pages.demo');
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        Newsletter::create([
            'email' => $request->email,
        ]);

        return back()->with('success', 'धन्यवाद! तपाईंको सदस्यता सफलतापूर्वक दर्ता गरियो।');
    }

    /**
     * Display hostels index page with search and filtering
     */
    public function hostelsIndex(Request $request)
    {
        $query = Hostel::where('is_published', true)->withCount('approvedReviews');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('city')) {
            $query->where('city', $request->get('city'));
        }

        $hostels = $query->paginate(12);
        $cities = Hostel::where('is_published', true)->distinct()->pluck('city');

        return view('frontend.hostels.index', compact('hostels', 'cities'));
    }

    /**
     * Display single hostel page with dynamic theme
     */
    public function hostelShow($slug)
    {
        $hostel = Hostel::where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $reviews = $hostel->approvedReviews()->with('student.user')->paginate(10);
        $reviewCount = $hostel->approvedReviews()->count();
        $avgRating = $hostel->approvedReviews()->avg('rating') ?? 0;
        $studentCount = $hostel->students()->where('status', 'active')->count();

        $logo = $this->normalizeLogoUrl($hostel->logo_path);
        $facilities = $this->parseFacilities($hostel->facilities);

        $roomGalleries = $hostel->publicRoomGalleries;

        $mealMenus = MealMenu::where('hostel_id', $hostel->id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('meal_type')
            ->get();

        \Log::info("Hostel Show - Slug: {$slug}", [
            'logo_path' => $hostel->logo_path,
            'logo_normalized' => $logo,
            'facilities_raw' => $hostel->facilities,
            'facilities_parsed' => $facilities,
            'room_galleries_count' => $roomGalleries->count(),
            'meal_menus_count' => $mealMenus->count()
        ]);

        $theme = $hostel->theme ?? 'modern';
        $viewPath = "public.hostels.themes.$theme";

        if (!view()->exists($viewPath)) {
            $viewPath = 'public.hostels.themes.modern';
        }

        return view($viewPath, compact(
            'hostel',
            'reviews',
            'reviewCount',
            'avgRating',
            'studentCount',
            'logo',
            'facilities',
            'roomGalleries',
            'mealMenus'
        ));
    }

    /**
     * Better logo URL normalization
     */
    private function normalizeLogoUrl($logoPath)
    {
        if (empty($logoPath)) {
            return null;
        }

        if (filter_var($logoPath, FILTER_VALIDATE_URL)) {
            return $logoPath;
        }

        if (str_starts_with($logoPath, 'http')) {
            return $logoPath;
        }

        $cleanPath = ltrim($logoPath, '/');

        if (\Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    /**
     * Better facilities parsing with Unicode handling
     */
    private function parseFacilities($facilitiesData)
    {
        if (empty($facilitiesData)) {
            return [];
        }

        if (is_array($facilitiesData)) {
            return array_values(array_filter(array_map('trim', $facilitiesData)));
        }

        if (is_string($facilitiesData)) {
            $decoded = json_decode($facilitiesData, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $flattened = [];
                array_walk_recursive($decoded, function ($item) use (&$flattened) {
                    if (is_string($item) && !empty(trim($item))) {
                        $trimmed = trim($item);
                        $cleaned = $this->cleanAndDecodeString($trimmed);
                        if (!empty($cleaned)) {
                            $flattened[] = $cleaned;
                        }
                    }
                });
                return array_values(array_unique(array_filter($flattened)));
            } else {
                $facilities = preg_split('/\r\n|\r|\n|,/', $facilitiesData);
                $cleaned = array_map(function ($item) {
                    return $this->cleanAndDecodeString($item);
                }, $facilities);

                return array_values(array_unique(array_filter($cleaned, function ($item) {
                    return !empty($item) && $item !== '""' && $item !== "''";
                })));
            }
        }

        return [];
    }

    /**
     * Clean and decode string with Unicode support
     */
    private function cleanAndDecodeString($string)
    {
        $trimmed = trim($string);
        $trimmed = trim($trimmed, ' ,"\'[]{}');

        if (empty($trimmed) || $trimmed === '""' || $trimmed === "''") {
            return null;
        }

        if (preg_match('/\\\\u[0-9a-fA-F]{4}/', $trimmed)) {
            $decoded = json_decode('"' . str_replace('"', '\\"', $trimmed) . '"');
            if ($decoded !== null && json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return $trimmed;
    }

    /**
     * Preview hostel for owners/admins with dynamic theme
     */
    public function hostelPreview($slug)
    {
        $hostel = Hostel::with([
            'images',
            'rooms',
            'approvedReviews.student.user',
            'mealMenus'
        ])->where('slug', $slug)->firstOrFail();

        $user = auth()->user();

        if ($user) {
            if ($user->hasRole('admin')) {
                return $this->renderHostelPreview($hostel);
            }

            if ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
                $userOrganizationId = session('current_organization_id');

                if ($hostel->organization_id == $userOrganizationId) {
                    return $this->renderHostelPreview($hostel);
                }
            }
        }

        abort(404, 'होस्टल फेला परेन वा तपाईंसँग यसलाई हेर्ने अनुमति छैन।');
    }

    /**
     * Helper method to render hostel preview with theme
     */
    private function renderHostelPreview($hostel)
    {
        $reviews = $hostel->approvedReviews()->with('student.user')->paginate(10);
        $reviewCount = $hostel->approvedReviews()->count();
        $avgRating = $hostel->approvedReviews()->avg('rating') ?? 0;
        $studentCount = $hostel->students()->where('status', 'active')->count();

        $logo = $this->normalizeLogoUrl($hostel->logo);
        $facilities = $this->parseFacilities($hostel->facilities);

        $roomGalleries = $hostel->publicRoomGalleries;

        $mealMenus = MealMenu::where('hostel_id', $hostel->id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('meal_type')
            ->get();

        $theme = $hostel->theme ?? 'modern';
        $viewPath = "public.hostels.themes.$theme";

        if (!view()->exists($viewPath)) {
            $viewPath = "public.hostels.themes.modern";
        }

        return view($viewPath, compact(
            'hostel',
            'reviews',
            'reviewCount',
            'avgRating',
            'studentCount',
            'logo',
            'facilities',
            'roomGalleries',
            'mealMenus'
        ))->with('preview', true);
    }

    // Gallery Integration Methods

    /**
     * Get featured gallery items for homepage WITH HOSTEL NAMES
     */
    private function getFeaturedGalleryItems()
    {
        return Cache::remember('featured_galleries_with_hostels', 3600, function () {
            return Gallery::with(['hostel', 'room.hostel'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get()
                ->map(function ($item) {
                    return $this->processGalleryItemForHome($item);
                });
        });
    }

    /**
     * Get recent gallery items for homepage WITH HOSTEL NAMES
     */
    private function getRecentGalleryItems()
    {
        return Cache::remember('recent_galleries_with_hostels', 3600, function () {
            return Gallery::with(['hostel', 'room.hostel'])
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(12)
                ->get()
                ->map(function ($item) {
                    return $this->processGalleryItemForHome($item);
                });
        });
    }

    /**
     * API: Get public gallery data WITH HOSTEL NAMES
     */
    public function getPublicGalleryData()
    {
        try {
            $galleries = Cache::remember('public_galleries_with_hostels', 3600, function () {
                return Gallery::with(['hostel', 'room.hostel'])
                    ->where('is_active', true)
                    ->orderBy('is_featured', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($item) {
                        return $this->formatGalleryItemWithHostel($item);
                    });
            });

            return response()->json($galleries);
        } catch (\Exception $e) {
            \Log::error('Public gallery API error: ' . $e->getMessage());
            return response()->json(['error' => 'ग्यालरी डाटा लोड गर्न असफल'], 500);
        }
    }

    /**
     * Format gallery item WITH HOSTEL NAME for API response
     */
    private function formatGalleryItemWithHostel($item): array
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'category' => $item->category,
            'media_type' => $item->media_type,
            'file_url' => $item->media_url,
            'thumbnail_url' => $item->thumbnail_url,
            'external_link' => $item->external_link,
            'created_at' => $item->created_at->format('Y-m-d'),
            'hostel_name' => $item->hostel_name,
            'hostel_id' => $item->hostel_id,
            'room_number' => $item->room ? $item->room->room_number : null,
            'is_room_image' => !is_null($item->room_id)
        ];
    }

    /**
     * Get available hostels for search
     */
    public function getAvailableHostels(Request $request)
    {
        try {
            $query = Hostel::where('is_published', true)
                ->where('status', 'active');

            if ($request->has('city') && !empty($request->city)) {
                $query->where('city', 'like', '%' . $request->city . '%');
            }

            $hostels = $query->select('id', 'name', 'city', 'slug')
                ->orderBy('name')
                ->get();

            return response()->json($hostels);
        } catch (\Exception $e) {
            \Log::error('Available hostels API error: ' . $e->getMessage());
            return response()->json(['error' => 'होस्टल डाटा लोड गर्न असफल'], 500);
        }
    }

    /**
     * ✅ NEW: Get homepage gallery data (REQUIRED BY ROUTE)
     */
    public function getHomepageGallery()
    {
        try {
            // Get featured galleries for homepage
            $featuredGalleries = \App\Models\Gallery::with(['hostel'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->whereHas('hostel', function ($query) {
                    $query->where('is_published', true);
                })
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();

            return response()->json([
                'success' => true,
                'featured_galleries' => $featuredGalleries,
                'stats' => [
                    'total_photos' => \App\Models\Gallery::where('is_active', true)->where('media_type', 'photo')->count(),
                    'total_videos' => \App\Models\Gallery::where('is_active', true)->whereIn('media_type', ['external_video', 'local_video'])->count(),
                    'total_hostels' => \App\Models\Hostel::where('is_published', true)->count()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Homepage gallery error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load homepage gallery'
            ], 500);
        }
    }

    /**
     * Get room availability for specific hostel
     */
    public function getHostelRoomAvailability($slug)
    {
        try {
            $hostel = Hostel::where('slug', $slug)
                ->with(['rooms' => function ($query) {
                    $query->where('status', 'available')
                        ->where('available_beds', '>', 0)
                        ->select('id', 'room_number', 'type', 'capacity', 'available_beds', 'price');
                }])
                ->firstOrFail();

            $availableRooms = $hostel->rooms->groupBy('type')->map(function ($rooms, $type) {
                return [
                    'type' => $type,
                    'count' => $rooms->count(),
                    'min_price' => $rooms->min('price'),
                    'max_price' => $rooms->max('price'),
                    'rooms' => $rooms
                ];
            });

            return response()->json([
                'hostel' => [
                    'name' => $hostel->name,
                    'slug' => $hostel->slug,
                    'city' => $hostel->city
                ],
                'available_rooms' => $availableRooms
            ]);
        } catch (\Exception $e) {
            \Log::error('Hostel room availability error: ' . $e->getMessage());
            return response()->json(['error' => 'कोठा उपलब्धता जानकारी लोड गर्न असफल'], 500);
        }
    }

    /**
     * Quick search functionality
     */
    public function quickSearch(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        try {
            $query = $request->get('query');

            $hostels = Hostel::where('is_published', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('city', 'like', "%{$query}%")
                        ->orWhere('address', 'like', "%{$query}%");
                })
                ->select('id', 'name', 'city', 'slug', 'logo_path')
                ->take(5)
                ->get()
                ->map(function ($hostel) {
                    return [
                        'id' => $hostel->id,
                        'name' => $hostel->name,
                        'city' => $hostel->city,
                        'slug' => $hostel->slug,
                        'logo' => $this->normalizeLogoUrl($hostel->logo_path),
                        'type' => 'hostel'
                    ];
                });

            $rooms = Room::where('status', 'available')
                ->where('available_beds', '>', 0)
                ->whereHas('hostel', function ($q) use ($query) {
                    $q->where('is_published', true)
                        ->where(function ($hostelQuery) use ($query) {
                            $hostelQuery->where('name', 'like', "%{$query}%")
                                ->orWhere('city', 'like', "%{$query}%");
                        });
                })
                ->with('hostel:id,name,slug,city')
                ->select('id', 'room_number', 'type', 'price', 'hostel_id')
                ->take(5)
                ->get()
                ->map(function ($room) {
                    return [
                        'id' => $room->id,
                        'room_number' => $room->room_number,
                        'type' => $room->type,
                        'price' => $room->price,
                        'hostel_name' => $room->hostel->name,
                        'hostel_slug' => $room->hostel->slug,
                        'city' => $room->hostel->city,
                        'type' => 'room'
                    ];
                });

            return response()->json([
                'hostels' => $hostels,
                'rooms' => $rooms,
                'total_results' => $hostels->count() + $rooms->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Quick search error: ' . $e->getMessage());
            return response()->json(['error' => 'खोजी प्रक्रिया असफल'], 500);
        }
    }

    // Meal Gallery Methods

    /**
     * Show meal gallery page
     */
    public function mealGallery(Request $request): View
    {
        $search = $request->get('search');
        $type = $request->get('type');

        $mealMenus = MealMenu::with('hostel')
            ->where('is_active', true)
            ->whereHas('hostel', function ($query) {
                $query->where('is_published', true);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('description', 'like', "%{$search}%")
                    ->orWhereHas('hostel', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($type && $type != 'all', function ($query) use ($type) {
                return $query->where('meal_type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('frontend.pages.meal-gallery', compact('mealMenus', 'search', 'type'));
    }

    /**
     * Get meal menu data for API
     */
    public function getMealMenuData($hostelId = null)
    {
        try {
            $query = MealMenu::with('hostel')
                ->where('is_active', true);

            if ($hostelId) {
                $query->where('hostel_id', $hostelId);
            }

            $mealMenus = $query->orderBy('day_of_week')
                ->orderBy('meal_type')
                ->get()
                ->map(function ($menu) {
                    return [
                        'id' => $menu->id,
                        'hostel_id' => $menu->hostel_id,
                        'hostel_name' => $menu->hostel->name ?? 'Unknown Hostel',
                        'meal_type' => $menu->meal_type,
                        'meal_type_nepali' => $this->getMealTypeNepali($menu->meal_type),
                        'day_of_week' => $menu->day_of_week,
                        'items' => $menu->items,
                        'formatted_items' => $menu->formatted_items,
                        'image' => $menu->image ? asset('storage/' . $menu->image) : null,
                        'description' => $menu->description,
                        'is_active' => $menu->is_active,
                        'created_at' => $menu->created_at->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json($mealMenus);
        } catch (\Exception $e) {
            \Log::error('Meal menu API error: ' . $e->getMessage());
            return response()->json(['error' => 'खानाको मेनु डाटा लोड गर्न असफल'], 500);
        }
    }

    /**
     * Helper method to get meal type in Nepali
     */
    private function getMealTypeNepali($mealType): string
    {
        switch ($mealType) {
            case 'breakfast':
                return 'विहानको खाना';
            case 'lunch':
                return 'दिउसोको खाना';
            case 'dinner':
                return 'बेलुकाको खाना';
            default:
                return $mealType;
        }
    }

    // ======================================================================
    // ENHANCED BOOKING SYSTEM METHODS WITH DYNAMIC ROOM LOADING
    // ======================================================================

    /**
     * ✅ FIXED: Show booking form for specific hostel with dynamic room data
     */
    public function bookForm($slug)
    {
        try {
            $hostel = Hostel::where('slug', $slug)
                ->where('is_published', true)
                ->where('status', 'active')
                ->firstOrFail();

            $checkIn = request('check_in');
            $checkOut = request('check_out');
            $datesLocked = !empty($checkIn) && !empty($checkOut);

            if ($datesLocked) {
                $today = now()->format('Y-m-d');
                if ($checkIn < $today) {
                    $checkIn = $today;
                }
                if ($checkOut && $checkOut <= $checkIn) {
                    $checkOut = null;
                }
            }

            $roomsQuery = Room::where('hostel_id', $hostel->id)
                ->where('status', 'available');

            $roomsQuery->withCount(['bookings as approved_bookings_count' => function ($query) {
                $query->where('status', Booking::STATUS_APPROVED);
            }]);

            if ($datesLocked && $checkIn) {
                $roomsQuery->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                    $q->where('status', Booking::STATUS_APPROVED)
                        ->where(function ($bookingQuery) use ($checkIn, $checkOut) {
                            $bookingQuery->whereBetween('check_in_date', [$checkIn, $checkOut])
                                ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                                ->orWhere(function ($subQuery) use ($checkIn, $checkOut) {
                                    $subQuery->where('check_in_date', '<=', $checkIn)
                                        ->where('check_out_date', '>=', $checkOut);
                                });
                        });
                });
            }

            $availableRooms = $roomsQuery->get()->map(function ($room) {
                $actualAvailableBeds = $room->capacity - $room->approved_bookings_count;

                $nepaliTypes = [
                    '1 seater' => 'एक सिटर कोठा',
                    '2 seater' => 'दुई सिटर कोठा',
                    '3 seater' => 'तीन सिटर कोठा',
                    '4 seater' => 'चार सिटर कोठा',
                    'single' => 'एक सिटर कोठा',
                    'double' => 'दुई सिटर कोठा',
                    'triple' => 'तीन सिटर कोठा',
                    'quad' => 'चार सिटर कोठा',
                    'shared' => 'साझा कोठा',
                    'other' => 'अन्य कोठा'
                ];

                return [
                    'id' => $room->id,
                    'value' => $room->id,
                    'label' => ($nepaliTypes[$room->type] ?? $room->type)
                        . " - कोठा {$room->room_number} (उपलब्ध: {$actualAvailableBeds}, रु {$room->price})",
                    'room_number' => $room->room_number,
                    'type' => $room->type,
                    'nepali_type' => ($nepaliTypes[$room->type] ?? $room->type),
                    'available_beds' => $actualAvailableBeds,
                    'price' => $room->price,
                    'capacity' => $room->capacity
                ];
            })->filter(function ($room) {
                return $room['available_beds'] > 0;
            });

            $selectedRoom = null;
            $roomId = request('room_id');
            if ($roomId) {
                $selectedRoom = Room::where('id', $roomId)
                    ->where('hostel_id', $hostel->id)
                    ->where('status', 'available')
                    ->withCount(['bookings as approved_bookings_count' => function ($query) {
                        $query->where('status', Booking::STATUS_APPROVED);
                    }])
                    ->first();

                if ($selectedRoom) {
                    $actualAvailableBeds = $selectedRoom->capacity - $selectedRoom->approved_bookings_count;
                    $selectedRoom->available_beds = $actualAvailableBeds;

                    $nepaliTypes = [
                        '1 seater' => 'एक सिटर कोठा',
                        '2 seater' => 'दुई सिटर कोठा',
                        '3 seater' => 'तीन सिटर कोठा',
                        '4 seater' => 'चार सिटर कोठा',
                        'single' => 'एक सिटर कोठा',
                        'double' => 'दुई सिटर कोठा',
                        'triple' => 'तीन सिटर कोठा',
                        'quad' => 'चार सिटर कोठा',
                        'shared' => 'साझा कोठा',
                        'other' => 'अन्य कोठा'
                    ];
                    $selectedRoom->nepali_type = $nepaliTypes[$selectedRoom->type] ?? $selectedRoom->type;
                }
            }

            return view('frontend.booking.form', compact(
                'hostel',
                'availableRooms',
                'checkIn',
                'checkOut',
                'datesLocked',
                'selectedRoom'
            ));
        } catch (\Exception $e) {
            \Log::error('Booking form error: ' . $e->getMessage());
            abort(404, 'होस्टल फेला परेन वा बुकिंगको लागि उपलब्ध छैन');
        }
    }

    /**
     * ✅ FIXED: Get available rooms for hostel with date filtering
     */
    public function getHostelRooms(Request $request, $slug)
    {
        try {
            $hostel = Hostel::where('slug', $slug)
                ->where('is_published', true)
                ->where('status', 'active')
                ->firstOrFail();

            $checkIn = $request->get('check_in');
            $checkOut = $request->get('check_out');

            $roomsQuery = $hostel->rooms()
                ->where('status', 'available');

            $roomsQuery->withCount(['bookings as approved_bookings_count' => function ($query) {
                $query->where('status', Booking::STATUS_APPROVED);
            }]);

            if ($checkIn && $checkOut) {
                $roomsQuery->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                    $q->where('status', Booking::STATUS_APPROVED)
                        ->where(function ($bookingQuery) use ($checkIn, $checkOut) {
                            $bookingQuery->whereBetween('check_in_date', [$checkIn, $checkOut])
                                ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                                ->orWhere(function ($subQuery) use ($checkIn, $checkOut) {
                                    $subQuery->where('check_in_date', '<=', $checkIn)
                                        ->where('check_out_date', '>=', $checkOut);
                                });
                        });
                });
            }

            $rooms = $roomsQuery->get()->map(function ($room) {
                $actualAvailableBeds = $room->capacity - $room->approved_bookings_count;

                $nepaliTypes = [
                    '1 seater' => 'एक सिटर कोठा',
                    '2 seater' => 'दुई सिटर कोठा',
                    '3 seater' => 'तीन सिटर कोठा',
                    '4 seater' => 'चार सिटर कोठा',
                    'single' => 'एक सिटर कोठा',
                    'double' => 'दुई सिटर कोठा',
                    'triple' => 'तीन सिटर कोठा',
                    'quad' => 'चार सिटर कोठा',
                    'shared' => 'साझा कोठा',
                    'other' => 'अन्य कोठा'
                ];

                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'type' => $room->type,
                    'nepali_type' => $nepaliTypes[$room->type] ?? $room->type,
                    'capacity' => $room->capacity,
                    'available_beds' => $actualAvailableBeds,
                    'price' => $room->price,
                    'formatted_price' => 'रु ' . number_format($room->price),
                    'description' => $room->description
                ];
            })->filter(function ($room) {
                return $room['available_beds'] > 0;
            });

            return response()->json([
                'success' => true,
                'rooms' => $rooms,
                'hostel' => [
                    'name' => $hostel->name,
                    'slug' => $hostel->slug
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Get hostel rooms error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'कोठा डाटा लोड गर्न असफल'
            ], 500);
        }
    }

    /**
     * Store booking request with enhanced validation
     */
    public function storeBooking(Request $request, $slug)
    {
        try {
            $hostel = Hostel::where('slug', $slug)
                ->where('is_published', true)
                ->where('status', 'active')
                ->firstOrFail();

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'phone' => 'required|string|max:15',
                'email' => 'nullable|email|max:100',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'room_id' => 'required|exists:rooms,id',
                'message' => 'nullable|string|max:500'
            ]);

            $room = Room::where('id', $validated['room_id'])
                ->where('hostel_id', $hostel->id)
                ->where('status', 'available')
                ->withCount(['bookings as approved_bookings_count' => function ($query) {
                    $query->where('status', Booking::STATUS_APPROVED);
                }])
                ->first();

            if (!$room) {
                return back()->withInput()->with('error', 'यो कोठा अहिले उपलब्ध छैन वा तपाईंले छान्नुभएको कोठा यस होस्टलमा छैन।');
            }

            $actualAvailableBeds = $room->capacity - $room->approved_bookings_count;
            if ($actualAvailableBeds <= 0) {
                return back()->withInput()->with('error', 'यो कोठा अहिले उपलब्ध छैन। कृपया अर्को कोठा छान्नुहोस्।');
            }

            $conflictingBookings = Booking::where('room_id', $room->id)
                ->where('status', Booking::STATUS_APPROVED)
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('check_in_date', [$validated['check_in_date'], $validated['check_out_date']])
                        ->orWhereBetween('check_out_date', [$validated['check_in_date'], $validated['check_out_date']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('check_in_date', '<=', $validated['check_in_date'])
                                ->where('check_out_date', '>=', $validated['check_out_date']);
                        });
                })
                ->count();

            if ($conflictingBookings > 0) {
                return back()->withInput()->with('error', 'यो कोठा उक्त मितिहरूमा पहिले नै बुक गरिएको छ। कृपया अर्को मिति वा कोठा छान्नुहोस्।');
            }

            $booking = Booking::create([
                'hostel_id' => $hostel->id,
                'room_id' => $room->id,
                'guest_name' => $validated['name'],
                'guest_phone' => $validated['phone'],
                'guest_email' => $validated['email'],
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'status' => Booking::STATUS_PENDING,
                'amount' => $room->price,
                'is_guest_booking' => true,
                'booking_date' => now(),
                'payment_status' => 'pending'
            ]);

            return redirect()->route('booking.success', $booking->id)
                ->with('success', 'तपाईंको बुकिंग अनुरोध सफलतापूर्वक पेश गरियो। होस्टल प्रबन्धकले चाँडै नै तपाईंसँग सम्पर्क गर्नेछन्।');
        } catch (\Exception $e) {
            \Log::error('Store booking error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'बुकिंग अनुरोध सिर्जना गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Show booking success page
     */
    public function bookingSuccess($id)
    {
        $bookingRequest = BookingRequest::with(['hostel', 'room'])
            ->findOrFail($id);

        $nepaliTypes = [
            '1 seater' => 'एक सिटर कोठा',
            '2 seater' => 'दुई सिटर कोठा',
            '3 seater' => 'तीन सिटर कोठा',
            '4 seater' => 'चार सिटर कोठा',
            'single' => 'एक सिटर कोठा',
            'double' => 'दुई सिटर कोठा',
            'triple' => 'तीन सिटर कोठा',
            'quad' => 'चार सिटर कोठा',
            'shared' => 'साझा कोठा',
            'other' => 'अन्य कोठा'
        ];

        $bookingRequest->status_nepali = match ($bookingRequest->status) {
            'pending' => 'पेन्डिङ',
            'approved' => 'स्वीकृत',
            'rejected' => 'अस्वीकृत',
            'cancelled' => 'रद्द भयो',
            default => $bookingRequest->status
        };

        $bookingRequest->status_badge_class = match ($bookingRequest->status) {
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'cancelled' => 'bg-secondary',
            default => 'bg-light text-dark'
        };

        $bookingRequest->room_type_nepali = $nepaliTypes[$bookingRequest->room_type] ?? $bookingRequest->room_type;

        return view('frontend.booking.success', compact('bookingRequest'));
    }

    /**
     * Show booking success page for NEW booking system
     */
    public function bookingSuccessNew($id)
    {
        try {
            $bookingRequest = BookingRequest::with(['hostel', 'room'])->find($id);

            if ($bookingRequest) {
                $nepaliTypes = [
                    '1 seater' => 'एक सिटर कोठा',
                    '2 seater' => 'दुई सिटर कोठा',
                    '3 seater' => 'तीन सिटर कोठा',
                    '4 seater' => 'चार सिटर कोठा',
                    'single' => 'एक सिटर कोठा',
                    'double' => 'दुई सिटर कोठा',
                    'triple' => 'तीन सिटर कोठा',
                    'quad' => 'चार सिटर कोठा',
                    'shared' => 'साझा कोठा',
                    'other' => 'अन्य कोठा'
                ];

                $bookingRequest->status_nepali = match ($bookingRequest->status) {
                    'pending' => 'पेन्डिङ',
                    'approved' => 'स्वीकृत',
                    'rejected' => 'अस्वीकृत',
                    'cancelled' => 'रद्द भयो',
                    default => $bookingRequest->status
                };

                $bookingRequest->status_badge_class = match ($bookingRequest->status) {
                    'pending' => 'bg-warning',
                    'approved' => 'bg-success',
                    'rejected' => 'bg-danger',
                    'cancelled' => 'bg-secondary',
                    default => 'bg-light text-dark'
                };

                $bookingRequest->room_type_nepali = $nepaliTypes[$bookingRequest->room_type] ?? $bookingRequest->room_type;

                return view('frontend.booking.success', compact('bookingRequest'));
            }

            $booking = Booking::with(['hostel', 'room'])->find($id);

            if (!$booking) {
                abort(404, "Booking not found");
            }

            return view('frontend.booking.success', compact('booking'));
        } catch (\Exception $e) {
            \Log::error('Booking success page error: ' . $e->getMessage());
            abort(404, 'बुकिंग फेला परेन।');
        }
    }
    /**
     * Hostel Contact Form Process
     */
    public function hostelContact(Request $request, $hostelId)
    {
        try {
            // Validate form data
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'phone' => 'nullable|string|max:20',
                'message' => 'required|string|max:2000',
            ]);

            // Find hostel
            $hostel = Hostel::find($hostelId);

            if (!$hostel) {
                return back()->with('error', 'होस्टल फेला परेन!');
            }

            // ✅ TEMPORARY SOLUTION: Save to database table (if exists)
            // तपाईंले पहिले "hostel_messages" table बनाउनु भएको छैन भने,
            // यो काम गर्नेछ:

            // Option 1: Try to save to hostel_messages table
            try {
                // Check if table exists
                if (Schema::hasTable('hostel_messages')) {
                    DB::table('hostel_messages')->insert([
                        'hostel_id' => $hostel->id,
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'] ?? null,
                        'message' => $validated['message'],
                        'status' => 'unread',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                // Option 2: Save to contact_requests table (if exists)
                else if (Schema::hasTable('contact_requests')) {
                    DB::table('contact_requests')->insert([
                        'hostel_id' => $hostel->id,
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'] ?? null,
                        'message' => $validated['message'],
                        'type' => 'hostel_contact',
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                // Option 3: Save to messages table (if exists)
                else if (Schema::hasTable('messages')) {
                    DB::table('messages')->insert([
                        'hostel_id' => $hostel->id,
                        'sender_name' => $validated['name'],
                        'sender_email' => $validated['email'],
                        'sender_phone' => $validated['phone'] ?? null,
                        'content' => $validated['message'],
                        'status' => 'unread',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                // Option 4: Just log the message
                else {
                    \Log::info('Hostel Contact Form Submission:', [
                        'hostel_id' => $hostel->id,
                        'hostel_name' => $hostel->name,
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'] ?? null,
                        'message' => $validated['message'],
                        'ip' => $request->ip(),
                    ]);
                }

                // Log for debugging
                \Log::info('Contact form submitted for hostel: ' . $hostel->name, $validated);
            } catch (\Exception $e) {
                \Log::error('Failed to save contact message: ' . $e->getMessage());
                // Continue anyway - don't show error to user
            }

            // ✅ BONUS: Send email notification (if email is configured)
            try {
                // Check if hostel owner has email
                if ($hostel->owner && $hostel->owner->email) {
                    Mail::raw(
                        "नयाँ सन्देश तपाईंको होस्टेल '{$hostel->name}' बाट:\n\n" .
                            "नाम: {$validated['name']}\n" .
                            "ईमेल: {$validated['email']}\n" .
                            "फोन: " . ($validated['phone'] ?? 'नभएको') . "\n\n" .
                            "सन्देश:\n{$validated['message']}\n\n" .
                            "मिति: " . now()->format('Y-m-d H:i:s'),
                        function ($message) use ($hostel, $validated) {
                            $message->to($hostel->owner->email)
                                ->subject("नयाँ सन्देश: {$hostel->name} होस्टेल");
                        }
                    );
                }
            } catch (\Exception $e) {
                \Log::error('Email send failed: ' . $e->getMessage());
                // Continue - email failure shouldn't break form
            }

            // Success message in Nepali
            return back()->with([
                'success' => 'धन्यवाद! तपाईंको सन्देश सफलतापूर्वक पठाइयो। ' .
                    'होस्टेल प्रबन्धकले चाँडै नै तपाईंसँग सम्पर्क गर्नेछन्।',
                'contact_submitted' => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Hostel contact form error: ' . $e->getMessage());
            return back()->with('error', 'सन्देश पठाउँदा त्रुटि भयो। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Quick Contact Form (for homepage)
     */
    public function quickContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:1000',
        ]);

        try {
            // Save to database
            DB::table('contact_requests')->insert([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'type' => 'general_contact',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'तपाईंको सन्देश पठाइयो! हामी चाँडै नै सम्पर्क गर्नेछौं।');
        } catch (\Exception $e) {
            \Log::error('Quick contact form error: ' . $e->getMessage());
            return back()->with('error', 'त्रुटि भयो। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }

    // Method add garnu paryo:
    protected function optimizeGalleryImages($galleries)
    {
        $optimizer = app(\App\Services\ImageOptimizer::class);

        return $galleries->map(function ($gallery) use ($optimizer) {
            if ($gallery->media_type === 'image' && $gallery->media_url) {
                // Extract local path from URL
                $path = str_replace(asset('storage/'), '', $gallery->media_url);
                $path = str_replace(asset(''), '', $path);

                $gallery->optimized = $optimizer->optimizeForGallery($path);
                $gallery->placeholder = $optimizer->createPlaceholder(280, 280);
            } else {
                $gallery->optimized = null;
                $gallery->placeholder = $optimizer->createPlaceholder(280, 280, '#6366F1');
            }

            return $gallery;
        });
    }
}
