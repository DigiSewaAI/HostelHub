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
use App\Models\MealMenu; // ✅ NEW: MealMenu model import
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
                ->with(['students']) // Load students relationship
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
                return Hostel::distinct()->pluck('city');
            });

            // 4. Featured Hostels (with caching)
            $hostels = Cache::remember('home_hostels', 3600, function () {
                return Hostel::with('images')->take(4)->get();
            });

            // 5. Recent Testimonials (with caching)
            $testimonials = Cache::remember('home_testimonials', 3600, function () {
                return Review::with('student')
                    ->where('is_published', true)
                    ->take(3)
                    ->get();
            });

            // 6. Room Types (with caching) - UPDATED
            $roomTypes = Cache::remember('home_room_types', 3600, function () {
                return Room::distinct()->pluck('type');
            });

            // 7. Hero Slider Items (with caching) - ✅ UPDATED: Include hostel names WITH FALLBACK
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

                // 🚨 CRITICAL FIX: Add fallback if no items
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

            // 8. Gallery Items (with caching) - ✅ UPDATED: Include hostel names WITH FALLBACK
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

                // 🚨 CRITICAL FIX: Add fallback if no items
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

            // 9. Upcoming Meals (Today + next 2 days) - 🚨 TEMPORARILY COMMENTED OUT
            $meals = collect();
            // if (class_exists(Meal::class)) {
            //     try {
            //         $meals = Meal::whereDate('date', '>=', now())
            //             ->where('status', 'published')
            //             ->orderBy('date')
            //             ->limit(3)
            //             ->get();
            //     } catch (\Exception $e) {
            //         Log::warning('Meals status column not available: ' . $e->getMessage());
            //         $meals = Meal::whereDate('date', '>=', now())
            //             ->orderBy('date')
            //             ->limit(3)
            //             ->get();
            //     }
            // }

            // ✅ NEW: Featured Meal Menus for Homepage
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
                'featuredMealMenus' // ✅ NEW: Featured meal menus
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
                'featuredMealMenus' => collect() // ✅ NEW: Empty collection on error
            ]);
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
            'hostel_name' => $item->hostel_name, // ✅ ADDED: Hostel name
            'hostel_id' => $item->hostel_id,
            'is_room_image' => !is_null($item->room_id) // ✅ ADDED: Room image flag
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
     * Show only available rooms gallery - COMPLETELY FIXED COUNTS
     */
    public function hostelGallery($slug)
    {
        try {
            \Log::info("=== MAIN GALLERY DEBUG START ===", ['slug' => $slug]);

            // Get hostel with available rooms and their images only
            $hostel = Hostel::where('slug', $slug)
                ->with(['rooms' => function ($query) {
                    // ✅ UPDATED: Only rooms that have available beds
                    $query->where('available_beds', '>', 0)
                        ->where('status', '!=', 'मर्मत सम्भार');
                }])
                ->first();

            if (!$hostel) {
                abort(404, 'होस्टल फेला परेन।');
            }

            // ✅ UPDATED: Count available rooms by TYPE (not capacity)
            $availableRoomCounts = [
                '1 seater' => $hostel->rooms->where('type', '1 seater')->count(),
                '2 seater' => $hostel->rooms->where('type', '2 seater')->count(),
                '3 seater' => $hostel->rooms->where('type', '3 seater')->count(),
                '4 seater' => $hostel->rooms->where('type', '4 seater')->count(),
                'other' => $hostel->rooms->whereNotIn('type', ['1 seater', '2 seater', '3 seater', '4 seater'])->count(),
            ];

            // ✅ NEW: Get meal menus for this hostel
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
                'rooms_debug' => $hostel->rooms->map(function ($room) {
                    return [
                        'room_number' => $room->room_number,
                        'type' => $room->type,
                        'capacity' => $room->capacity,
                        'current_occupancy' => $room->current_occupancy,
                        'available_beds' => $room->available_beds,
                        'status' => $room->status
                    ];
                })
            ]);

            // ✅ UPDATED: Get galleries with hostel and room relationships
            $galleries = Gallery::with(['hostel', 'room'])
                ->where('hostel_id', $hostel->id)
                ->where('is_active', true)
                ->where('media_type', 'photo') // Only photos, no videos
                ->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other']) // Only room categories
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->filter(function ($gallery) use ($availableRoomCounts) {
                    // Only show galleries for room types that have available rooms
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
                'mealMenus' // ✅ NEW: Pass meal menus to view
            ));
        } catch (\Exception $e) {
            \Log::error('Main gallery error: ' . $e->getMessage());
            abort(404, 'ग्यालरी लोड गर्न असफल।');
        }
    }

    /**
     * Show full gallery with all images/videos - CLEANED & FIXED
     */
    public function hostelFullGallery($slug)
    {
        try {
            \Log::info("=== FULL GALLERY DEBUG START ===", ['slug' => $slug]);

            // ✅ UPDATED: Include room relationship for room galleries
            $hostel = Hostel::where('slug', $slug)
                ->with(['galleries' => function ($query) {
                    $query->where('is_active', true)
                        ->with('room') // ✅ ADDED: Load room relationship
                        ->orderBy('is_featured', 'desc')
                        ->orderBy('created_at', 'desc');
                }])
                ->first();

            if (!$hostel) {
                abort(404, 'होस्टल फेला परेन।');
            }

            // ✅ NEW: Get meal menus for this hostel
            $mealMenus = MealMenu::where('hostel_id', $hostel->id)
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->orderBy('meal_type')
                ->get();

            // Count items by category for full gallery
            $activeGalleries = $hostel->galleries->where('is_active', true);

            $categoryCounts = [
                'rooms' => $activeGalleries->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other'])->count(),
                'kitchen' => $activeGalleries->where('category', 'kitchen')->count(),
                'facilities' => $activeGalleries->whereIn('category', ['bathroom', 'common', 'living room', 'study room'])->count(),
                'video' => $activeGalleries->whereIn('media_type', ['local_video', 'external_video'])->count(),
                'meals' => $mealMenus->count() // ✅ NEW: Meal menus count
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
                'mealMenus' // ✅ NEW: Pass meal menus to view
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

            // ✅ UPDATED: Include room relationship and use hostel_name
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
                        'hostel_name' => $hostel->name, // ✅ ADDED: Hostel name
                        'room_number' => $item->room ? $item->room->room_number : null,
                        'is_room_image' => !is_null($item->room_id) // ✅ ADDED: Room image flag
                    ];
                });

            return response()->json($galleries);
        } catch (\Exception $e) {
            \Log::error('Hostel gallery API error: ' . $e->getMessage());
            return response()->json(['error' => 'ग्यालरी डाटा लोड गर्न असफल'], 500);
        }
    }

    /**
     * Handle search request
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

        return view('frontend.search-results', compact('rooms'));
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

        // Check if student already has a hostel
        if ($user->student->hostel_id) {
            return back()->with('error', 'You are already assigned to a hostel.');
        }

        // Assign student to hostel
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

        // Store newsletter subscription
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

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by city
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

        // Get reviews for the hostel
        $reviews = $hostel->approvedReviews()->with('student.user')->paginate(10);

        // Calculate review statistics
        $reviewCount = $hostel->approvedReviews()->count();
        $avgRating = $hostel->approvedReviews()->avg('rating') ?? 0;

        // Calculate student count
        $studentCount = $hostel->students()->where('status', 'active')->count();

        // ✅ FIXED: Proper logo URL normalization
        $logo = $this->normalizeLogoUrl($hostel->logo_path);

        // ✅ FIXED: Proper facilities parsing
        $facilities = $this->parseFacilities($hostel->facilities);

        // ✅ NEW: Get room galleries for this hostel
        $roomGalleries = $hostel->publicRoomGalleries;

        // ✅ NEW: Get meal menus for this hostel
        $mealMenus = MealMenu::where('hostel_id', $hostel->id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('meal_type')
            ->get();

        // Log for debugging
        \Log::info("Hostel Show - Slug: {$slug}", [
            'logo_path' => $hostel->logo_path,
            'logo_normalized' => $logo,
            'facilities_raw' => $hostel->facilities,
            'facilities_parsed' => $facilities,
            'room_galleries_count' => $roomGalleries->count(),
            'meal_menus_count' => $mealMenus->count()
        ]);

        // Determine theme (default = modern)
        $theme = $hostel->theme ?? 'modern';

        // Construct view path dynamically
        $viewPath = "public.hostels.themes.$theme";

        // Fallback if chosen theme file doesn't exist
        if (!view()->exists($viewPath)) {
            $viewPath = 'public.hostels.themes.modern';
        }

        // Render themed view with all necessary data
        return view($viewPath, compact(
            'hostel',
            'reviews',
            'reviewCount',
            'avgRating',
            'studentCount',
            'logo',
            'facilities',
            'roomGalleries', // ✅ ADDED: Room galleries for display
            'mealMenus' // ✅ NEW: Meal menus for display
        ));
    }

    /**
     * ✅ FIXED: Better logo URL normalization
     */
    private function normalizeLogoUrl($logoPath)
    {
        if (empty($logoPath)) {
            return null;
        }

        // If it's already a full URL, use it as is
        if (filter_var($logoPath, FILTER_VALIDATE_URL)) {
            return $logoPath;
        }

        // If it starts with http but might not validate as URL
        if (str_starts_with($logoPath, 'http')) {
            return $logoPath;
        }

        // Clean the path
        $cleanPath = ltrim($logoPath, '/');

        // Check if file exists in storage
        if (\Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }

        // Fallback: try the original path
        return asset('storage/' . $cleanPath);
    }

    /**
     * ✅ FIXED: Better facilities parsing with Unicode handling
     */
    private function parseFacilities($facilitiesData)
    {
        if (empty($facilitiesData)) {
            return [];
        }

        // If it's already an array, return as is
        if (is_array($facilitiesData)) {
            return array_values(array_filter(array_map('trim', $facilitiesData)));
        }

        // If it's a string, try to parse it
        if (is_string($facilitiesData)) {
            // Try JSON decode first
            $decoded = json_decode($facilitiesData, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // JSON decoded successfully
                $flattened = [];
                array_walk_recursive($decoded, function ($item) use (&$flattened) {
                    if (is_string($item) && !empty(trim($item))) {
                        $trimmed = trim($item);
                        // Clean and decode Unicode
                        $cleaned = $this->cleanAndDecodeString($trimmed);
                        if (!empty($cleaned)) {
                            $flattened[] = $cleaned;
                        }
                    }
                });
                return array_values(array_unique(array_filter($flattened)));
            } else {
                // If not JSON, try different separators
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
     * ✅ NEW: Clean and decode string with Unicode support
     */
    private function cleanAndDecodeString($string)
    {
        $trimmed = trim($string);
        $trimmed = trim($trimmed, ' ,"\'[]{}');

        if (empty($trimmed) || $trimmed === '""' || $trimmed === "''") {
            return null;
        }

        // Handle Unicode escape sequences
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
        // For owner preview - allow viewing even if not published
        $hostel = Hostel::with([
            'images',
            'rooms',
            'approvedReviews.student.user',
            'mealMenus'
        ])->where('slug', $slug)->firstOrFail();

        $user = auth()->user();

        // Allow preview for authenticated users with proper roles
        if ($user) {
            // Admin can preview any hostel
            if ($user->hasRole('admin')) {
                return $this->renderHostelPreview($hostel);
            }

            // For owner and hostel_manager, check if the hostel belongs to their current organization
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
        // Get reviews for the hostel
        $reviews = $hostel->approvedReviews()->with('student.user')->paginate(10);

        // Calculate review statistics
        $reviewCount = $hostel->approvedReviews()->count();
        $avgRating = $hostel->approvedReviews()->avg('rating') ?? 0;

        // Calculate student count
        $studentCount = $hostel->students()->where('status', 'active')->count();

        // ✅ NORMALIZED: Use the same logo and facilities normalization
        $logo = $this->normalizeLogoUrl($hostel->logo);
        $facilities = $this->parseFacilities($hostel->facilities);

        // ✅ NEW: Get room galleries for this hostel
        $roomGalleries = $hostel->publicRoomGalleries;

        // ✅ NEW: Get meal menus for this hostel
        $mealMenus = MealMenu::where('hostel_id', $hostel->id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('meal_type')
            ->get();

        // Determine theme (default = modern)
        $theme = $hostel->theme ?? 'modern';

        // Construct view path dynamically
        $viewPath = "public.hostels.themes.$theme";

        // Fallback if chosen theme file doesn't exist
        if (!view()->exists($viewPath)) {
            $viewPath = "public.hostels.themes.modern";
        }

        // Render themed view with preview flag
        return view($viewPath, compact(
            'hostel',
            'reviews',
            'reviewCount',
            'avgRating',
            'studentCount',
            'logo',
            'facilities',
            'roomGalleries', // ✅ ADDED: Room galleries for display
            'mealMenus' // ✅ NEW: Meal menus for display
        ))->with('preview', true);
    }

    // ✅ NEW: Gallery Integration Methods

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
            'hostel_name' => $item->hostel_name, // ✅ ADDED: Hostel name
            'hostel_id' => $item->hostel_id,
            'room_number' => $item->room ? $item->room->room_number : null,
            'is_room_image' => !is_null($item->room_id) // ✅ ADDED: Room image flag
        ];
    }

    /**
     * ✅ NEW: Get available hostels for search
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
     * ✅ NEW: Get room availability for specific hostel
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
     * ✅ NEW: Quick search functionality
     */
    public function quickSearch(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        try {
            $query = $request->get('query');

            // Search hostels
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

            // Search available rooms
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

    // ✅ NEW: Meal Gallery Methods

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
}
