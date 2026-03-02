<?php

use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendBirthdayNotificationsJob;
use Illuminate\Support\Facades\Log;

// Publish scheduled circulars every minute
Schedule::command('circulars:publish')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

// Archive expired circulars every minute
Schedule::command('circulars:archive-expired')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

// Gallery cache refresh every hour
Schedule::command('gallery:refresh')->hourly();

// Clear gallery cache daily at 2 AM
Schedule::command('gallery:clear-cache')->dailyAt('02:00');

// Birthday notification job daily at 12:05 AM
Schedule::job(new SendBirthdayNotificationsJob)->dailyAt('00:05');

// Test closure every minute
Schedule::call(function () {
    Log::info('Test schedule running at ' . now());
})->everyMinute();
