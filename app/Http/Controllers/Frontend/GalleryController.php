<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Hostel;
use App\Services\GalleryCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    protected $cacheService;

    public function __construct()
    {
        $this->cacheService = new GalleryCacheService();
    }

    /**
     * Display the main gallery page WITH HOSTEL NAMES AND CACHE
     */
    public function index()
    {
        try {
            // ✅ FIXED: Simplified query with proper error handling
            $galleries = Gallery::with(['hostel', 'room'])
                ->where('is_active', true)
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            // Get unique hostels for filter
            $hostels = Hostel::where('is_published', true)
                ->whereHas('galleries', function ($query) {
                    $query->where('is_active', true);
                })
                ->get(['id', 'name']);

            // ✅ FIXED: Add cities data for the stats section
            $cities = Hostel::where('is_published', true)
                ->distinct('city')
                ->pluck('city')
                ->filter()
                ->values();

            // ✅ FIXED: Add metrics data for the stats section
            $metrics = [
                'total_students' => 500,
                'total_hostels' => $hostels->count(),
                'cities_available' => $cities->count(),
                'satisfaction_rate' => '98%'
            ];

            return view('frontend.gallery.index', compact('galleries', 'hostels', 'cities', 'metrics'));
        } catch (\Exception $e) {
            \Log::error('Gallery index error: ' . $e->getMessage());

            // Return empty data on error
            return view('frontend.gallery.index', [
                'galleries' => [],
                'hostels' => [],
                'cities' => [],
                'metrics' => [
                    'total_students' => 0,
                    'total_hostels' => 0,
                    'cities_available' => 0,
                    'satisfaction_rate' => '0%'
                ]
            ]);
        }
    }

    /**
     * API: Get gallery categories
     */
    public function getCategories()
    {
        $categories = [
            'all' => 'सबै',
            'single' => '१ सिटर कोठा',
            'double' => '२ सिटर कोठा',
            'triple' => '३ सिटर कोठा',
            'quad' => '४ सिटर कोठा',
            'common' => 'लिभिङ रूम',
            'bathroom' => 'बाथरूम',
            'kitchen' => 'भान्सा',
            'study' => 'अध्ययन कोठा',
            'event' => 'कार्यक्रम',
            'video' => 'भिडियो टुर'
        ];

        return response()->json($categories);
    }

    /**
     * API: Get gallery stats
     */
    public function getStats()
    {
        $stats = [
            'total_students' => 500,
            'total_hostels' => 25,
            'cities_available' => 5,
            'satisfaction_rate' => '98%'
        ];

        return response()->json($stats);
    }

    /**
     * API: Get gallery data WITH HOSTEL NAMES AND CACHE
     */
    public function getGalleryData()
    {
        try {
            // ✅ FIXED: Use cache service for API with fallback
            $galleries = $this->cacheService->getPublicGalleries()
                ->map(function ($item) {
                    return $this->formatGalleryItemWithHostel($item);
                });

            // Fallback sample data if no items found
            if ($galleries->isEmpty()) {
                $galleries = collect($this->getSampleGalleryData());
            }

            return response()->json($galleries);
        } catch (\Exception $e) {
            \Log::error('Gallery API data error: ' . $e->getMessage());
            return response()->json($this->getSampleGalleryData());
        }
    }

    /**
     * API: Get featured galleries WITH CACHE
     */
    public function getFeaturedGalleries()
    {
        try {
            // ✅ FIXED: Featured galleries cache bata lyaune with error handling
            $galleries = $this->cacheService->getFeaturedGalleries()
                ->map(function ($item) {
                    return $this->formatGalleryItemWithHostel($item);
                });

            return response()->json($galleries);
        } catch (\Exception $e) {
            \Log::error('Featured galleries error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Format gallery item WITH HOSTEL NAME for API response
     */
    private function formatGalleryItemWithHostel($item): array
    {
        // ✅ FIXED: Handle both object and array items safely
        $hostelName = null;
        $hostelId = null;
        $roomNumber = null;
        $isRoomImage = false;

        if (is_object($item)) {
            $hostelName = $item->hostel->name ?? ($item->hostel_name ?? 'Unknown Hostel');
            $hostelId = $item->hostel_id ?? null;
            $roomNumber = $item->room->room_number ?? null;
            $isRoomImage = !is_null($item->room_id ?? null);
            $createdAt = $item->created_at ?? now();
        } else {
            $hostelName = $item['hostel_name'] ?? 'Unknown Hostel';
            $hostelId = $item['hostel_id'] ?? null;
            $roomNumber = $item['room_number'] ?? null;
            $isRoomImage = $item['is_room_image'] ?? false;
            $createdAt = isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at']) : now();
        }

        return [
            'id' => $item->id ?? $item['id'] ?? 0,
            'title' => $item->title ?? $item['title'] ?? '',
            'description' => $item->description ?? $item['description'] ?? '',
            'category' => $item->category ?? $item['category'] ?? 'common',
            'media_type' => $item->media_type ?? $item['media_type'] ?? 'photo',
            'file_url' => $item->media_url ?? $item['file_url'] ?? '',
            'thumbnail_url' => $item->thumbnail_url ?? $item['thumbnail_url'] ?? '',
            'external_link' => $item->external_link ?? $item['external_link'] ?? '',
            'created_at' => $createdAt->format('Y-m-d'),
            'hostel_name' => $hostelName,
            'hostel_id' => $hostelId,
            'room_number' => $roomNumber,
            'is_room_image' => $isRoomImage
        ];
    }

    /**
     * Format gallery item for API response (original preserved)
     */
    private function formatGalleryItem($item)
    {
        $formatted = [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'category' => $item->category,
            'media_type' => $item->media_type,
            'file_url' => $item->file_url ?? '',
            'thumbnail_url' => $item->thumbnail_url ?? '',
            'external_link' => $item->external_link ?? '',
            'created_at' => $item->created_at->format('Y-m-d'),
        ];

        return $formatted;
    }

    /**
     * Sample gallery data fallback
     */
    private function getSampleGalleryData()
    {
        return [
            [
                'id' => 1,
                'title' => 'आरामदायी १ सिटर कोठा',
                'description' => 'विद्यार्थीहरूको लागि आरामदायी एक सिटर कोठा',
                'category' => 'single',
                'media_type' => 'photo',
                'file_url' => asset('images/sample-room-1.jpg'),
                'thumbnail_url' => asset('images/sample-room-1-thumb.jpg'),
                'created_at' => '2024-01-15',
                'hostel_name' => 'नमूना होस्टल',
                'hostel_id' => 1,
                'room_number' => '101',
                'is_room_image' => true
            ],
            [
                'id' => 2,
                'title' => 'होस्टल भिडियो टुर',
                'description' => 'हाम्रो होस्टलको पूर्ण भिडियो टुर',
                'category' => 'video',
                'media_type' => 'external_video',
                'external_link' => 'https://www.youtube.com/embed/sample-video',
                'thumbnail_url' => asset('images/video-thumbnail.jpg'),
                'created_at' => '2024-01-10',
                'hostel_name' => 'नमूना होस्टल',
                'hostel_id' => 1,
                'room_number' => null,
                'is_room_image' => false
            ],
        ];
    }

    /**
     * Display the public gallery for a specific hostel
     */
    public function show($slug)
    {
        try {
            \Log::info('=== GALLERY DEBUG START ===');

            $hostel = Hostel::where('slug', $slug)->firstOrFail();

            \Log::info('Hostel details:', [
                'id' => $hostel->id,
                'name' => $hostel->name,
                'slug' => $hostel->slug,
                'status' => $hostel->status,
                'status_type' => gettype($hostel->status),
                'theme' => $hostel->theme
            ]);

            $galleries = Gallery::where('hostel_id', $hostel->id)
                ->where('is_active', true)
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            \Log::info('Gallery items count:', ['count' => $galleries->count()]);
            \Log::info('=== GALLERY DEBUG END ===');

            return view('public.hostels.gallery', compact('hostel', 'galleries'));
        } catch (\Exception $e) {
            \Log::error('Hostel gallery show error: ' . $e->getMessage());
            abort(404, 'Hostel gallery not found');
        }
    }

    /**
     * API Endpoint: Get gallery data for a hostel WITH HOSTEL NAME BADGES
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
            return response()->json([]);
        }
    }

    /**
     * API: Filter galleries by hostel
     */
    public function filterByHostel(Request $request)
    {
        try {
            $hostelId = $request->get('hostel_id');

            // ✅ FIXED: Use proper scope or remove if scope doesn't exist
            $galleries = Gallery::with(['hostel', 'room'])
                ->where('is_active', true) // Use direct where instead of scope
                ->when($hostelId, function ($query) use ($hostelId) {
                    return $query->where('hostel_id', $hostelId);
                })
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return $this->formatGalleryItemWithHostel($item);
                });

            return response()->json($galleries);
        } catch (\Exception $e) {
            \Log::error('Gallery filter error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Helper method to get categories list
     */
    private function getCategoriesList()
    {
        return [
            'all'       => 'सबै',
            'single'  => '१ सिटर कोठा',
            'double'  => '२ सिटर कोठा',
            'triple'  => '३ सिटर कोठा',
            'quad'  => '४ सिटर कोठा',
            'common'    => 'लिभिङ रूम',
            'bathroom'  => 'बाथरूम',
            'kitchen'   => 'भान्सा',
            'study room' => 'अध्ययन कोठा',
            'event'     => 'कार्यक्रम',
            'video'     => 'भिडियो टुर',
        ];
    }

    /**
     * Clear gallery cache (Admin/Owner ko laagi)
     */
    public function clearCache()
    {
        try {
            // ✅ FIXED: Cache clear garne method with error handling
            $this->cacheService->clearCache();

            return back()->with('success', 'Gallery cache cleared successfully!');
        } catch (\Exception $e) {
            \Log::error('Clear cache error: ' . $e->getMessage());
            return back()->with('error', 'Failed to clear gallery cache.');
        }
    }

    /**
     * Process media item based on its type
     */
    private function processMediaItem(Gallery $item): array
    {
        $result = [
            'file_exists' => '❌ हुँदैन',
            'file_url' => '',
            'thumbnail_url' => asset('images/default-thumbnail.jpg'),
            'absolute_path' => '',
            'media_type' => $item->media_type,
        ];

        if ($item->media_type === 'photo') {
            return $this->processPhotoItem($item, $result);
        }

        if ($item->media_type === 'local_video') {
            return $this->processLocalVideoItem($item, $result);
        }

        if ($item->media_type === 'external_video') {
            return $this->processExternalVideoItem($item, $result);
        }

        return $result;
    }

    /**
     * Process photo media items
     */
    private function processPhotoItem(Gallery $item, array $result): array
    {
        if (!$item->file_path) {
            return $result;
        }

        $fileExists = Storage::disk('public')->exists($item->file_path);

        $result['file_exists'] = $fileExists ? '✅ हुन्छ' : '❌ हुँदैन';

        if ($fileExists) {
            $result['file_url'] = Storage::disk('public')->url($item->file_path);

            // Handle thumbnail
            if ($item->thumbnail) {
                if (Storage::disk('public')->exists($item->thumbnail)) {
                    $result['thumbnail_url'] = Storage::disk('public')->url($item->thumbnail);
                } else {
                    $result['thumbnail_url'] = $result['file_url'];
                }
            } else {
                $result['thumbnail_url'] = $result['file_url'];
            }
        }

        return $result;
    }

    /**
     * Process local video items
     */
    private function processLocalVideoItem(Gallery $item, array $result): array
    {
        if (!$item->file_path) {
            return $result;
        }

        $fileExists = Storage::disk('public')->exists($item->file_path);

        $result['file_exists'] = $fileExists ? '✅ हुन्छ' : '❌ हुँदैन';

        if ($fileExists) {
            $result['file_url'] = Storage::disk('public')->url($item->file_path);
            $result['video_url'] = $result['file_url'];

            // Handle thumbnail
            if ($item->thumbnail) {
                if (Storage::disk('public')->exists($item->thumbnail)) {
                    $result['thumbnail_url'] = Storage::disk('public')->url($item->thumbnail);
                } else {
                    $result['thumbnail_url'] = asset('images/video-default.jpg');
                }
            } else {
                $result['thumbnail_url'] = asset('images/video-default.jpg');
            }
        }

        return $result;
    }

    /**
     * Process External Video (YouTube/Vimeo)
     */
    private function processExternalVideoItem(Gallery $item, array $result): array
    {
        $result['file_exists'] = '✅ (External)';

        if ($item->external_link) {
            $result['file_url'] = $item->external_link;
            $youtubeId = $this->getYoutubeIdFromUrl($item->external_link);
            $result['youtube_id'] = $youtubeId;
        }

        // Handle thumbnail
        if ($item->thumbnail) {
            if (filter_var($item->thumbnail, FILTER_VALIDATE_URL)) {
                $result['thumbnail_url'] = $item->thumbnail;
            } else if (Storage::disk('public')->exists($item->thumbnail)) {
                $result['thumbnail_url'] = Storage::disk('public')->url($item->thumbnail);
            } else {
                $result['thumbnail_url'] = asset('images/video-default.jpg');
            }
        } else {
            $result['thumbnail_url'] = asset('images/video-default.jpg');
        }

        return $result;
    }

    /**
     * Extract YouTube ID from URL
     */
    private function getYoutubeIdFromUrl(string $url): ?string
    {
        $patterns = [
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i',
            '%^https?://(?:www\.)?youtube\.com/watch\?v=([\w-]{11})%',
            '%^https?://youtu\.be/([\w-]{11})%'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * ✅ NEW: Add missing scope method for Gallery model compatibility
     */
    public function scopeForPublic($query)
    {
        return $query->where('is_active', true)
            ->whereHas('hostel', function ($q) {
                $q->where('is_published', true);
            });
    }
}
