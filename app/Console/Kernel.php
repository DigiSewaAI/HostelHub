<?php

namespace App\Console;

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
        \App\Console\Commands\DeployDatabase::class,  // ✅ Full namespace

        // ✅ NEW: Circular commands
        \App\Console\Commands\PublishScheduledCirculars::class,
        \App\Console\Commands\ArchiveExpiredCirculars::class,
        \App\Console\Commands\UpdateRoomIssueNotificationsAvatar::class,
    ];

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
