<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display the main gallery page
     */
    public function index()
    {
        $galleryItems = [
            [
                'media_type' => 'image',
                'url' => asset('storage/gallery/room1.jpg'),
                'thumbnail_url' => asset('storage/gallery/room1-thumb.jpg'),
                'title' => 'सफा कोठा',
                'description' => 'आरामदायी र सफा कोठा',
                'category' => '१ सिटर कोठा',
                'date' => '2025-10-24'
            ],
            [
                'media_type' => 'video',
                'url' => 'https://youtube.com/watch?v=abc123',
                'thumbnail_url' => asset('storage/gallery/video1-thumb.jpg'),
                'youtube_id' => 'abc123',
                'title' => 'होस्टल भिडियो टुर',
                'description' => 'हाम्रो होस्टलको भिडियो टुर',
                'category' => 'भिडियो टुर',
                'date' => '2025-10-24'
            ],
            // ... more items
        ];

        return view('frontend.gallery.index', compact('galleryItems'));
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
     * API: Get gallery data
     */
    public function getGalleryData()
    {
        // Get sample gallery data - replace with your actual data source
        $galleryItems = Cache::remember('gallery_items', 3600, function () {
            return Gallery::where('is_active', true)
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return $this->formatGalleryItem($item);
                })
                ->toArray();
        });

        // Fallback sample data if no items found
        if (empty($galleryItems)) {
            $galleryItems = $this->getSampleGalleryData();
        }

        return response()->json($galleryItems);
    }

    /**
     * Format gallery item for API response
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
                'category' => '1 seater',
                'media_type' => 'photo',
                'file_url' => asset('images/sample-room-1.jpg'),
                'thumbnail_url' => asset('images/sample-room-1-thumb.jpg'),
                'created_at' => '2024-01-15'
            ],
            [
                'id' => 2,
                'title' => 'होस्टल भिडियो टुर',
                'description' => 'हाम्रो होस्टलको पूर्ण भिडियो टुर',
                'category' => 'video',
                'media_type' => 'external_video',
                'external_link' => 'https://www.youtube.com/embed/sample-video',
                'thumbnail_url' => asset('images/video-thumbnail.jpg'),
                'created_at' => '2024-01-10'
            ],
            // Add more sample items as needed
        ];
    }

    /**
     * Display the public gallery for a specific hostel
     */
    public function show($slug)
    {
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
            ->get();

        \Log::info('Gallery items count:', ['count' => $galleries->count()]);
        \Log::info('Gallery items:', $galleries->toArray());
        \Log::info('=== GALLERY DEBUG END ===');

        return view('public.hostels.gallery', compact('hostel', 'galleries'));
    }

    /**
     * API Endpoint: Get gallery data for a hostel
     */
    public function getHostelGalleryData($slug)
    {
        $hostel = Hostel::where('slug', $slug)->firstOrFail();

        $galleries = Gallery::where('hostel_id', $hostel->id)
            ->where('is_active', true)
            ->get()
            ->map(function ($item) {
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
                ];
            });

        return response()->json($galleries);
    }

    /**
     * Helper method to get categories list
     */
    private function getCategoriesList()
    {
        return [
            'all'       => 'सबै',
            '1 seater'  => '१ सिटर कोठा',
            '2 seater'  => '२ सिटर कोठा',
            '3 seater'  => '३ सिटर कोठा',
            '4 seater'  => '४ सिटर कोठा',
            'common'    => 'लिभिङ रूम',
            'bathroom'  => 'बाथरूम',
            'kitchen'   => 'भान्सा',
            'study room' => 'अध्ययन कोठा',
            'event'     => 'कार्यक्रम',
            'video'     => 'भिडियो टुर',
        ];
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
}
