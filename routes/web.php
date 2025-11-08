<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

// Force HTTPS in production
if (app()->environment('production')) {
    URL::forceScheme('https');
}

/*|--------------------------------------------------------------------------
| Emergency Routes - ADD THESE AT THE TOP (500 Error Fix)
|--------------------------------------------------------------------------*/
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showUserRegistrationForm'])->name('register');
// 🚨 REMOVE THIS DUPLICATE LINE:
// Route::get('/register/organization', [RegisterController::class, 'showOrganizationRegistrationForm'])->name('register.organization');

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

// 🚨 EMERGENCY TEST ROUTE
Route::get('/test-hero', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head><title>Test Hero</title></head>
    <body style="margin:0; padding:0;">
        <div style="height:100vh; background:blue; color:white; display:flex; align-items:center; justify-content:center;">
            <h1>🚨 HERO SECTION WORKING!</h1>
        </div>
    </body>
    </html>
    ';
});
