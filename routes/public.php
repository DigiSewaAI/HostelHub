<?php

use App\Http\Controllers\{
    Frontend\GalleryController as FrontendGalleryController,
    Frontend\PublicContactController,
    Frontend\PublicController,
    Frontend\PricingController,
    Frontend\MealGalleryController,
    Frontend\ReviewController as FrontendReviewController,
    Owner\OwnerPublicPageController,
    BookingController,
    RegistrationController
};

/*|--------------------------------------------------------------------------
| Public Routes (Marketing Site - Homepage, Features, Pricing, etc.)
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'web'], function () {
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/about', [PublicController::class, 'about'])->name('about');
    Route::get('/features', [PublicController::class, 'features'])->name('features');
    Route::get('/how-it-works', [PublicController::class, 'howItWorks'])->name('how-it-works');
    Route::get('/gallery', [FrontendGalleryController::class, 'index'])->name('gallery');
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

    // Public hostel listing routes
    Route::get('/hostels', [PublicController::class, 'hostelsIndex'])->name('hostels.index');
    Route::get('/hostels/{slug}', [PublicController::class, 'hostelShow'])->name('hostels.show');

    // Public hostel gallery routes
    Route::get('/hostel/{slug}/gallery', [PublicController::class, 'hostelGallery'])->name('hostel.gallery');
    Route::get('/hostel/{slug}/full-gallery', [PublicController::class, 'hostelFullGallery'])->name('hostel.full-gallery');


    Route::get('/menu-gallery', [MealGalleryController::class, 'index'])->name('menu-gallery');


    // Book room route
    Route::get('/hostel/{slug}/book-room', [BookingController::class, 'create'])->name('hostel.book-room');

    // Hostel contact route
    Route::post('/hostels/{hostel}/contact', [PublicController::class, 'hostelContact'])->name('hostel.contact');

    // Hostel preview route
    Route::get('/preview/{slug}', [OwnerPublicPageController::class, 'preview'])->name('hostels.preview');

    // Gallery API Routes
    Route::get('/api/gallery/data', [FrontendGalleryController::class, 'getGalleryData']);
    Route::get('/api/gallery/categories', [FrontendGalleryController::class, 'getGalleryCategories']);
    Route::get('/api/gallery/stats', [FrontendGalleryController::class, 'getGalleryStats']);

    // Public testimonials
    Route::get('/reviews', [FrontendReviewController::class, 'index'])->name('reviews');
    Route::get('/testimonials', [FrontendReviewController::class, 'index'])->name('testimonials');

    // Legal pages routes
    Route::get('/privacy-policy', [PublicController::class, 'privacy'])->name('privacy');
    Route::get('/terms-of-service', [PublicController::class, 'terms'])->name('terms');
    Route::get('/cookies', [PublicController::class, 'cookies'])->name('cookies');

    // Contact routes
    Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
    Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

    // Search functionality
    Route::get('/rooms', [PublicController::class, 'roomSearch'])->name('rooms.search');
    Route::post('/rooms/search', [PublicController::class, 'searchRooms'])->name('rooms.search.post');
    Route::get('/hostels/search', [PublicController::class, 'hostelSearch'])->name('hostels.search');

    // Demo route
    Route::get('/demo', function () {
        return view('frontend.pages.demo');
    })->name('demo');

    // Newsletter subscription
    Route::post('/newsletter/subscribe', [PublicController::class, 'subscribeNewsletter'])
        ->name('newsletter.subscribe');
});

/*|--------------------------------------------------------------------------
| Organization Registration Routes (Accessible by all)
|--------------------------------------------------------------------------
*/
Route::get('/register/organization/{plan?}', [RegistrationController::class, 'show'])->name('register.organization');
Route::post('/register/organization', [RegistrationController::class, 'store'])->name('register.organization.store');

/*|--------------------------------------------------------------------------
| Global Search Route
|--------------------------------------------------------------------------
*/
Route::post('/search', [PublicController::class, 'search'])->name('search');
