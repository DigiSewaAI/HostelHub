<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

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
        \App\Console\Commands\DeployDatabase::class,

        // Circular commands
        \App\Console\Commands\PublishScheduledCirculars::class,
        \App\Console\Commands\ArchiveExpiredCirculars::class,
        \App\Console\Commands\UpdateRoomIssueNotificationsAvatar::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ✅ हरेक दिन बिहान ८ बजे birthday notification job चलाउने
        $schedule->job(new \App\Jobs\SendBirthdayNotificationsJob())
            ->everyMinute()
            ->withoutOverlapping() // एकै पटक धेरै पटक नचलोस्
            ->appendOutputTo(storage_path('logs/birthday-notifications.log')); // log हेर्न मिलोस्

        // तपाईंका अन्य scheduled jobs/commands छन् भने यहाँ थप्नुहोस्
        // जस्तै:
        // $schedule->command('circulars:publish')->everyMinute();
        // $schedule->command('circulars:archive')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
