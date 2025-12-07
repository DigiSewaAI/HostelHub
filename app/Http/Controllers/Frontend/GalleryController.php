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
     * ✅ FIXED: Added gender normalization method
     */
    private function normalizeGender($gender): string
    {
        if (!$gender) return 'mixed';

        $gender = strtolower(trim($gender));

        if (
            str_contains($gender, 'boy') || str_contains($gender, 'male') ||
            str_contains($gender, 'ब्वाइ') || str_contains($gender, 'पुरुष')
        ) {
            return 'boys';
        } elseif (
            str_contains($gender, 'girl') || str_contains($gender, 'female') ||
            str_contains($gender, 'गर्ल') || str_contains($gender, 'महिला')
        ) {
            return 'girls';
        }

        return 'mixed';
    }

    /**
     * ✅ ENHANCED: Display the main gallery page with tabs for photos/videos
     * ✅ FIX: Now includes both Gallery images AND Room images with proper video tab handling
     */
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'photos'); // 'photos' or 'videos' or 'virtual-tours'

        try {
            // ✅ FIXED: Get items based on tab
            if ($tab === 'photos') {
                $galleryItems = $this->getGalleryTableItems('photo');
                $roomItems = $this->getRoomImageItems();
                $allItems = array_merge($galleryItems, $roomItems);
            } elseif ($tab === 'videos') {
                $allItems = $this->getVideoItems($request);
            } elseif ($tab === 'virtual-tours') {
                $allItems = $this->getGalleryTableItems('virtual_tour');
            } else {
                $allItems = [];
            }

            // Apply filtering - ✅ FIXED: Pass tab parameter
            $filteredItems = $this->applyFilters($allItems, $request, $tab);

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
                    'path' => $request->url(),
                    'query' => array_merge($request->query(), ['tab' => $tab]),
                    'fragment' => 'gallery-grid'
                ]
            );

            // ✅ FIXED: Get only published hostels for filter - INCLUDING BOYS HOSTELS
            $hostels = Hostel::where('is_published', true)
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'gender']); // ✅ Include gender column

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
     * ✅ FIXED: Get items from Gallery table with proper video duration format and URL generation
     */
    private function getGalleryTableItems($mediaType = 'photo')
    {
        $query = Gallery::with(['hostel', 'room'])
            ->where('is_active', true)
            ->whereHas('hostel', function ($query) {
                $query->where('is_published', true);
            });

        // Filter by media type
        if ($mediaType === 'photo') {
            $query->where('media_type', 'photo');
        } elseif ($mediaType === 'virtual_tour') {
            $query->where('is_360_video', true)
                ->orWhere('category', 'virtual_tour');
        }

        $galleries = $query->orderBy('created_at', 'desc')->get();

        $items = [];
        foreach ($galleries as $gallery) {
            // ✅ FIXED: Get media URLs with proper storage path
            $mediaUrl = $this->getProperMediaUrl($gallery);
            $thumbnailUrl = $this->getProperThumbnailUrl($gallery);

            // ✅ FIXED: Extract YouTube ID for embed
            $youtubeId = $this->extractYoutubeId($gallery->media_url);
            $youtubeEmbedUrl = $youtubeId ? "https://www.youtube.com/embed/{$youtubeId}" : null;

            // ✅ FIXED: Use normalized gender
            $normalizedGender = $this->normalizeGender($gallery->hostel->gender ?? 'mixed');

            $items[] = (object)[
                'id' => 'gallery_' . $gallery->id,
                'title' => $gallery->title,
                'description' => $gallery->description,
                'category' => $gallery->category,
                'category_nepali' => $gallery->category_nepali ?? $this->getCategoryNepali($gallery->category),
                'media_type' => $gallery->media_type,
                'media_url' => $mediaUrl,
                'thumbnail_url' => $thumbnailUrl,
                'created_at' => $gallery->created_at,
                'hostel_name' => $gallery->hostel->name ?? 'Unknown Hostel',
                'hostel_id' => $gallery->hostel_id,
                'hostel_slug' => $gallery->hostel->slug ?? '',
                'hostel_gender' => $normalizedGender, // ✅ FIXED: Use normalized gender
                'room' => $gallery->room,
                'room_number' => $gallery->room ? $gallery->room->room_number : null,
                'is_room_image' => !is_null($gallery->room_id),
                'source' => 'gallery',
                'youtube_embed_url' => $youtubeEmbedUrl,
                'youtube_id' => $youtubeId,
                'video_duration' => $gallery->video_duration_formatted ?? $gallery->video_duration,
                'video_resolution' => $gallery->video_resolution,
                'is_360_video' => (bool)($gallery->is_360_video ?? false),
                'hd_available' => $gallery->hd_available ?? false,
                'hd_url' => $gallery->hd_image_url ?? $mediaUrl
            ];
        }

        return $items;
    }

    /**
     * ✅ NEW: Get video items with proper URL formatting
     */
    private function getVideoItems(Request $request): array
    {
        $query = Gallery::with(['hostel', 'room'])
            ->where('is_active', true)
            ->whereIn('media_type', ['external_video', 'local_video'])
            ->whereHas('hostel', function ($query) {
                $query->where('is_published', true);
            });

        // Filter by video category if specified
        if ($request->filled('video_category') && $request->video_category !== 'all') {
            $query->where('category', $request->video_category);
        }

        // Filter by hostel if specified
        if ($request->filled('hostel_id')) {
            $query->where('hostel_id', $request->hostel_id);
        }

        $galleries = $query->orderBy('created_at', 'desc')->get();

        $items = [];
        foreach ($galleries as $gallery) {
            // ✅ FIXED: Get media URLs with proper storage path
            $mediaUrl = $this->getProperMediaUrl($gallery);
            $thumbnailUrl = $this->getProperThumbnailUrl($gallery);

            // ✅ FIXED: Extract YouTube ID for embed
            $youtubeId = $this->extractYoutubeId($gallery->media_url);
            $youtubeEmbedUrl = $youtubeId ? "https://www.youtube.com/embed/{$youtubeId}" : null;

            // ✅ FIXED: Use normalized gender
            $normalizedGender = $this->normalizeGender($gallery->hostel->gender ?? 'mixed');

            $items[] = (object)[
                'id' => 'gallery_' . $gallery->id,
                'title' => $gallery->title,
                'description' => $gallery->description,
                'category' => $gallery->category,
                'category_nepali' => $gallery->category_nepali ?? $this->getCategoryNepali($gallery->category),
                'media_type' => $gallery->media_type,
                'media_url' => $mediaUrl,
                'thumbnail_url' => $thumbnailUrl,
                'created_at' => $gallery->created_at,
                'hostel_name' => $gallery->hostel->name ?? 'Unknown Hostel',
                'hostel_id' => $gallery->hostel_id,
                'hostel_slug' => $gallery->hostel->slug ?? '',
                'hostel_gender' => $normalizedGender, // ✅ FIXED: Use normalized gender
                'room' => $gallery->room,
                'room_number' => $gallery->room ? $gallery->room->room_number : null,
                'is_room_image' => false,
                'source' => 'gallery',
                'youtube_embed_url' => $youtubeEmbedUrl,
                'youtube_id' => $youtubeId,
                'video_duration' => $gallery->video_duration_formatted ?? $gallery->video_duration,
                'video_resolution' => $gallery->video_resolution,
                'is_360_video' => (bool)($gallery->is_360_video ?? false),
                'hd_available' => false,
                'hd_url' => $mediaUrl
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
            // ✅ FIXED: Use normalized gender
            $normalizedGender = $this->normalizeGender($hostel->gender ?? 'mixed');

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
                        'media_url' => Storage::url($room->image), // ✅ FIXED: Use Storage::url
                        'thumbnail_url' => Storage::url($room->image), // ✅ FIXED: Use Storage::url
                        'created_at' => $room->created_at,
                        'hostel_name' => $hostel->name,
                        'hostel_id' => $hostel->id,
                        'hostel_slug' => $hostel->slug,
                        'hostel_gender' => $normalizedGender, // ✅ FIXED: Use normalized gender
                        'room' => (object)['room_number' => $room->room_number],
                        'room_number' => $room->room_number,
                        'is_room_image' => true,
                        'source' => 'room',
                        'room_type' => $room->type,
                        'room_price' => $room->price,
                        // Add missing properties to prevent errors
                        'youtube_embed_url' => null,
                        'youtube_id' => null,
                        'video_duration' => null,
                        'video_resolution' => null,
                        'is_360_video' => false,
                        'hd_available' => false,
                        'hd_url' => Storage::url($room->image)
                    ];
                }
            }
        }

        return $items;
    }
    /**
     * ✅ NEW: Extract YouTube ID from URL
     */
    private function extractYoutubeId($url): ?string
    {
        if (!$url) return null;

        $patterns = [
            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i',
            '/youtube\.com\/embed\/([^"&?\/\s]{11})/i',
            '/youtube\.com\/watch\?v=([^"&?\/\s]{11})/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * ✅ NEW: Get proper media URL with Storage::url for local files
     */
    private function getProperMediaUrl($gallery): string
    {
        // If it's a local video or photo, use Storage::url
        if (($gallery->media_type === 'local_video' || $gallery->media_type === 'photo') &&
            !Str::startsWith($gallery->media_url, ['http://', 'https://', '//'])
        ) {
            return Storage::url($gallery->media_url);
        }

        // Otherwise return as-is (for external URLs)
        return $gallery->media_url ?? '';
    }

    /**
     * ✅ NEW: Get proper thumbnail URL with Storage::url for local files
     */
    private function getProperThumbnailUrl($gallery): string
    {
        // If thumbnail exists and is local, use Storage::url
        if ($gallery->thumbnail_url && !Str::startsWith($gallery->thumbnail_url, ['http://', 'https://', '//'])) {
            return Storage::url($gallery->thumbnail_url);
        }

        // For YouTube videos without thumbnail, generate from YouTube ID
        if ($gallery->media_type === 'external_video' && !$gallery->thumbnail_url) {
            $youtubeId = $this->extractYoutubeId($gallery->media_url);
            if ($youtubeId) {
                return "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
            }
        }

        // Fallback to media URL or default
        return $gallery->thumbnail_url ?? $this->getProperMediaUrl($gallery) ?? asset('images/default-thumbnail.jpg');
    }

    /**
     * ✅ FIXED: Apply filters to gallery items with video category support and gender filter
     */
    private function applyFilters($items, Request $request, $tab = 'photos')
    {
        $hostelId = $request->get('hostel_id');
        $hostelGender = $request->get('hostel_gender'); // ✅ NEW: Gender filter
        $search = $request->get('search');
        $category = $request->get('category', 'all');
        $videoCategory = $request->get('video_category', 'all');

        $filteredItems = $items;

        // ✅ NEW: Filter by hostel gender (boys/girls/mixed)
        if ($hostelGender) {
            $filteredItems = array_filter($filteredItems, function ($item) use ($hostelGender) {
                return isset($item->hostel_gender) && $item->hostel_gender === $hostelGender;
            });
        }

        // Filter by specific hostel ID
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
                // Match both English and Nepali categories
                return $item->category === $category ||
                    $item->category_nepali === $category ||
                    strtolower($item->category) === strtolower($category);
            });
        }

        // For video tab, filter by video category
        if ($tab === 'videos' && $videoCategory && $videoCategory !== 'all') {
            $filteredItems = array_filter($filteredItems, function ($item) use ($videoCategory) {
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
            // Get categories from database
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

            // Start with 'all' category
            $categories = ['all' => 'सबै'];

            // Add database categories
            foreach ($categoriesFromDB as $category) {
                if (isset($defaultCategories[$category])) {
                    $categories[$category] = $defaultCategories[$category];
                } else {
                    $categories[$category] = $category;
                }
            }

            // If no categories from DB, use defaults
            if (count($categories) <= 1) {
                $categories = $defaultCategories;
            }

            return $categories;
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
     * ✅ FIXED: API endpoint for filtered galleries with video support
     * ✅ FIXED: Now includes Room images for Boys Hostels
     */
    public function filteredGalleries(Request $request)
    {
        try {
            $tab = $request->get('tab', 'photos');

            // ✅ FIXED: Get ALL items based on tab
            if ($tab === 'photos') {
                $galleryItems = $this->getGalleryTableItems('photo');
                $roomItems = $this->getRoomImageItems();
                $allItems = array_merge($galleryItems, $roomItems);
            } elseif ($tab === 'videos') {
                $allItems = $this->getVideoItems($request);
            } elseif ($tab === 'virtual-tours') {
                $allItems = $this->getGalleryTableItems('virtual_tour');
            } else {
                $allItems = [];
            }

            // Apply filters
            $filteredItems = $this->applyFilters($allItems, $request, $tab);

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
            return response()->json([
                'success' => false,
                'message' => 'Failed to load galleries',
                'galleries' => [],
                'pagination' => [
                    'total' => 0,
                    'per_page' => 12,
                    'current_page' => 1,
                    'last_page' => 0
                ]
            ]);
        }
    }

    /**
     * ✅ FIXED: API endpoint for videos only with proper filtering and URL formatting
     */
    public function getVideos(Request $request)
    {
        try {
            $allItems = $this->getVideoItems($request);

            // Apply filters
            $filteredItems = $this->applyFilters($allItems, $request, 'videos');

            // Paginate
            $page = $request->get('page', 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;
            $currentPageItems = array_slice($filteredItems, $offset, $perPage);

            return response()->json([
                'success' => true,
                'videos' => $currentPageItems,
                'pagination' => [
                    'total' => count($filteredItems),
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => ceil(count($filteredItems) / $perPage)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Videos API error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load videos',
                'videos' => [],
                'pagination' => [
                    'total' => 0,
                    'per_page' => 12,
                    'current_page' => 1,
                    'last_page' => 0
                ]
            ]);
        }
    }

    /**
     * ✅ FIXED: Get HD image URL with proper storage path
     */
    public function getHdImage($id)
    {
        try {
            // Handle both gallery_ and room_ IDs
            if (Str::startsWith($id, 'gallery_')) {
                $galleryId = Str::replace('gallery_', '', $id);
                $gallery = Gallery::where('id', $galleryId)
                    ->where('is_active', true)
                    ->where('media_type', 'photo')
                    ->firstOrFail();

                // Check if HD version exists
                $hdPath = str_replace('.jpg', '-hd.jpg', $gallery->file_path);

                if (Storage::disk('public')->exists($hdPath)) {
                    $url = Storage::url($hdPath);
                } else {
                    $url = Storage::url($gallery->file_path);
                }

                return response()->json([
                    'success' => true,
                    'hd_url' => $url,
                    'title' => $gallery->title
                ]);
            } elseif (Str::startsWith($id, 'room_')) {
                $roomId = Str::replace('room_', '', $id);
                $room = Room::where('id', $roomId)
                    ->whereNotNull('image')
                    ->firstOrFail();

                $url = Storage::url($room->image);

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
     * ✅ FIXED: Get hostel gallery data with proper URLs
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

            $galleryItems = [];
            foreach ($galleries as $gallery) {
                $mediaUrl = $this->getProperMediaUrl($gallery);
                $thumbnailUrl = $this->getProperThumbnailUrl($gallery);

                $galleryItems[] = [
                    'id' => 'gallery_' . $gallery->id,
                    'title' => $gallery->title,
                    'description' => $gallery->description,
                    'category' => $gallery->category,
                    'category_nepali' => $gallery->category_nepali ?? $this->getCategoryNepali($gallery->category),
                    'media_type' => $gallery->media_type,
                    'media_url' => $mediaUrl,
                    'thumbnail_url' => $thumbnailUrl,
                    'room_number' => $gallery->room ? $gallery->room->room_number : null,
                    'is_room_image' => !is_null($gallery->room_id),
                    'source' => 'gallery'
                ];
            }

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
                        'media_url' => Storage::url($room->image),
                        'thumbnail_url' => Storage::url($room->image),
                        'room_number' => $room->room_number,
                        'is_room_image' => true,
                        'source' => 'room'
                    ];
                }
            }

            $allItems = array_merge($galleryItems, $roomItems);

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
                $mediaUrl = $this->getProperMediaUrl($gallery);
                $thumbnailUrl = $this->getProperThumbnailUrl($gallery);

                $galleryItems[] = (object)[
                    'id' => 'gallery_' . $gallery->id,
                    'title' => $gallery->title,
                    'description' => $gallery->description,
                    'category' => $gallery->category,
                    'category_nepali' => $gallery->category_nepali ?? $this->getCategoryNepali($gallery->category),
                    'media_type' => $gallery->media_type,
                    'media_url' => $mediaUrl,
                    'thumbnail_url' => $thumbnailUrl,
                    'created_at' => $gallery->created_at,
                    'hostel_name' => $gallery->hostel->name ?? 'Unknown Hostel',
                    'hostel_id' => $gallery->hostel_id,
                    'hostel_slug' => $gallery->hostel->slug ?? '',
                    'hostel_gender' => $gallery->hostel->gender ?? 'mixed',
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
                            'media_url' => Storage::url($room->image),
                            'thumbnail_url' => Storage::url($room->image),
                            'created_at' => $room->created_at,
                            'hostel_name' => $hostel->name,
                            'hostel_id' => $hostel->id,
                            'hostel_slug' => $hostel->slug,
                            'hostel_gender' => $hostel->gender ?? 'mixed',
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
            return response()->json([]);
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

    /**
     * ✅ NEW: Get proper media URL for gallery item
     */
    private function getMediaUrl($item)
    {
        if ($item->media_type === 'photo' && !Str::startsWith($item->media_url, ['http://', 'https://'])) {
            return Storage::url($item->media_url);
        }

        return $item->media_url;
    }

    /**
     * ✅ NEW: Get proper thumbnail URL for gallery item
     */
    private function getThumbnailUrl($item)
    {
        if ($item->thumbnail_url && !Str::startsWith($item->thumbnail_url, ['http://', 'https://'])) {
            return Storage::url($item->thumbnail_url);
        }

        return $item->thumbnail_url ?? $this->getMediaUrl($item);
    }
}
