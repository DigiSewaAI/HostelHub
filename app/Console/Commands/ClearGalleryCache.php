<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GalleryCacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class ClearGalleryCache extends Command
{
    protected $signature = 'gallery:clear-cache';
    protected $description = 'Clear all gallery cache including dynamic gallery cache';

    public function handle()
    {
        $this->info('Starting gallery cache clearance...');

        // 1. Clear dynamic gallery cache (new system)
        $this->clearDynamicGalleryCache();

        // 2. Clear old gallery cache via service (existing system)
        $service = new GalleryCacheService();

        // Cache stats before clear
        $beforeStats = $service->getCacheStats();

        $this->info('Cache before clearance:');
        $this->table(
            ['Cache Type', 'Status'],
            [
                ['Public Galleries', $beforeStats['public_galleries']],
                ['Featured Galleries', $beforeStats['featured_galleries']],
                ['Recent Galleries', $beforeStats['recent_galleries']],
                ['Cached Hostels', $beforeStats['cached_hostels'] . '/' . $beforeStats['total_hostels']],
                ['Dynamic Gallery Cache', $this->getDynamicCacheStats()],
            ]
        );

        // Cache clear garne
        $service->clearCache();

        // Refresh dynamic gallery cache after clearing
        Artisan::call('gallery:refresh');

        // Cache stats after clear
        $afterStats = $service->getCacheStats();

        $this->info('Cache after clearance:');
        $this->table(
            ['Cache Type', 'Status'],
            [
                ['Public Galleries', $afterStats['public_galleries']],
                ['Featured Galleries', $afterStats['featured_galleries']],
                ['Recent Galleries', $afterStats['recent_galleries']],
                ['Cached Hostels', $afterStats['cached_hostels'] . '/' . $afterStats['total_hostels']],
                ['Dynamic Gallery Cache', 'Refreshed'],
            ]
        );

        $this->info('✅ All gallery cache cleared and refreshed successfully!');
    }

    /**
     * Clear dynamic gallery cache (new system)
     */
    private function clearDynamicGalleryCache()
    {
        $this->info('Clearing dynamic gallery cache...');

        // Clear current and previous hour cache
        $currentHour = now()->format('Y-m-d_H');
        $previousHour = now()->subHour()->format('Y-m-d_H');

        Cache::forget('dynamic_gallery_items_' . $currentHour);
        Cache::forget('dynamic_gallery_items_' . $previousHour);

        // Clear old cache keys (older than 24 hours)
        $deletedCount = 0;
        for ($i = 24; $i <= 72; $i++) {
            $oldKey = 'dynamic_gallery_items_' . now()->subHours($i)->format('Y-m-d_H');
            if (Cache::forget($oldKey)) {
                $deletedCount++;
            }
        }

        $this->info("✅ Cleared {$deletedCount} old dynamic gallery cache keys.");
    }

    /**
     * Get dynamic cache statistics
     */
    private function getDynamicCacheStats(): string
    {
        $currentHour = now()->format('Y-m-d_H');
        $hasCache = Cache::has('dynamic_gallery_items_' . $currentHour) ? 'Yes' : 'No';

        // Count old cache keys
        $oldKeys = 0;
        for ($i = 1; $i <= 24; $i++) {
            $key = 'dynamic_gallery_items_' . now()->subHours($i)->format('Y-m-d_H');
            if (Cache::has($key)) {
                $oldKeys++;
            }
        }

        return "Current: {$hasCache}, Old keys: {$oldKeys}";
    }
}
