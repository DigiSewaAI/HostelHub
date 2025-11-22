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

    // ✅ FIXED: Public hostel listing routes - ADDED BOTH ROUTE NAMES
    // Route for 'all.hostels' (used in search-results.blade.php)
    Route::get('/hostels', [PublicController::class, 'allHostels'])->name('all.hostels');

    // Route for 'hostels.index' (used in dark.blade.php) - same URI, different name
    Route::get('/hostels', [PublicController::class, 'allHostels'])->name('hostels.index');

    Route::get('/hostels/{slug}', [PublicController::class, 'hostelShow'])->name('hostels.show');

    // ✅ FIXED: Single search route for hostel search functionality (GET method)
    Route::get('/search', [PublicController::class, 'search'])->name('search');

    // Public hostel gallery routes
    Route::get('/hostel/{slug}/gallery', [PublicController::class, 'hostelGallery'])->name('hostel.gallery');
    Route::get('/hostel/{slug}/full-gallery', [PublicController::class, 'hostelFullGallery'])->name('hostel.full-gallery');

    // Meal gallery route
    Route::get('/menu-gallery', [MealGalleryController::class, 'index'])->name('menu-gallery');

    // ✅ FIXED: Updated booking routes to use new booking system
    // Book room route - NEW CORRECTED ROUTES
    Route::get('/hostel/{slug}/book', [PublicController::class, 'bookForm'])->name('hostel.book');
    Route::post('/hostel/{slug}/book', [PublicController::class, 'storeBooking'])->name('hostel.book.store');
    Route::get('/booking/success/{id}', [PublicController::class, 'bookingSuccess'])->name('booking.success');

    // Keep old route for backward compatibility but redirect to new one
    Route::get('/hostel/{slug}/book-room', function ($slug) {
        return redirect()->route('hostel.book', $slug);
    })->name('hostel.book-room');

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

    // ✅ FIXED: Removed duplicate search routes - consolidated into single search route above
    // Room search functionality (if needed separately from general search)
    Route::get('/rooms', [PublicController::class, 'roomSearch'])->name('rooms.search');
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
|--------------------------------------------------------------------------*/
Route::get('/register/organization/{plan?}', [RegistrationController::class, 'show'])->name('register.organization');
Route::post('/register/organization', [RegistrationController::class, 'store'])->name('register.organization.store');

/*|--------------------------------------------------------------------------
| Clean File - No Duplicate Routes
|--------------------------------------------------------------------------*/