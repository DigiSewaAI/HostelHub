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
use App\Models\Booking; // ✅ ADDED: Import Booking model
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
            // 1. Featured Rooms (Available rooms with at least one vacancy) - FIXED FOR POSTGRESQL
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

            // 4. Featured Hostels (with caching) - 🚨 FIXED: Remove problematic image ordering
            $hostels = Cache::remember('home_hostels_all', 3600, function () {
                return Hostel::where('is_published', true)
                    ->where('status', 'active')
                    ->with(['images'])
                    ->get();
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

            // 7. Hero Slider Items (with caching)
            $heroSliderItems = Cache::remember('home_hero_slider_items', 3600, function () {
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

            // 8. Gallery Items (with caching)
            $galleryItems = Cache::remember('home_gallery_items', 3600, function () {
                $items = Gallery::with(['hostel', 'room.hostel'])
                    ->where('is_active', true)
                    ->whereNotNull('file_path')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get()
                    ->map(function ($item) {
                        return $this->processGalleryItemForHome($item);
                    });

                if ($items->isEmpty()) {
                    return collect([
                        [
                            'media_type' => 'image',
                            'thumbnail_url' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=400&h=300&fit=crop',
                            'title' => 'Study Room',
                            'hostel_name' => 'HostelHub'
                        ],
                        [
                            'media_type' => 'image',
                            'thumbnail_url' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&h=300&fit=crop',
                            'title' => 'Common Area',
                            'hostel_name' => 'HostelHub'
                        ]
                    ]);
                }

                return $items;
            });

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
                'featuredMealMenus'
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
                'featuredMealMenus' => collect()
            ]);
        }
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

            // 🚨 FIXED: Use empty query for pagination instead of collection
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
     * 🚨 FIXED: Unified search method without problematic image ordering and facilities relationship
     */
    public function search(Request $request)
    {
        try {
            \Log::info("=== SEARCH REQUEST ===", $request->all());

            // Simple validation
            $request->validate([
                'city' => 'required|string|min:2',
                'hostel_id' => 'nullable|exists:hostels,id',
                'check_in' => 'nullable|date',
                'check_out' => 'nullable|date|after:check_in',
                'min_price' => 'nullable|numeric|min:0',
                'max_price' => 'nullable|numeric|min:0',
                'room_type' => 'nullable|array',
                'amenities' => 'nullable|array',
                'hostel_type' => 'nullable|string'
            ]);

            // Start with active published hostels
            $query = Hostel::where('is_published', true)
                ->where('status', 'active');

            // 🔍 City filter
            if ($request->filled('city')) {
                $query->where('city', 'like', '%' . $request->city . '%');
            }

            // 🔍 Hostel filter
            if ($request->filled('hostel_id')) {
                $query->where('id', $request->hostel_id);
            }

            // 🔍 General search (name, address, description)
            if ($request->filled('q') || $request->filled('search')) {
                $searchTerm = $request->get('q') ?? $request->get('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('address', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            // 🔍 Price range filter
            if ($request->filled('min_price') || $request->filled('max_price')) {
                $minPrice = $request->get('min_price', 0);
                $maxPrice = $request->get('max_price', 100000);

                $query->whereHas('rooms', function ($roomQuery) use ($minPrice, $maxPrice) {
                    $roomQuery->where('status', 'available')
                        ->where('available_beds', '>', 0)
                        ->whereBetween('price', [$minPrice, $maxPrice]);
                });
            }

            // 🔍 Room type filter
            if ($request->filled('room_type') && is_array($request->room_type)) {
                $roomTypes = $request->room_type;
                $query->whereHas('rooms', function ($roomQuery) use ($roomTypes) {
                    $roomQuery->where('status', 'available')
                        ->where('available_beds', '>', 0)
                        ->whereIn('type', $roomTypes);
                });
            }

            // 🔍 Hostel type filter (boys/girls)
            if ($request->filled('hostel_type') && $request->hostel_type != 'all') {
                $query->where('name', 'like', '%' . $request->hostel_type . '%');
            }

            // 🔍 Amenities filter - FIXED: Use facilities column directly instead of relationship
            if ($request->filled('amenities') && is_array($request->amenities)) {
                $amenities = $request->amenities;
                $query->where(function ($q) use ($amenities) {
                    foreach ($amenities as $amenity) {
                        $q->orWhere('facilities', 'like', '%"' . $amenity . '"%')
                            ->orWhere('facilities', 'like', "%{$amenity}%");
                    }
                });
            }

            // Get hostels with relationships - FIXED: No problematic image ordering and no facilities relationship
            $hostels = $query->with([
                'images',
                'reviews' => function ($query) {
                    $query->where('is_published', true);
                },
                'rooms' => function ($roomQuery) use ($request) {
                    $roomQuery->where('status', 'available')
                        ->where('available_beds', '>', 0);

                    // Apply room-specific filters
                    if ($request->filled('min_price') || $request->filled('max_price')) {
                        $minPrice = $request->get('min_price', 0);
                        $maxPrice = $request->get('max_price', 100000);
                        $roomQuery->whereBetween('price', [$minPrice, $maxPrice]);
                    }

                    if ($request->filled('room_type') && is_array($request->room_type)) {
                        $roomQuery->whereIn('type', $request->room_type);
                    }
                }
            ])
                ->withCount(['rooms as available_rooms_count' => function ($roomQuery) {
                    $roomQuery->where('status', 'available')
                        ->where('available_beds', '>', 0);
                }])
                ->withAvg('reviews', 'rating')
                ->paginate(12);

            // Get cities and room types for filters - 🚨 FIXED: Room type mapping without using Room model method
            $cities = Cache::remember('search_cities', 3600, function () {
                return Hostel::where('is_published', true)
                    ->whereNotNull('city')
                    ->distinct()
                    ->pluck('city')
                    ->filter();
            });

            $roomTypes = Cache::remember('search_room_types', 3600, function () {
                return Room::where('status', 'available')
                    ->where('available_beds', '>', 0)
                    ->distinct()
                    ->pluck('type')
                    ->map(function ($type) {
                        // 🚨 FIXED: Use simple mapping instead of Room model method
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
                            'value' => $type,
                            'label' => $nepaliTypes[$type] ?? $type
                        ];
                    });
            });

            // Search filters for view
            $searchFilters = [
                'city' => $request->city,
                'hostel_id' => $request->hostel_id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'room_type' => $request->room_type ?? [],
                'amenities' => $request->amenities ?? [],
                'hostel_type' => $request->hostel_type,
                'q' => $request->q ?? $request->search
            ];

            \Log::info("✅ Search successful", [
                'total_hostels' => $hostels->total(),
                'filters' => $searchFilters
            ]);

            return view('frontend.search-results', compact('hostels', 'cities', 'roomTypes', 'searchFilters'));
        } catch (\Exception $e) {
            \Log::error('❌ Search error: ' . $e->getMessage());

            // Return empty results on error
            $hostels = Hostel::where('id', 0)->paginate(12);
            $cities = collect([]);
            $roomTypes = collect([]);
            $searchFilters = $request->all();

            return view('frontend.search-results', compact('hostels', 'cities', 'roomTypes', 'searchFilters'))
                ->with('error', 'खोजी प्रक्रिया असफल: ' . $e->getMessage());
        }
    }

    /**
     * Show only available rooms gallery
     */
    public function hostelGallery($slug)
    {
        try {
            \Log::info("=== MAIN GALLERY DEBUG START ===", ['slug' => $slug]);

            $hostel = Hostel::where('slug', $slug)
                ->with(['rooms' => function ($query) {
                    $query->where('available_beds', '>', 0)
                        ->where('status', '!=', 'मर्मत सम्भार');
                }])
                ->first();

            if (!$hostel) {
                abort(404, 'होस्टल फेला परेन।');
            }

            $availableRoomCounts = [
                '1 seater' => $hostel->rooms->where('type', '1 seater')->count(),
                '2 seater' => $hostel->rooms->where('type', '2 seater')->count(),
                '3 seater' => $hostel->rooms->where('type', '3 seater')->count(),
                '4 seater' => $hostel->rooms->where('type', '4 seater')->count(),
                'other' => $hostel->rooms->whereNotIn('type', ['1 seater', '2 seater', '3 seater', '4 seater'])->count(),
            ];

            $mealMenus = MealMenu::where('hostel_id', $hostel->id)
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->orderBy('meal_type')
                ->get();

            \Log::info("Fixed room counts by type:", [
                'hostel_id' => $hostel->id,
                'available_rooms_count' => $hostel->rooms->count(),
                'available_room_counts' => $availableRoomCounts,
                'meal_menus_count' => $mealMenus->count(),
            ]);

            $galleries = Gallery::with(['hostel', 'room'])
                ->where('hostel_id', $hostel->id)
                ->where('is_active', true)
                ->where('media_type', 'photo')
                ->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other'])
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->filter(function ($gallery) use ($availableRoomCounts) {
                    return ($availableRoomCounts[$gallery->category] ?? 0) > 0;
                });

            \Log::info("Main gallery data:", [
                'hostel_id' => $hostel->id,
                'available_rooms_count' => $hostel->rooms->count(),
                'galleries_count' => $galleries->count(),
                'available_room_counts' => $availableRoomCounts,
                'meal_menus_count' => $mealMenus->count()
            ]);

            return view('public.hostels.gallery', compact(
                'hostel',
                'galleries',
                'availableRoomCounts',
                'mealMenus'
            ));
        } catch (\Exception $e) {
            \Log::error('Main gallery error: ' . $e->getMessage());
            abort(404, 'ग्यालरी लोड गर्न असफल।');
        }
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

            // ✅ FIXED: Better date handling from query parameters
            $checkIn = request('check_in');
            $checkOut = request('check_out');
            $datesLocked = !empty($checkIn) && !empty($checkOut);

            // ✅ FIXED: Validate dates if provided from search
            if ($datesLocked) {
                $today = now()->format('Y-m-d');
                // If check-in is today or in past, allow it but show warning
                if ($checkIn < $today) {
                    // Don't return error, just adjust to today
                    $checkIn = $today;
                }
                if ($checkOut && $checkOut <= $checkIn) {
                    // Don't return error, just clear checkout
                    $checkOut = null;
                }
            }

            // ✅ FIXED: Get available rooms for the hostel with date filtering
            $roomsQuery = Room::where('hostel_id', $hostel->id)
                ->where('status', 'available');

            // ✅ FIXED: Calculate actual available beds based on APPROVED bookings only
            $roomsQuery->withCount(['bookings as approved_bookings_count' => function ($query) {
                $query->where('status', Booking::STATUS_APPROVED); // ✅ ONLY count approved bookings
            }]);

            // If dates provided, filter rooms by availability for those dates
            if ($datesLocked && $checkIn) {
                $roomsQuery->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                    $q->where('status', Booking::STATUS_APPROVED) // ✅ ONLY approved bookings block availability
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
                // ✅ FIXED: CORRECT available beds calculation based on APPROVED bookings only
                $actualAvailableBeds = $room->capacity - $room->approved_bookings_count;

                // Simple mapping for room types to Nepali
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
                    'available_beds' => $actualAvailableBeds, // ✅ Use calculated available beds
                    'price' => $room->price,
                    'capacity' => $room->capacity
                ];
            })->filter(function ($room) {
                return $room['available_beds'] > 0; // ✅ Only show rooms with actual available beds
            });

            // ✅ FIXED: Check if room_id is provided in query (for gallery booking)
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
                    // ✅ FIXED: CORRECT available beds calculation for selected room
                    $actualAvailableBeds = $selectedRoom->capacity - $selectedRoom->approved_bookings_count;
                    $selectedRoom->available_beds = $actualAvailableBeds;

                    // Add nepali_type to selected room
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

            // Base query for available rooms
            $roomsQuery = $hostel->rooms()
                ->where('status', 'available');

            // ✅ FIXED: Calculate actual available beds based on APPROVED bookings only
            $roomsQuery->withCount(['bookings as approved_bookings_count' => function ($query) {
                $query->where('status', Booking::STATUS_APPROVED); // ✅ ONLY count approved bookings
            }]);

            // If dates provided, check room availability for those dates
            if ($checkIn && $checkOut) {
                $roomsQuery->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                    $q->where('status', Booking::STATUS_APPROVED) // ✅ ONLY approved bookings block availability
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
                // ✅ FIXED: CORRECT available beds calculation based on APPROVED bookings only
                $actualAvailableBeds = $room->capacity - $room->approved_bookings_count;

                // Simple mapping for room types to Nepali
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
                    'available_beds' => $actualAvailableBeds, // ✅ Use calculated available beds
                    'price' => $room->price,
                    'formatted_price' => 'रु ' . number_format($room->price),
                    'description' => $room->description
                ];
            })->filter(function ($room) {
                return $room['available_beds'] > 0; // ✅ Only return rooms with actual available beds
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
     * ✅ FIXED: Now creates Booking model instead of BookingRequest with proper hostel_id
     */
    public function storeBooking(Request $request, $slug)
    {
        try {
            $hostel = Hostel::where('slug', $slug)
                ->where('is_published', true)
                ->where('status', 'active')
                ->firstOrFail();

            // ✅ UPDATED: Remove emergency_contact from validation
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'phone' => 'required|string|max:15',
                'email' => 'nullable|email|max:100',
                'check_in_date' => 'required|date|after_or_equal:today', // ✅ CHANGED: after_or_equal
                'check_out_date' => 'required|date|after:check_in_date',
                'room_id' => 'required|exists:rooms,id',
                'message' => 'nullable|string|max:500'
                // ❌ REMOVED: emergency_contact
            ]);

            // Check if the room belongs to the hostel and is available
            $room = Room::where('id', $validated['room_id'])
                ->where('hostel_id', $hostel->id)
                ->where('status', 'available')
                ->withCount(['bookings as approved_bookings_count' => function ($query) {
                    $query->where('status', Booking::STATUS_APPROVED); // ✅ ONLY count approved bookings
                }])
                ->first();

            if (!$room) {
                return back()->withInput()->with('error', 'यो कोठा अहिले उपलब्ध छैन वा तपाईंले छान्नुभएको कोठा यस होस्टलमा छैन।');
            }

            // ✅ FIXED: Check actual available beds based on APPROVED bookings only
            $actualAvailableBeds = $room->capacity - $room->approved_bookings_count;
            if ($actualAvailableBeds <= 0) {
                return back()->withInput()->with('error', 'यो कोठा अहिले उपलब्ध छैन। कृपया अर्को कोठा छान्नुहोस्।');
            }

            // Check for booking conflicts for this room and dates - ONLY check approved bookings
            $conflictingBookings = Booking::where('room_id', $room->id)
                ->where('status', Booking::STATUS_APPROVED) // ✅ ONLY approved bookings block availability
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

            // ✅ CRITICAL FIX: Create Booking model instead of BookingRequest with proper hostel_id
            $booking = Booking::create([
                'hostel_id' => $hostel->id, // ✅ CRITICAL: Ensure hostel_id is saved
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

            // TODO: Send notification to hostel owner

            return redirect()->route('frontend.booking.success', $booking->id)
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

        // Simple mapping for room types to Nepali
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

        // Add computed properties for the view
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
     * This method handles both BookingRequest and Booking models
     */
    public function bookingSuccessNew($id)
    {
        try {
            // First try to find BookingRequest
            $bookingRequest = BookingRequest::with(['hostel', 'room'])->find($id);

            if ($bookingRequest) {
                // Simple mapping for room types to Nepali
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

                // Add computed properties for the view
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

            // If BookingRequest not found, try to find Booking model
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
}
