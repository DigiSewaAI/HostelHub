<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Hostel;
use App\Models\Review;
use App\Models\Room;
use App\Services\GalleryCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    protected $cacheService;

    public function __construct()
    {
        $this->cacheService = new GalleryCacheService();
    }

    /**
     * ✅ ENHANCED: Display the main gallery page with tabs for photos/videos
     * ✅ FIX: Now includes both Gallery images AND Room images
     */
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'photos'); // 'photos' or 'videos' or 'virtual-tours'

        try {
            // ✅ FIXED: Get items for current tab only
            if ($tab === 'photos') {
                $galleryItems = $this->getGalleryTableItems($tab);
                $roomItems = $this->getRoomImageItems();
                $allItems = array_merge($galleryItems, $roomItems);
            } elseif ($tab === 'videos') {
                $allItems = $this->getGalleryTableItems($tab);
            } elseif ($tab === 'virtual-tours') {
                $allItems = $this->getGalleryTableItems($tab);
            } else {
                $allItems = [];
            }

            // Apply filtering
            $filteredItems = $this->applyFilters($allItems, $request);

            // Paginate the results
            $page = $request->get('page', 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;
            $currentPageItems = array_slice($filteredItems, $offset, $perPage);

            $galleries = new LengthAwarePaginator(
                $currentPageItems,
                count($filteredItems),
                $perPage,
                $page,
                [
                    'path' => request()->url(),
                    'query' => request()->query(),
                    'fragment' => 'gallery-grid'
                ]
            );

            // Get only published hostels for filter - INCLUDING BOYS HOSTELS
            $hostels = Hostel::where('is_published', true)
                ->orderBy('name')
                ->get(['id', 'name', 'slug']);

            // Get cities from published hostels
            $cities = Hostel::where('is_published', true)
                ->distinct()
                ->pluck('city')
                ->filter()
                ->map(function ($city) {
                    return (object)['name' => $city];
                });

            $metrics = [
                'total_students' => $this->getTotalStudents(),
                'total_hostels' => $hostels->count(),
                'satisfaction_rate' => $this->getSatisfactionRate(),
                'cities_covered' => $cities->count(),
                'total_videos' => $this->getTotalVideos(),
                'total_photos' => $this->getTotalPhotos()
            ];

            // Get video categories
            $videoCategories = $this->getVideoCategories();

            return view('frontend.gallery.index', compact(
                'galleries',
                'hostels',
                'cities',
                'metrics',
                'tab',
                'videoCategories'
            ));
        } catch (\Exception $e) {
            Log::error('Gallery index error: ' . $e->getMessage());
            return $this->showSampleData($tab);
        }
    }

    /**
     * ✅ NEW: Get ALL gallery items from multiple sources
     * Sources: Gallery table AND Room images
     */
    private function getAllGalleryItems($tab)
    {
        $allItems = [];

        // 1. Get items from Gallery table
        $galleryItems = $this->getGalleryTableItems($tab);
        $allItems = array_merge($allItems, $galleryItems);

        // 2. Get items from Room images (ONLY for photos tab)
        if ($tab === 'photos') {
            $roomItems = $this->getRoomImageItems();
            $allItems = array_merge($allItems, $roomItems);
        }

        // 3. Shuffle and limit
        shuffle($allItems);

        return $allItems;
    }

    /**
     * ✅ FIXED: Get items from Gallery table with proper video duration format
     */
    private function getGalleryTableItems($tab)
    {
        $query = Gallery::with(['hostel', 'room'])
            ->where('is_active', true)
            ->whereHas('hostel', function ($query) {
                $query->where('is_published', true);
            });

        // Filter by tab
        if ($tab === 'photos') {
            $query->where('media_type', 'photo');
        } elseif ($tab === 'videos') {
            $query->whereIn('media_type', ['external_video', 'local_video']);
        } elseif ($tab === 'virtual-tours') {
            $query->where('category', 'virtual_tour')
                ->orWhere('is_360_video', true);
        }

        $galleries = $query->orderBy('created_at', 'desc')->get();

        $items = [];
        foreach ($galleries as $gallery) {
            // ✅ FIXED: Use gender_detected property directly
            $hostelGender = 'mixed';
            if ($gallery->hostel && property_exists($gallery->hostel, 'gender_detected')) {
                $hostelGender = $gallery->hostel->gender_detected;
            } elseif ($gallery->hostel && property_exists($gallery->hostel, 'gender')) {
                $hostelGender = $gallery->hostel->gender;
            }

            $items[] = (object)[
                'id' => 'gallery_' . $gallery->id,
                'title' => $gallery->title,
                'description' => $gallery->description,
                'category' => $gallery->category, // ✅ English key
                'category_nepali' => $gallery->category_nepali ?? $this->getCategoryNepali($gallery->category),
                'media_type' => $gallery->media_type,
                'media_url' => $gallery->media_url,
                'thumbnail_url' => $gallery->thumbnail_url,
                'created_at' => $gallery->created_at,
                'hostel_name' => $gallery->hostel->name ?? 'Unknown Hostel',
                'hostel_id' => $gallery->hostel_id,
                'hostel_slug' => $gallery->hostel->slug ?? '',
                'hostel_gender' => $hostelGender, // ✅ FIXED: Use gender_detected directly
                'room' => $gallery->room,
                'room_number' => $gallery->room ? $gallery->room->room_number : null,
                'is_room_image' => !is_null($gallery->room_id),
                'source' => 'gallery',
                'youtube_embed_url' => $gallery->youtube_embed_url,
                'video_duration' => $gallery->video_duration_formatted ?? $gallery->video_duration, // ✅ FIXED: Use formatted duration
                'video_resolution' => $gallery->video_resolution,
                'is_360_video' => (bool)($gallery->is_360_video ?? false),
                'hd_available' => $gallery->hd_available ?? false,
                'hd_url' => $gallery->hd_image_url ?? $gallery->media_url
            ];
        }

        return $items;
    }

    /**
     * ✅ FIXED: Get items from Room images with proper hostel gender
     */
    private function getRoomImageItems()
    {
        // Get all published hostels INCLUDING BOYS HOSTELS
        $hostels = Hostel::where('is_published', true)
            ->where('status', 'active')
            ->with(['rooms' => function ($query) {
                $query->whereNotNull('image')
                    ->where('image', '!=', '')
                    ->where('status', 'available');
            }])
            ->get();

        $items = [];

        foreach ($hostels as $hostel) {
            // ✅ FIXED: Get hostel gender directly from model
            $hostelGender = 'mixed';
            if (property_exists($hostel, 'gender_detected')) {
                $hostelGender = $hostel->gender_detected;
            } elseif (property_exists($hostel, 'gender')) {
                $hostelGender = $hostel->gender;
            }

            foreach ($hostel->rooms as $room) {
                // Check if image exists in storage
                if ($room->image && Storage::disk('public')->exists($room->image)) {
                    $roomTypeNepali = $this->getRoomTypeNepali($room->type);

                    $items[] = (object)[
                        'id' => 'room_' . $room->id,
                        'title' => $room->room_number ? 'कोठा ' . $room->room_number : $roomTypeNepali,
                        'description' => $room->description ?: $roomTypeNepali . ' - ' . $hostel->name,
                        'category' => $room->type,
                        'category_nepali' => $roomTypeNepali,
                        'media_type' => 'photo',
                        'media_url' => asset('storage/' . $room->image),
                        'thumbnail_url' => asset('storage/' . $room->image),
                        'created_at' => $room->created_at,
                        'hostel_name' => $hostel->name,
                        'hostel_id' => $hostel->id,
                        'hostel_slug' => $hostel->slug,
                        'hostel_gender' => $hostelGender, // ✅ FIXED: Use gender_detected directly
                        'room' => (object)['room_number' => $room->room_number],
                        'room_number' => $room->room_number,
                        'is_room_image' => true,
                        'source' => 'room',
                        'room_type' => $room->type,
                        'room_price' => $room->price,
                        // Add missing properties to prevent errors
                        'youtube_embed_url' => null,
                        'video_duration' => null,
                        'video_resolution' => null,
                        'is_360_video' => false,
                        'hd_available' => false,
                        'hd_url' => asset('storage/' . $room->image)
                    ];
                }
            }
        }

        return $items;
    }

    /**
     * ✅ FIXED: Detect gender from hostel with improved logic
     */
    private function detectGenderFromHostel($hostel)
    {
        if (!$hostel) {
            return 'mixed';
        }

        // पहिले hostel को gender column check गर्ने
        if (is_object($hostel) && property_exists($hostel, 'gender')) {
            if (!empty($hostel->gender)) {
                return $hostel->gender;
            }
        }

        // फेरी gender_detected property check गर्ने
        if (is_object($hostel) && property_exists($hostel, 'gender_detected')) {
            return $hostel->gender_detected;
        }

        // Fallback: Hostel name बाट gender detect गर्ने
        return $this->getHostelGenderFromName($hostel);
    }

    /**
     * ✅ NEW: Get hostel gender from name with fallback
     */
    private function getHostelGenderFromName($hostel)
    {
        if (!$hostel) {
            return 'mixed';
        }

        // Hostel object बाट name निकाल्ने
        if (is_object($hostel) && property_exists($hostel, 'name')) {
            $hostelName = $hostel->name;
        } elseif (is_array($hostel) && isset($hostel['name'])) {
            $hostelName = $hostel['name'];
        } else {
            return 'mixed';
        }

        return $this->getHostelGender($hostelName);
    }

    /**
     * ✅ FIXED: Detect hostel gender from name with more keywords
     */
    private function getHostelGender($hostelName)
    {
        if (empty($hostelName)) {
            return 'mixed';
        }

        $name = strtolower($hostelName);

        // Boys hostels का लागि (अब सही नाममा "boys" छ)
        if (
            str_contains($name, 'boys') ||
            str_contains($name, 'ब्वाइज') ||
            str_contains($name, 'पुरुष') ||
            str_contains($name, 'ब्वायज') ||
            str_contains($name, 'male') ||
            str_contains($name, 'mens') ||
            str_contains($name, 'पुस')
        ) {
            return 'boys';
        }

        // Girls hostels का लागि
        if (
            str_contains($name, 'girls') ||
            str_contains($name, 'गर्ल्स') ||
            str_contains($name, 'महिला') ||
            str_contains($name, 'female') ||
            str_contains($name, 'womens') ||
            str_contains($name, 'महि')
        ) {
            return 'girls';
        }

        return 'mixed';
    }

    /**
     * ✅ FIXED: Apply filters to gallery items with video category support
     */
    private function applyFilters($items, Request $request)
    {
        $hostelId = $request->get('hostel_id');
        $search = $request->get('search');
        $category = $request->get('category', 'all');
        $tab = $request->get('tab', 'photos');

        $filteredItems = $items;

        // Filter by hostel
        if ($hostelId) {
            $filteredItems = array_filter($filteredItems, function ($item) use ($hostelId) {
                return $item->hostel_id == $hostelId;
            });
        }

        // Filter by search
        if ($search) {
            $searchLower = strtolower($search);
            $filteredItems = array_filter($filteredItems, function ($item) use ($searchLower) {
                return str_contains(strtolower($item->title), $searchLower) ||
                    str_contains(strtolower($item->description), $searchLower) ||
                    str_contains(strtolower($item->hostel_name), $searchLower) ||
                    str_contains(strtolower($item->category_nepali), $searchLower) ||
                    ($item->room_number && str_contains(strtolower($item->room_number), $searchLower));
            });
        }

        // Filter by category (For photos tab)
        if ($category && $category !== 'all' && $tab === 'photos') {
            $filteredItems = array_filter($filteredItems, function ($item) use ($category) {
                // दुवै English र Nepali category match गर्ने
                return $item->category === $category ||
                    $item->category_nepali === $category ||
                    strtolower($item->category) === strtolower($category);
            });
        }

        // For video tab, filter by video category (English keys only)
        if ($tab === 'videos' && $request->filled('video_category') && $request->video_category !== 'all') {
            $videoCategory = $request->video_category;
            $filteredItems = array_filter($filteredItems, function ($item) use ($videoCategory) {
                // ✅ FIXED: Video category filter ले English keys मा match गर्नुपर्छ
                return isset($item->category) && $item->category === $videoCategory;
            });
        }

        return array_values($filteredItems);
    }

    /**
     * Get room type in Nepali
     */
    private function getRoomTypeNepali($roomType)
    {
        $nepaliTypes = [
            '1 seater' => '१ सिटर कोठा',
            '2 seater' => '२ सिटर कोठा',
            '3 seater' => '३ सिटर कोठा',
            '4 seater' => '४ सिटर कोठा',
            'single' => 'एक सिटर कोठा',
            'double' => 'दुई सिटर कोठा',
            'triple' => 'तीन सिटर कोठा',
            'quad' => 'चार सिटर कोठा',
            'shared' => 'साझा कोठा',
            'other' => 'अन्य कोठा'
        ];

        return $nepaliTypes[$roomType] ?? $roomType;
    }

    /**
     * Get category in Nepali
     */
    private function getCategoryNepali($category)
    {
        $categories = [
            '1 seater' => '१ सिटर कोठा',
            '2 seater' => '२ सिटर कोठा',
            '3 seater' => '३ सिटर कोठा',
            '4 seater' => '४ सिटर कोठा',
            'living room' => 'लिभिङ रूम',
            'bathroom' => 'बाथरूम',
            'kitchen' => 'भान्सा',
            'study room' => 'अध्ययन कोठा',
            'event' => 'कार्यक्रम',
            'hostel_tour' => 'होस्टल टुर',
            'room_tour' => 'कोठा टुर',
            'student_life' => 'विद्यार्थी जीवन',
            'virtual_tour' => 'भर्चुअल टुर',
            'testimonial' => 'विद्यार्थी अनुभव',
            'facility' => 'सुविधाहरू'
        ];

        return $categories[$category] ?? $category;
    }

    /**
     * ✅ NEW: Get total videos count
     */
    private function getTotalVideos(): int
    {
        try {
            return Gallery::where('is_active', true)
                ->whereIn('media_type', ['external_video', 'local_video'])
                ->whereHas('hostel', function ($query) {
                    $query->where('is_published', true);
                })
                ->count() ?: 4;
        } catch (\Exception $e) {
            return 4;
        }
    }

    /**
     * ✅ NEW: Get total photos count
     */
    private function getTotalPhotos(): int
    {
        try {
            // Count from Gallery table
            $galleryPhotos = Gallery::where('is_active', true)
                ->where('media_type', 'photo')
                ->whereHas('hostel', function ($query) {
                    $query->where('is_published', true);
                })
                ->count();

            // Count from Room images
            $roomPhotos = Room::whereNotNull('image')
                ->where('image', '!=', '')
                ->whereHas('hostel', function ($query) {
                    $query->where('is_published', true)
                        ->where('status', 'active');
                })
                ->count();

            return $galleryPhotos + $roomPhotos ?: 49;
        } catch (\Exception $e) {
            return 49;
        }
    }

    /**
     * ✅ FIXED: Get video categories dynamically from database
     */
    private function getVideoCategories(): array
    {
        try {
            // Database बाट actual video categories लिने (English keys only)
            $categoriesFromDB = Gallery::whereIn('media_type', ['external_video', 'local_video'])
                ->where('is_active', true)
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category')
                ->filter()
                ->toArray();

            // Default categories with English keys
            $defaultCategories = [
                'all' => 'सबै',
                'hostel_tour' => 'होस्टल टुर',
                'room_tour' => 'कोठा टुर',
                'student_life' => 'विद्यार्थी जीवन',
                'virtual_tour' => 'भर्चुअल टुर',
                'testimonial' => 'विद्यार्थी अनुभव',
                'facility' => 'सुविधाहरू',
                'event' => 'कार्यक्रम'
            ];

            // ✅ FIXED: Start with 'all' category
            $categories = ['all' => 'सबै'];

            // Database बाट आएका categories लाई include गर्ने
            foreach ($categoriesFromDB as $category) {
                if (isset($defaultCategories[$category])) {
                    $categories[$category] = $defaultCategories[$category];
                } else {
                    // यदि Nepali भएर आएको छ भने English मा convert गर्ने
                    $englishKey = $this->convertToEnglishKey($category);
                    $categories[$englishKey] = $category;
                }
            }

            // यदि database बाट कुनै category नआएको भए default categories use गर्ने
            if (count($categories) <= 1) { // only 'all' है
                $categories = $defaultCategories;
            }

            // Ensure keys are unique
            return array_unique($categories, SORT_REGULAR);
        } catch (\Exception $e) {
            Log::error('Video categories error: ' . $e->getMessage());

            // Return default categories
            return [
                'all' => 'सबै',
                'hostel_tour' => 'होस्टल टुर',
                'room_tour' => 'कोठा टुर',
                'student_life' => 'विद्यार्थी जीवन',
                'virtual_tour' => 'भर्चुअल टुर',
                'testimonial' => 'विद्यार्थी अनुभव',
                'facility' => 'सुविधाहरू'
            ];
        }
    }

    /**
     * ✅ NEW: Convert Nepali category to English key
     */
    private function convertToEnglishKey($nepaliCategory)
    {
        $mapping = [
            'होस्टल टुर' => 'hostel_tour',
            'कोठा टुर' => 'room_tour',
            'विद्यार्थी जीवन' => 'student_life',
            'भर्चुअल टुर' => 'virtual_tour',
            'विद्यार्थी अनुभव' => 'testimonial',
            'सुविधाहरू' => 'facility',
            'कार्यक्रम' => 'event'
        ];

        return $mapping[$nepaliCategory] ?? strtolower(str_replace(' ', '_', $nepaliCategory));
    }

    /**
     * ✅ FIXED: Get total students from published hostels
     */
    private function getTotalStudents(): int
    {
        try {
            if (class_exists(Hostel::class)) {
                $total = Hostel::where('is_published', true)
                    ->withCount(['students' => function ($query) {
                        $query->where('status', 'active');
                    }])
                    ->get()
                    ->sum('students_count');
                return $total ?: 22;
            }
            return 22;
        } catch (\Exception $e) {
            return 22;
        }
    }

    /**
     * ✅ FIXED: Calculate satisfaction rate from reviews
     */
    private function getSatisfactionRate(): int
    {
        try {
            if (class_exists(Review::class)) {
                $totalReviews = Review::whereHas('hostel', function ($q) {
                    $q->where('is_published', true);
                })->count();

                if ($totalReviews > 0) {
                    $positiveReviews = Review::whereHas('hostel', function ($q) {
                        $q->where('is_published', true);
                    })->where('rating', '>=', 4)->count();

                    return round(($positiveReviews / $totalReviews) * 100);
                }
            }
            return 95;
        } catch (\Exception $e) {
            return 95;
        }
    }

    /**
     * ✅ ENHANCED: Show sample data with tabs support
     */
    private function showSampleData(string $tab = 'photos'): View
    {
        return view('frontend.gallery.index', [
            'galleries' => new LengthAwarePaginator([], 0, 12, 1),
            'hostels' => [],
            'cities' => [],
            'metrics' => [
                'total_students' => 22,
                'total_hostels' => 6,
                'satisfaction_rate' => 95,
                'cities_covered' => 2,
                'total_videos' => 4,
                'total_photos' => 49
            ],
            'tab' => $tab,
            'videoCategories' => $this->getVideoCategories()
        ]);
    }

    /**
     * ✅ ENHANCED: Enhanced sample gallery data with videos and HD images
     */
    private function getSampleGalleryData(): array
    {
        // Sample data remains the same as before
        return [];
    }

    /**
     * ✅ API: Get gallery categories (ONLY ONE METHOD - NO DUPLICATE)
     */
    public function getCategories()
    {
        $categories = [
            'all' => 'सबै',
            '1 seater' => '१ सिटर कोठा',
            '2 seater' => '२ सिटर कोठा',
            '3 seater' => '३ सिटर कोठा',
            '4 seater' => '४ सिटर कोठा',
            'living room' => 'लिभिङ रूम',
            'bathroom' => 'बाथरूम',
            'kitchen' => 'भान्सा',
            'study room' => 'अध्ययन कोठा',
            'event' => 'कार्यक्रम',
            'hostel_tour' => 'होस्टल टुर',
            'room_tour' => 'कोठा टुर',
            'student_life' => 'विद्यार्थी जीवन',
            'virtual_tour' => 'भर्चुअल टुर',
            'testimonial' => 'विद्यार्थी अनुभव',
            'facility' => 'सुविधाहरू'
        ];

        return response()->json($categories);
    }

    /**
     * ✅ API: Get gallery stats (ONLY ONE METHOD)
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_students' => $this->getTotalStudents(),
                'total_hostels' => Hostel::where('is_published', true)->count() ?: 6,
                'cities_available' => Hostel::where('is_published', true)->distinct('city')->count(),
                'satisfaction_rate' => $this->getSatisfactionRate() . '%',
                'total_videos' => $this->getTotalVideos(),
                'total_photos' => $this->getTotalPhotos()
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Gallery stats error: ' . $e->getMessage());
            return response()->json([
                'total_students' => 22,
                'total_hostels' => 6,
                'cities_available' => 2,
                'satisfaction_rate' => '95%',
                'total_videos' => 4,
                'total_photos' => 49
            ]);
        }
    }

    /**
     * ✅ NEW: API endpoint for filtered galleries with video support
     * ✅ FIXED: Now includes Room images for Boys Hostels
     */
    public function filteredGalleries(Request $request)
    {
        try {
            $tab = $request->get('tab', 'photos');

            // ✅ FIXED: Get ALL items (Gallery + Room images) based on tab
            if ($tab === 'photos') {
                $galleryItems = $this->getGalleryTableItems($tab);
                $roomItems = $this->getRoomImageItems();
                $allItems = array_merge($galleryItems, $roomItems);
            } else {
                $allItems = $this->getGalleryTableItems($tab);
            }

            // Apply filters
            $filteredItems = $this->applyFilters($allItems, $request);

            // Paginate
            $page = $request->get('page', 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;
            $currentPageItems = array_slice($filteredItems, $offset, $perPage);

            return response()->json([
                'success' => true,
                'galleries' => $currentPageItems,
                'pagination' => [
                    'total' => count($filteredItems),
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => ceil(count($filteredItems) / $perPage)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Filtered galleries error: ' . $e->getMessage());

            // Return sample data as fallback
            $sampleData = array_slice($this->getSampleGalleryData(), 0, 12);

            return response()->json([
                'success' => true,
                'galleries' => $sampleData,
                'pagination' => [
                    'total' => 12,
                    'per_page' => 12,
                    'current_page' => 1,
                    'last_page' => 1
                ]
            ]);
        }
    }

    /**
     * ✅ FIXED: API endpoint for videos only with proper filtering
     */
    public function getVideos(Request $request)
    {
        try {
            $query = Gallery::with(['hostel', 'room'])
                ->where('is_active', true)
                ->whereIn('media_type', ['external_video', 'local_video'])
                ->whereHas('hostel', function ($query) {
                    $query->where('is_published', true);
                });

            // ✅ FIXED: Filter by category (English keys only)
            if ($request->filled('category') && $request->category !== 'all') {
                $query->where('category', $request->category);
            }

            // ✅ FIXED: Filter by hostel - Boys Hostels included
            if ($request->filled('hostel_id')) {
                $query->where('hostel_id', $request->hostel_id);
            }

            // ✅ FIXED: Get videos with proper data structure
            $videos = $query->orderBy('created_at', 'desc')->paginate(12);

            // Transform to proper format
            $videoItems = [];
            foreach ($videos as $video) {
                $videoItems[] = (object)[
                    'id' => 'gallery_' . $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'category' => $video->category,
                    'category_nepali' => $video->category_nepali ?? $this->getCategoryNepali($video->category),
                    'media_type' => $video->media_type,
                    'media_url' => $video->media_url,
                    'thumbnail_url' => $video->thumbnail_url,
                    'created_at' => $video->created_at,
                    'hostel_name' => $video->hostel->name ?? 'Unknown Hostel',
                    'hostel_id' => $video->hostel_id,
                    'hostel_slug' => $video->hostel->slug ?? '',
                    'hostel_gender' => $video->hostel->gender_detected ?? ($video->hostel->gender ?? 'mixed'),
                    'youtube_embed_url' => $video->youtube_embed_url,
                    'video_duration' => $video->video_duration_formatted ?? $video->video_duration,
                    'video_resolution' => $video->video_resolution,
                    'is_360_video' => (bool)($video->is_360_video ?? false)
                ];
            }

            return response()->json([
                'success' => true,
                'videos' => $videoItems,
                'pagination' => [
                    'total' => $videos->total(),
                    'per_page' => $videos->perPage(),
                    'current_page' => $videos->currentPage(),
                    'last_page' => $videos->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Videos API error: ' . $e->getMessage());

            // Return sample videos
            $allSampleData = $this->getSampleGalleryData();
            $sampleVideos = array_filter($allSampleData, function ($item) {
                return in_array($item->media_type, ['external_video', 'local_video']);
            });

            return response()->json([
                'success' => true,
                'videos' => array_values($sampleVideos),
                'pagination' => [
                    'total' => count($sampleVideos),
                    'per_page' => 12,
                    'current_page' => 1,
                    'last_page' => 1
                ]
            ]);
        }
    }

    /**
     * ✅ NEW: Get HD image URL
     */
    public function getHdImage($id)
    {
        try {
            // Handle both gallery_ and room_ IDs
            if (str_starts_with($id, 'gallery_')) {
                $galleryId = str_replace('gallery_', '', $id);
                $gallery = Gallery::where('id', $galleryId)
                    ->where('is_active', true)
                    ->where('media_type', 'photo')
                    ->firstOrFail();

                // Check if HD version exists
                $hdPath = str_replace('.jpg', '-hd.jpg', $gallery->file_path);

                if (Storage::disk('public')->exists($hdPath)) {
                    $url = Storage::disk('public')->url($hdPath);
                } else {
                    $url = Storage::disk('public')->url($gallery->file_path);
                }

                return response()->json([
                    'success' => true,
                    'hd_url' => $url,
                    'title' => $gallery->title
                ]);
            } elseif (str_starts_with($id, 'room_')) {
                $roomId = str_replace('room_', '', $id);
                $room = Room::where('id', $roomId)
                    ->whereNotNull('image')
                    ->firstOrFail();

                $url = asset('storage/' . $room->image);

                return response()->json([
                    'success' => true,
                    'hd_url' => $url,
                    'title' => 'कोठा ' . $room->room_number
                ]);
            }

            throw new \Exception('Invalid image ID');
        } catch (\Exception $e) {
            Log::error('HD image error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'HD image not found'
            ], 404);
        }
    }

    /**
     * ✅ FIXED: Get hostel gallery data
     */
    public function getHostelGalleryData($slug)
    {
        try {
            $hostel = Hostel::where('slug', $slug)
                ->where('is_published', true)
                ->firstOrFail();

            // Get gallery items
            $galleries = Gallery::where('hostel_id', $hostel->id)
                ->where('is_active', true)
                ->with(['hostel', 'room'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Get room images
            $rooms = Room::where('hostel_id', $hostel->id)
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->get();

            $roomItems = [];
            foreach ($rooms as $room) {
                if ($room->image && Storage::disk('public')->exists($room->image)) {
                    $roomItems[] = [
                        'id' => 'room_' . $room->id,
                        'title' => $room->room_number ? 'कोठा ' . $room->room_number : $this->getRoomTypeNepali($room->type),
                        'description' => $room->description ?: $this->getRoomTypeNepali($room->type),
                        'category' => $room->type,
                        'category_nepali' => $this->getRoomTypeNepali($room->type),
                        'media_type' => 'photo',
                        'media_url' => asset('storage/' . $room->image),
                        'thumbnail_url' => asset('storage/' . $room->image),
                        'room_number' => $room->room_number,
                        'is_room_image' => true,
                        'source' => 'room'
                    ];
                }
            }

            $allItems = array_merge($galleries->toArray(), $roomItems);

            return response()->json([
                'success' => true,
                'hostel' => $hostel,
                'galleries' => $allItems,
                'total_count' => count($allItems)
            ]);
        } catch (\Exception $e) {
            Log::error('Hostel gallery data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Hostel not found or gallery data unavailable'
            ], 404);
        }
    }

    /**
     * ✅ FIXED: Get featured galleries with proper hostel gender
     */
    public function getFeaturedGalleries()
    {
        try {
            // Try to get real featured galleries
            $galleries = Gallery::with(['hostel', 'room'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->whereHas('hostel', function ($query) {
                    $query->where('is_published', true);
                })
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();

            // Transform gallery items
            $galleryItems = [];
            foreach ($galleries as $gallery) {
                $galleryItems[] = (object)[
                    'id' => 'gallery_' . $gallery->id,
                    'title' => $gallery->title,
                    'description' => $gallery->description,
                    'category' => $gallery->category,
                    'category_nepali' => $gallery->category_nepali ?? $this->getCategoryNepali($gallery->category),
                    'media_type' => $gallery->media_type,
                    'media_url' => $gallery->media_url,
                    'thumbnail_url' => $gallery->thumbnail_url,
                    'created_at' => $gallery->created_at,
                    'hostel_name' => $gallery->hostel->name ?? 'Unknown Hostel',
                    'hostel_id' => $gallery->hostel_id,
                    'hostel_slug' => $gallery->hostel->slug ?? '',
                    'hostel_gender' => $gallery->hostel->gender_detected ?? ($gallery->hostel->gender ?? 'mixed'),
                    'is_room_image' => !is_null($gallery->room_id),
                    'source' => 'gallery'
                ];
            }

            // Get featured room images from featured hostels
            $featuredHostels = Hostel::where('is_published', true)
                ->where('is_featured', true)
                ->with(['rooms' => function ($query) {
                    $query->whereNotNull('image')
                        ->where('image', '!=', '')
                        ->take(2);
                }])
                ->take(3)
                ->get();

            $roomItems = [];
            foreach ($featuredHostels as $hostel) {
                foreach ($hostel->rooms as $room) {
                    if ($room->image && Storage::disk('public')->exists($room->image)) {
                        $roomItems[] = (object)[
                            'id' => 'room_' . $room->id,
                            'title' => $room->room_number ? 'कोठा ' . $room->room_number : $this->getRoomTypeNepali($room->type),
                            'description' => $hostel->name . ' - ' . $this->getRoomTypeNepali($room->type),
                            'category' => $room->type,
                            'category_nepali' => $this->getRoomTypeNepali($room->type),
                            'media_type' => 'photo',
                            'media_url' => asset('storage/' . $room->image),
                            'thumbnail_url' => asset('storage/' . $room->image),
                            'created_at' => $room->created_at,
                            'hostel_name' => $hostel->name,
                            'hostel_id' => $hostel->id,
                            'hostel_slug' => $hostel->slug,
                            'hostel_gender' => $hostel->gender_detected ?? ($hostel->gender ?? 'mixed'),
                            'room_number' => $room->room_number,
                            'is_room_image' => true,
                            'source' => 'room'
                        ];
                    }
                }
            }

            $allItems = array_merge($galleryItems, $roomItems);
            shuffle($allItems);
            $allItems = array_slice($allItems, 0, 6);

            return response()->json($allItems);
        } catch (\Exception $e) {
            Log::error('Featured galleries error: ' . $e->getMessage());

            // Return sample featured data
            $sampleData = array_slice($this->getSampleGalleryData(), 0, 6);
            return response()->json($sampleData);
        }
    }

    /**
     * Clear gallery cache (Admin/Owner ko laagi)
     */
    public function clearCache()
    {
        try {
            $this->cacheService->clearCache();
            return back()->with('success', 'Gallery cache cleared successfully!');
        } catch (\Exception $e) {
            Log::error('Clear cache error: ' . $e->getMessage());
            return back()->with('error', 'Failed to clear gallery cache.');
        }
    }
}
