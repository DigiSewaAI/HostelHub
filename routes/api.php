<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes  
|--------------------------------------------------------------------------
|
| This project currently doesn't use API routes as it's a web-based
| application. All routes are defined in web.php.
|
| If API functionality is needed in the future (for mobile app, 
| external integrations, etc.), routes can be added here.
|
*/

// ✅ NEW: API route for contact counts (for real-time notifications)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard/contacts-count', [DashboardController::class, 'getContactCounts']);
});
