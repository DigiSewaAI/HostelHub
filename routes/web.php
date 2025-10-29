<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

// Force HTTPS in production
if (app()->environment('production')) {
    URL::forceScheme('https');
}

/*|--------------------------------------------------------------------------
| Load Modular Route Files
|--------------------------------------------------------------------------
*/
require __DIR__ . '/public.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/shared.php';

// ✅ Admin routes - Add prefix HERE instead of in admin.php
Route::prefix('admin')->group(function () {
    require __DIR__ . '/admin.php';
});

// ✅ Owner routes - Add prefix HERE instead of in owner.php
Route::prefix('owner')->group(function () {
    require __DIR__ . '/owner.php';
});

// ✅ Student routes - Add prefix HERE instead of in student.php
Route::prefix('student')->group(function () {
    require __DIR__ . '/student.php';
});

/*|--------------------------------------------------------------------------
| Development Routes (Conditionally Loaded)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    require __DIR__ . '/dev.php';
}