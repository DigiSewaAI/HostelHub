<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Gallery;
use App\Models\Hostel;

class GalleryCacheService
{
    const CACHE_DURATION = 3600; // 1 hour

    /**
     * Public galleries with hostel names cache ma lyaune
     */
    public function getPublicGalleries()
    {
        return Cache::remember('public_galleries_with_hostels', self::CACHE_DURATION, function () {
            return Gallery::with(['hostel', 'room.hostel'])
                ->where('is_active', true)
                ->whereHas('hostel', function ($query) {
                    $query->where('is_published', true);
                })
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        });
    }

    /**
     * Specific hostel ko galleries cache ma lyaune
     */
    public function getHostelGalleries($hostelId)
    {
        return Cache::remember("hostel_galleries_{$hostelId}", self::CACHE_DURATION, function () use ($hostelId) {
            return Gallery::with(['hostel', 'room'])
                ->where('hostel_id', $hostelId)
                ->where('is_active', true)
                ->get();
        });
    }

    /**
     * Featured galleries cache ma lyaune
     */
    public function getFeaturedGalleries()
    {
        return Cache::remember('featured_galleries_with_hostels', self::CACHE_DURATION, function () {
            return Gallery::with(['hostel', 'room.hostel'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->whereHas('hostel', function ($query) {
                    $query->where('is_published', true);
                })
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        });
    }

    /**
     * Recent galleries cache ma lyaune
     */
    public function getRecentGalleries()
    {
        return Cache::remember('recent_galleries_with_hostels', self::CACHE_DURATION, function () {
            return Gallery::with(['hostel', 'room.hostel'])
                ->where('is_active', true)
                ->whereHas('hostel', function ($query) {
                    $query->where('is_published', true);
                })
                ->orderBy('created_at', 'desc')
                ->take(15)
                ->get();
        });
    }

    /**
     * Sabai gallery cache clear garne
     */
    public function clearCache(): void
    {
        Cache::forget('public_galleries_with_hostels');
        Cache::forget('featured_galleries_with_hostels');
        Cache::forget('recent_galleries_with_hostels');

        // Sabai hostel-specific cache clear garne
        $hostelIds = Hostel::pluck('id');
        foreach ($hostelIds as $hostelId) {
            Cache::forget("hostel_galleries_{$hostelId}");
        }
    }

    /**
     * Cache statistics herne
     */
    public function getCacheStats(): array
    {
        return [
            'public_galleries' => Cache::has('public_galleries_with_hostels') ? 'Cached' : 'Not Cached',
            'featured_galleries' => Cache::has('featured_galleries_with_hostels') ? 'Cached' : 'Not Cached',
            'recent_galleries' => Cache::has('recent_galleries_with_hostels') ? 'Cached' : 'Not Cached',
            'total_hostels' => Hostel::count(),
            'cached_hostels' => $this->getCachedHostelCount()
        ];
    }

    /**
     * Kati ota hostel ko cache cha count garne
     */
    private function getCachedHostelCount(): int
    {
        $count = 0;
        $hostelIds = Hostel::pluck('id');

        foreach ($hostelIds as $hostelId) {
            if (Cache::has("hostel_galleries_{$hostelId}")) {
                $count++;
            }
        }

        return $count;
    }
}
