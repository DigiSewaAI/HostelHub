<?php

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/*|--------------------------------------------------------------------------
| Debug Routes - Permission Check
|--------------------------------------------------------------------------
*/
Route::get('/debug-permissions', function () {
    $user = auth()->user();

    if (!$user) {
        return "No user logged in";
    }

    return [
        'user_id' => $user->id,
        'user_name' => $user->name,
        'email' => $user->email,
        'roles' => $user->getRoleNames(),
        'permissions' => $user->getAllPermissions()->pluck('name'),
        'can_payment_view' => $user->can('payment.view') ? 'YES' : 'NO',
        'is_admin' => $user->hasRole('admin') ? 'YES' : 'NO',
        'is_hostel_manager' => $user->hasRole('hostel_manager') ? 'YES' : 'NO',
        'is_owner' => $user->hasRole('owner') ? 'YES' : 'NO'
    ];
})->middleware('auth');

Route::get('/assign-role/{role}', function ($role) {
    $user = auth()->user();

    if (!$user) {
        return "No user logged in";
    }

    // Valid roles check
    $validRoles = ['admin', 'hostel_manager', 'student', 'owner'];
    if (!in_array($role, $validRoles)) {
        return "Invalid role. Use: " . implode(', ', $validRoles);
    }

    $user->syncRoles([$role]);

    return [
        'message' => "Role {$role} assigned to user {$user->name}",
        'current_roles' => $user->getRoleNames(),
        'permissions' => $user->getAllPermissions()->pluck('name')
    ];
})->middleware('auth');

/*|--------------------------------------------------------------------------
| Development Routes - Room Counts Fix
|--------------------------------------------------------------------------
*/

// Temporary routes for fixing room counts
Route::get('/fix-hostel-counts', function () {
    $hostels = \App\Models\Hostel::all();

    foreach ($hostels as $hostel) {
        $totalRooms = $hostel->rooms()->count();
        $availableRooms = $hostel->rooms()->where('status', 'available')->count();

        echo "Hostel: {$hostel->name} <br>";
        echo "Database - Total: {$hostel->total_rooms}, Available: {$hostel->available_rooms} <br>";
        echo "Actual - Total: {$totalRooms}, Available: {$availableRooms} <br>";

        // Auto-update
        $hostel->update([
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms
        ]);

        echo "<strong>UPDATED - Total: {$totalRooms}, Available: {$availableRooms}</strong> <br>";
        echo "---<br>";
    }

    return "<h3>All hostel room counts updated successfully!</h3>";
});

// Check duplicate hostels
Route::get('/check-duplicates', function () {
    $duplicates = \App\Models\Hostel::select('name', DB::raw('COUNT(*) as count'))
        ->groupBy('name')
        ->having('count', '>', 1)
        ->get();

    echo "<h3>Duplicate Hostels:</h3>";
    foreach ($duplicates as $dup) {
        echo "Name: {$dup->name} - Count: {$dup->count} <br>";

        $hostels = \App\Models\Hostel::where('name', $dup->name)->get();
        foreach ($hostels as $hostel) {
            $roomCount = $hostel->rooms()->count();
            $studentCount = $hostel->students()->count();
            echo "&nbsp;&nbsp;- ID: {$hostel->id}, Rooms: {$roomCount}, Students: {$studentCount} <br>";
        }
        echo "---<br>";
    }

    return "<h3>Duplicate check completed!</h3>";
});

// Temporary debug route for hostel preview
Route::get('/debug-hostel/{slug}', function ($slug) {
    $hostel = \App\Models\Hostel::with(['images', 'rooms', 'mealMenus'])->where('slug', $slug)->first();

    if (!$hostel) {
        return "Hostel not found with slug: {$slug}";
    }

    return [
        'hostel_data' => [
            'id' => $hostel->id,
            'name' => $hostel->name,
            'slug' => $hostel->slug,
            'description' => $hostel->description,
            'theme_color' => $hostel->theme_color,
            'logo_path' => $hostel->logo_path,
            'is_published' => $hostel->is_published,
            'organization_id' => $hostel->organization_id,
        ],
        'relationships' => [
            'images_count' => $hostel->images->count(),
            'rooms_count' => $hostel->rooms->count(),
            'mealMenus_count' => $hostel->mealMenus->count(),
        ],
        'session_data' => [
            'current_organization_id' => session('current_organization_id'),
        ]
    ];
});

// Database check and update routes for hostel data
Route::get('/check-hostel-data/{slug}', function ($slug) {
    $hostel = \App\Models\Hostel::where('slug', $slug)->first();

    if (!$hostel) {
        return "Hostel not found!";
    }

    // Check database directly
    $dbData = \DB::table('hostels')->where('slug', $slug)->first();

    return [
        'database_data' => $dbData,
        'model_data' => [
            'name' => $hostel->name,
            'description' => $hostel->description,
            'theme_color' => $hostel->theme_color,
            'logo_path' => $hostel->logo_path,
        ]
    ];
});

// Update hostel data temporarily
Route::get('/update-hostel-data/{slug}', function ($slug) {
    $hostel = \App\Models\Hostel::where('slug', $slug)->first();

    if (!$hostel) {
        return "Hostel not found!";
    }

    // Update with sample data
    $hostel->update([
        'description' => 'यो Sanctuary Girls Hostel को विवरण हो। हामी विद्यार्थीहरूको लागि उत्कृष्ट र सुरक्षित बसाइ सुनिश्चित गर्दछौं।',
        'theme_color' => '#10b981', // green color
        'logo_path' => 'hostels/logos/sanctuary1.jpg'
    ]);

    return "Hostel data updated! Check: " . route('debug-hostel', $slug);
});

// Debug route for admin routes testing
Route::get('/debug-admin-routes', function () {
    $user = auth()->user();
    $routes = [
        'admin.rooms.create' => route('admin.rooms.create'),
        'admin.rooms.store' => route('admin.rooms.store'),
        'admin.rooms.index' => route('admin.rooms.index'),
    ];

    return [
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'roles' => $user->getRoleNames()->toArray(),
        ],
        'routes' => $routes,
        'route_exists' => Route::has('admin.rooms.create'),
    ];
})->middleware(['auth', 'role:admin']);

/*|--------------------------------------------------------------------------
| Development Routes
|--------------------------------------------------------------------------
*/
Route::get('/test-route', function () {
    return 'Test route';
})->name('test.route');

Route::get('/test-pdf', function () {
    $pdf = Pdf::loadHTML('<h1>Test PDF</h1><p>This is working!</p>');
    return $pdf->download('test.pdf');
});

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection is working.';
    } catch (\Exception $e) {
        return 'Database connection failed: ' . $e->getMessage();
    }
});

Route::get('/test-roles', function () {
    $user = auth()->user();
    if ($user) {
        return [
            'user_id' => $user->id,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name')
        ];
    }
    return 'No user logged in';
})->middleware('auth');

// Dashboard cache testing route
Route::get('/test-dashboard-cache', function () {
    $userId = auth()->id();
    $cached = Cache::get('admin_dashboard_metrics_' . $userId);
    return [
        'cached' => $cached ? true : false,
        'user_id' => $userId,
        'cache_key' => 'admin_dashboard_metrics_' . $userId
    ];
})->middleware('auth');

// Debug route for gallery testing with column fix
Route::get('/debug-gallery/{slug}', function ($slug) {
    // Check what columns exist in galleries table
    $columns = \DB::getSchemaBuilder()->getColumnListing('galleries');

    $hostel = \App\Models\Hostel::with(['galleries' => function ($query) use ($columns) {
        // Check which status column exists
        if (in_array('is_active', $columns)) {
            $query->where('is_active', true);
        } else if (in_array('status', $columns)) {
            $query->where('status', 'active');
        }
        // If neither exists, get all galleries
    }])->where('slug', $slug)->first();

    if (!$hostel) {
        return "Hostel not found with slug: {$slug}";
    }

    return [
        'galleries_table_columns' => $columns,
        'hostel' => [
            'id' => $hostel->id,
            'name' => $hostel->name,
            'slug' => $hostel->slug,
            'is_published' => $hostel->is_published,
        ],
        'galleries' => $hostel->galleries->map(function ($gallery) {
            return [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'media_type' => $gallery->media_type,
                'file_path' => $gallery->file_path,
                'external_link' => $gallery->external_link,
                'category' => $gallery->category,
                'is_active' => $gallery->is_active ?? 'N/A',
                'status' => $gallery->status ?? 'N/A',
            ];
        }),
        'gallery_count' => $hostel->galleries->count(),
        'route_exists' => \Route::has('hostel.gallery'),
        'route_url' => route('hostel.gallery', $slug),
    ];
});

// Temporary route to publish hostel for testing
Route::get('/publish-hostel/{slug}', function ($slug) {
    $hostel = \App\Models\Hostel::where('slug', $slug)->first();

    if (!$hostel) {
        return "Hostel not found with slug: {$slug}";
    }

    $hostel->update(['is_published' => true]);

    return [
        'message' => "Hostel '{$hostel->name}' has been published!",
        'hostel' => [
            'id' => $hostel->id,
            'name' => $hostel->name,
            'slug' => $hostel->slug,
            'is_published' => $hostel->is_published
        ],
        'gallery_url' => route('hostel.gallery', $slug)
    ];
});

// Debug view path
Route::get('/debug-view-path', function () {
    $viewPaths = [
        'public.hostels.gallery' => view()->exists('public.hostels.gallery'),
        'frontend.hostels.gallery' => view()->exists('frontend.hostels.gallery'),
        'public.hostels.gallery.blade' => view()->exists('public.hostels.gallery.blade'),
    ];

    return [
        'view_exists' => $viewPaths,
        'views_directory' => base_path('resources/views'),
        'public_hostels_path' => is_dir(base_path('resources/views/public/hostels')) ? 'Exists' : 'Not found',
        'gallery_file' => file_exists(base_path('resources/views/public/hostels/gallery.blade.php')) ? 'Exists' : 'Not found'
    ];
});

// Check gallery table structure
Route::get('/check-gallery-structure', function () {
    $columns = \DB::getSchemaBuilder()->getColumnListing('galleries');

    $sampleGallery = \App\Models\Gallery::first();

    return [
        'columns_in_galleries_table' => $columns,
        'sample_gallery' => $sampleGallery ? $sampleGallery->toArray() : 'No galleries found',
        'total_galleries' => \App\Models\Gallery::count()
    ];
});

// Temporary debug route - remove after testing
Route::get('/debug-hostel-data/{slug}', function ($slug) {
    $hostel = \App\Models\Hostel::where('slug', $slug)->first();

    if (!$hostel) {
        return "Hostel not found";
    }

    return [
        'hostel_name' => $hostel->name,
        'logo_path' => $hostel->logo_path,
        'logo_path_raw' => $hostel->getRawOriginal('logo_path'),
        'facilities' => $hostel->facilities,
        'facilities_raw' => $hostel->getRawOriginal('facilities'),
        'facilities_type' => gettype($hostel->facilities),
        'storage_exists' => $hostel->logo_path ? \Storage::disk('public')->exists($hostel->logo_path) : false,
        'storage_files' => $hostel->logo_path ? \Storage::disk('public')->files(dirname($hostel->logo_path)) : [],
    ];
});

// Test admin rooms create
Route::get('/test-admin-rooms-create', function () {
    $user = auth()->user();

    if (!$user) {
        return "Not authenticated";
    }

    if (!$user->hasRole('admin')) {
        return "Not an admin user. Roles: " . $user->getRoleNames()->implode(', ');
    }

    // Test if we can access the controller method directly
    try {
        $controller = new \App\Http\Controllers\Admin\RoomController();
        $hostels = \App\Models\Hostel::all();
        return "Controller accessible. Hostels count: " . $hostels->count();
    } catch (\Exception $e) {
        return "Controller error: " . $e->getMessage();
    }
})->middleware(['auth', 'hasOrganization', 'role:admin']);

// Temporary test route - completely open
Route::post('/test-hostel-update/{id}', function (Request $request, $id) {
    \Log::info("=== TEST UPDATE ROUTE HIT ===", [
        'hostel_id' => $id,
        'user_id' => auth()->id(),
        'all_data' => $request->all()
    ]);

    $hostel = \App\Models\Hostel::find($id);
    if ($hostel) {
        $hostel->update($request->all());
        return response()->json(['success' => true, 'message' => 'Test update successful']);
    }

    return response()->json(['success' => false, 'message' => 'Hostel not found']);
});