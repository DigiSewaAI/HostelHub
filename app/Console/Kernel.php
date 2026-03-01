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
        \App\Console\Commands\RefreshGalleryCache::class,
        Commands\DeployDatabase::class,

        // ✅ NEW: Circular commands
        \App\Console\Commands\PublishScheduledCirculars::class,
        \App\Console\Commands\ArchiveExpiredCirculars::class,
        \App\Console\Commands\UpdateRoomIssueNotificationsAvatar::class,

    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // ✅ NEW: Publish scheduled circulars every minute
        $schedule->command('circulars:publish')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

        // ✅ NEW: Archive expired circulars every minute
        $schedule->command('circulars:archive-expired')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

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
