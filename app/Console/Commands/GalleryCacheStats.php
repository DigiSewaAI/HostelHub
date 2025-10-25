<?php
// app/Console/Commands/GalleryCacheStats.php - NEW FILE CREATE GARNE

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GalleryCacheService;

class GalleryCacheStats extends Command
{
    protected $signature = 'gallery:cache-stats';
    protected $description = 'Show gallery cache statistics';

    public function handle()
    {
        $service = new GalleryCacheService();
        $stats = $service->getCacheStats();

        $this->info('📊 Gallery Cache Statistics:');
        $this->table(
            ['Cache Type', 'Status'],
            [
                ['Public Galleries', $stats['public_galleries']],
                ['Featured Galleries', $stats['featured_galleries']],
                ['Recent Galleries', $stats['recent_galleries']],
                ['Cached Hostels', $stats['cached_hostels'] . '/' . $stats['total_hostels']]
            ]
        );

        $this->info('Available Commands:');
        $this->info('  php artisan gallery:clear-cache    - Clear all gallery cache');
        $this->info('  php artisan gallery:cache-stats    - Show cache statistics');
        $this->info('  php artisan gallery:sync-room-images - Sync room images to gallery');
    }
}
