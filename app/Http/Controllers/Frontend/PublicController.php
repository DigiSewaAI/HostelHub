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
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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

            // 6. Room Types (with caching)
            $roomTypes = Cache::remember('home_room_types', 3600, function () {
                return Room::distinct()->pluck('type');
            });

            // 7. Hero Slider Items (with caching)
            $heroSliderItems = Cache::remember('home_hero_slider_items', 3600, function () {
                return Gallery::where('is_active', true)
                    ->whereNotNull('file_path')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get()
                    ->map(function ($item) {
                        return $this->processGalleryItemForHome($item);
                    });
            });

            // 8. Gallery Items (with caching)
            $galleryItems = Cache::remember('home_gallery_items', 3600, function () {
                return Gallery::where('is_active', true)
                    ->whereNotNull('file_path')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get()
                    ->map(function ($item) {
                        return $this->processGalleryItemForHome($item);
                    });
            });

            // 9. Upcoming Meals (Today + next 2 days) - ✅ FIXED: Status condition
            $meals = collect();
            if (class_exists(Meal::class)) {
                try {
                    // Try with status condition first
                    $meals = Meal::whereDate('date', '>=', now())
                        ->where('status', 'published') // Use the column you added
                        ->orderBy('date')
                        ->limit(3)
                        ->get();
                } catch (\Exception $e) {
                    // If status column doesn't exist yet, use without status condition
                    Log::warning('Meals status column not available, fetching all meals: ' . $e->getMessage());
                    $meals = Meal::whereDate('date', '>=', now())
                        ->orderBy('date')
                        ->limit(3)
                        ->get();
                }
            }

            return view('frontend.home', compact(
                'featuredRooms',
                'metrics',
                'cities',
                'hostels',
                'testimonials',
                'roomTypes',
                'heroSliderItems',
                'galleryItems',
                'meals'
            ));
        } catch (\Exception $e) {
            Log::error('PublicController@home error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

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
                'heroSliderItems' => collect(),
                'galleryItems' => collect(),
                'meals' => collect()
            ]);
        }
    }

    /**
     * Process gallery item for home page
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
     * Process gallery item for frontend display
     */
    private function processGalleryItem($item): array
    {
        $processed = [
            'id' => $item->id,
            'title' => $item->title ?? 'Untitled',
            'media_type' => $item->media_type ?? 'image',
            'file_path' => $item->file_path,
            'thumbnail_url' => null,
            'media_url' => null
        ];

        try {
            if ($item->file_path) {
                if (in_array($item->media_type, ['image', 'photo'])) {
                    $url = $this->imageService->getUrl($item->file_path);
                    $processed['media_url'] = $url;
                    $processed['thumbnail_url'] = $url;
                } elseif (in_array($item->media_type, ['video', 'local_video'])) {
                    $url = $this->imageService->getUrl($item->file_path);
                    $processed['media_url'] = $url;
                    $processed['thumbnail_url'] = asset('images/default-video-thumbnail.jpg');

                    if (
                        strpos($item->file_path, 'youtube.com') !== false ||
                        strpos($item->file_path, 'youtu.be') !== false
                    ) {
                        $youtubeThumbnail = $this->getYoutubeThumbnail($item->file_path);
                        if ($youtubeThumbnail) {
                            $processed['thumbnail_url'] = $youtubeThumbnail;
                        }
                    }
                } else {
                    $url = $this->imageService->getUrl(null);
                    $processed['thumbnail_url'] = $processed['media_url'] = $url;
                }
            } else {
                $url = $this->imageService->getUrl(null);
                $processed['thumbnail_url'] = $processed['media_url'] = $url;
            }
        } catch (\Exception $e) {
            Log::error('Error processing gallery item ' . $item->id . ': ' . $e->getMessage());
            $url = $this->imageService->getUrl(null);
            $processed['thumbnail_url'] = $processed['media_url'] = $url;
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
     * Generate YouTube thumbnail URL
     */
    private function getYoutubeThumbnail(string $url): ?string
    {
        $id = $this->getYoutubeIdFromUrl($url);
        if (!$id) {
            return null;
        }

        return "https://img.youtube.com/vi/{$id}/maxresdefault.jpg";
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

    /**
     * ✅ FIXED: Display hostel gallery - USING CORRECT COLUMN NAME 'is_active'
     */
    public function hostelGallery($slug)
    {
        try {
            \Log::info("Attempting to access hostel gallery", ['slug' => $slug]);

            // First, try to find published hostel
            $hostel = Hostel::where('slug', $slug)
                ->where('is_published', true)
                ->with(['galleries' => function ($query) {
                    // ✅ FIXED: Use correct column name 'is_active'
                    $query->where('is_active', true)->latest();
                }])
                ->first();

            // If not found, try without published check for preview/owner access
            if (!$hostel) {
                $hostel = Hostel::where('slug', $slug)
                    ->with(['galleries' => function ($query) {
                        // ✅ FIXED: Use correct column name 'is_active'
                        $query->where('is_active', true)->latest();
                    }])
                    ->first();

                if (!$hostel) {
                    \Log::error("Hostel not found with slug: {$slug}");
                    abort(404, 'होस्टल फेला परेन।');
                }

                // Check if user has permission to view unpublished hostel
                $user = auth()->user();
                $hasAccess = false;

                if ($user) {
                    // Admin can view any hostel
                    if ($user->hasRole('admin')) {
                        $hasAccess = true;
                    }
                    // Owner/hostel_manager can view their own hostels
                    elseif ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
                        $userOrganizationId = session('current_organization_id');
                        if ($hostel->organization_id == $userOrganizationId) {
                            $hasAccess = true;
                        }
                    }
                }

                if (!$hasAccess) {
                    \Log::warning("Unauthorized access attempt to unpublished hostel: {$slug}");
                    abort(404, 'होस्टल फेला परेन वा तपाईंसँग यसलाई हेर्ने अनुमति छैन।');
                }
            }

            // Get gallery items for this specific hostel
            $galleries = $hostel->galleries;

            // Get categories with correct column name
            $categories = $hostel->galleries()
                ->where('is_active', true)
                ->select('category')
                ->distinct()
                ->pluck('category');

            \Log::info("Hostel gallery loaded successfully", [
                'hostel_id' => $hostel->id,
                'hostel_slug' => $hostel->slug,
                'gallery_count' => $galleries->count(),
                'categories_count' => $categories->count(),
                'is_published' => $hostel->is_published
            ]);

            // ✅ FIXED: Use the correct view path - public.hostels.gallery
            return view('public.hostels.gallery', compact('hostel', 'galleries', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Hostel gallery error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            abort(404, 'ग्यालरी लोड गर्न असफल।');
        }
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

    /**
     * Display full reviews list (for admin/owner reference)
     */
    public function reviews(): View
    {
        $reviews = Review::with('student')->latest()->paginate(6);
        return view('frontend.reviews', compact('reviews'));
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

    public function cookies(): View
    {
        return view('frontend.legal.cookies');
    }

    public function demo(): View
    {
        return view('frontend.pages.demo');
    }

    // SEO routes
    public function sitemap()
    {
        try {
            $content = view('frontend.seo.sitemap')->render();
            return Response::make($content, 200, [
                'Content-Type' => 'application/xml'
            ]);
        } catch (\Exception $e) {
            Log::error('Sitemap generation failed: ' . $e->getMessage());
            abort(500);
        }
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

    public function robots()
    {
        try {
            $content = view('frontend.seo.robots')->render();
            return Response::make($content, 200, [
                'Content-Type' => 'text/plain'
            ]);
        } catch (\Exception $e) {
            Log::error('Robots.txt generation failed: ' . $e->getMessage());
            abort(500);
        }
    }

    /**
     * Display hostels index page with search and filtering
     */
    public function hostelsIndex(Request $request)
    {
        // ✅ FIXED: Use proper published scope instead of temporary fix
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
     * Display single hostel page
     */
    public function hostelShow($slug)
    {
        // ✅ FIXED: Use proper published scope instead of temporary fix
        $hostel = Hostel::where('is_published', true)->where('slug', $slug)->firstOrFail();
        $reviews = $hostel->approvedReviews()->with('student.user')->paginate(10);

        return view('frontend.hostels.show', compact('hostel', 'reviews'));
    }

    /**
     * ✅ FIXED: Preview hostel for owners/admins - Fixed 403 error
     */
    public function hostelPreview($slug)
    {
        // For owner preview - allow viewing even if not published
        $hostel = Hostel::with([
            'images',
            'rooms',
            'approvedReviews.student.user',
            'mealMenus' // ✅ Ensure this is included
        ])->where('slug', $slug)->firstOrFail();

        $user = auth()->user();

        // ✅ FIXED: Allow preview for authenticated users with proper roles
        if ($user) {
            // Admin can preview any hostel
            if ($user->hasRole('admin')) {
                $reviews = $hostel->approvedReviews()->with('student.user')->paginate(10);
                return view('frontend.hostels.show', compact('hostel', 'reviews'))->with('preview', true);
            }

            // For owner and hostel_manager, check if the hostel belongs to their current organization
            if ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
                $userOrganizationId = session('current_organization_id');

                if ($hostel->organization_id == $userOrganizationId) {
                    $reviews = $hostel->approvedReviews()->with('student.user')->paginate(10);
                    return view('frontend.hostels.show', compact('hostel', 'reviews'))->with('preview', true);
                }
            }
        }

        // ✅ FIXED: Instead of 403, show a more user-friendly error
        abort(404, 'होस्टल फेला परेन वा तपाईंसँग यसलाई हेर्ने अनुमति छैन।');
    }
}
