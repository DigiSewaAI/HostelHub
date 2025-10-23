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

            // 6. Room Types (with caching) - UPDATED
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

            // 9. Upcoming Meals (Today + next 2 days)
            $meals = collect();
            if (class_exists(Meal::class)) {
                try {
                    $meals = Meal::whereDate('date', '>=', now())
                        ->where('status', 'published')
                        ->orderBy('date')
                        ->limit(3)
                        ->get();
                } catch (\Exception $e) {
                    Log::warning('Meals status column not available: ' . $e->getMessage());
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
     * Show only available rooms gallery - CLEANED & FIXED
     */
    public function hostelGallery($slug)
    {
        try {
            \Log::info("=== MAIN GALLERY DEBUG START ===", ['slug' => $slug]);

            // Get hostel with available rooms and their images only
            $hostel = Hostel::where('slug', $slug)
                ->with(['rooms' => function ($query) {
                    $query->where('status', 'available');
                }])
                ->first();

            if (!$hostel) {
                abort(404, 'होस्टल फेला परेन।');
            }

            // Get available room counts by type
            $availableRoomCounts = [
                '1 seater' => $hostel->rooms->where('type', '1 seater')->count(),
                '2 seater' => $hostel->rooms->where('type', '2 seater')->count(),
                '3 seater' => $hostel->rooms->where('type', '3 seater')->count(),
                '4 seater' => $hostel->rooms->where('type', '4 seater')->count(),
                'other' => $hostel->rooms->where('type', 'other')->count(),
            ];

            // Get only room images (no videos, no facilities)
            $galleries = Gallery::where('hostel_id', $hostel->id)
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
                'available_room_counts' => $availableRoomCounts
            ]);

            return view('public.hostels.gallery', compact(
                'hostel',
                'galleries',
                'availableRoomCounts'
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

            $hostel = Hostel::where('slug', $slug)
                ->with(['galleries' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('is_featured', 'desc')
                        ->orderBy('created_at', 'desc');
                }])
                ->first();

            if (!$hostel) {
                abort(404, 'होस्टल फेला परेन।');
            }

            // Count items by category for full gallery
            $activeGalleries = $hostel->galleries->where('is_active', true);

            $categoryCounts = [
                'rooms' => $activeGalleries->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other'])->count(),
                'kitchen' => $activeGalleries->where('category', 'kitchen')->count(),
                'facilities' => $activeGalleries->whereIn('category', ['bathroom', 'common', 'living room', 'study room'])->count(),
                'video' => $activeGalleries->whereIn('media_type', ['local_video', 'external_video'])->count()
            ];

            \Log::info("Full gallery data:", [
                'hostel_id' => $hostel->id,
                'total_galleries' => $hostel->galleries->count(),
                'category_counts' => $categoryCounts
            ]);

            return view('public.hostels.full-gallery', compact(
                'hostel',
                'categoryCounts'
            ));
        } catch (\Exception $e) {
            \Log::error('Full gallery error: ' . $e->getMessage());
            abort(404, 'पूर्ण ग्यालरी लोड गर्न असफल।');
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
     * Display single hostel page
     */
    public function hostelShow($slug)
    {
        $hostel = Hostel::where('is_published', true)->where('slug', $slug)->firstOrFail();
        $reviews = $hostel->approvedReviews()->with('student.user')->paginate(10);

        return view('frontend.hostels.show', compact('hostel', 'reviews'));
    }

    /**
     * Preview hostel for owners/admins
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

        abort(404, 'होस्टल फेला परेन वा तपाईंसँग यसलाई हेर्ने अनुमति छैन।');
    }
}
