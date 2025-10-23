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
    public function getGalleryData($slug)
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
