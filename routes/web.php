<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\ContactController as PublicContactController;
use App\Http\Controllers\GalleryController as PublicGalleryController;
use App\Http\Controllers\MealController as PublicMealController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RoomController as PublicRoomController;
use App\Http\Controllers\StudentController as PublicStudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Enjoy building your web!
|
*/

// 🌐 Public Website Routes (सार्वजनिक पृष्ठहरू)
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms');
Route::get('/rooms/{room}', [PublicRoomController::class, 'show'])->name('rooms.show');
Route::get('/meals', [PublicMealController::class, 'publicIndex'])->name('meals');
Route::get('/gallery', [PublicGalleryController::class, 'publicIndex'])->name('gallery');
Route::get('/gallery/{gallery}', [PublicGalleryController::class, 'publicShow'])->name('gallery.show');
Route::get('/students', [PublicStudentController::class, 'index'])->name('students'); // Public student list
Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

// 🔐 Custom Authentication Routes (लगइन/रजिस्टर - कस्टम AuthController प्रयोग गर्दै)
// NOTE: Laravel को डिफल्ट auth.php हटाइएको छ, किनकि हामी कस्टम routes प्रयोग गर्दैछौं
Route::get('/signup', [AuthController::class, 'showRegistrationForm'])->name('signup');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// यदि आवश्यक छ भने login/signup पोस्ट रूटहरू पनि थप्नुहोस्
Route::post('/signup', [AuthController::class, 'register'])->name('signup.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 👤 User Profile Routes (प्रोफाइल पृष्ठहरू)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🚪 Dashboard Redirect (सार्वजनिक dashboard लाई admin मा redirect गर्ने)
Route::middleware(['auth', 'verified'])
    ->get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

// 👑 Admin Routes (प्रशासन पृष्ठहरू)
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // 📊 Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
             ->name('dashboard');

        // 👨‍🎓 Student Management
        Route::get('students/export', [AdminStudentController::class, 'export'])
             ->name('students.export')
             ->middleware('permission:export-students');
        Route::resource('students', AdminStudentController::class)
             ->middleware('permission:manage-students');

        // 🛏️ Room Management
        Route::resource('rooms', RoomController::class)
             ->middleware('permission:manage-rooms');

        // 🍽️ Meal Management
        Route::resource('meals', MealController::class)
             ->middleware('permission:manage-meals');

        // 🖼️ Gallery Management
        Route::resource('gallery', GalleryController::class)
             ->except('show') // Public show route already exists
             ->middleware('permission:manage-gallery');

        // 📬 Contact Management (Admin side)
        Route::resource('contacts', AdminContactController::class)
             ->only(['index', 'show', 'destroy'])
             ->middleware('permission:manage-contacts');
    });
