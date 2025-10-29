<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Frontend\PricingController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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
Route::get('/register/organization', [RegisterController::class, 'showOrganizationRegistrationForm'])->name('register.organization');

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
