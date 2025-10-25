<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GalleryCacheService;

class ClearGalleryCache extends Command
{
    protected $signature = 'gallery:clear-cache';
    protected $description = 'Clear all gallery cache';

    public function handle()
    {
        $this->info('Starting gallery cache clearance...');

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
                ['Cached Hostels', $beforeStats['cached_hostels'] . '/' . $beforeStats['total_hostels']]
            ]
        );

        // Cache clear garne
        $service->clearCache();

        // Cache stats after clear
        $afterStats = $service->getCacheStats();

        $this->info('Cache after clearance:');
        $this->table(
            ['Cache Type', 'Status'],
            [
                ['Public Galleries', $afterStats['public_galleries']],
                ['Featured Galleries', $afterStats['featured_galleries']],
                ['Recent Galleries', $afterStats['recent_galleries']],
                ['Cached Hostels', $afterStats['cached_hostels'] . '/' . $afterStats['total_hostels']]
            ]
        );

        $this->info('✅ Gallery cache cleared successfully!');
    }
}
