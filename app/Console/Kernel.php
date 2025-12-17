<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Existing commands
        \App\Console\Commands\SyncRoomsOccupancy::class,
        \App\Console\Commands\ClearGalleryCache::class,

        // ✅ NEW: Add RefreshGalleryCache command
        \App\Console\Commands\RefreshGalleryCache::class,

        Commands\DeployDatabase::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // ... यहाँ तपाईंका अरु existing schedule commands हुन सक्छन् ...

        // ✅ NEW: Dynamic gallery cache management
        // Refresh gallery cache every hour at minute 0
        $schedule->command('gallery:refresh')->hourly();

        // Clear old gallery cache daily at 2:00 AM
        $schedule->command('gallery:clear-cache')->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
