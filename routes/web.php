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

/*|--------------------------------------------------------------------------
| Load Modular Route Files - FIXED ORDER with Proper Middleware
|--------------------------------------------------------------------------
*/
require __DIR__ . '/public.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/shared.php';

// ✅ FIXED: Admin routes with proper middleware order
Route::prefix('admin')
    ->middleware(['auth', 'hasOrganization', 'role:admin'])
    ->group(function () {
        require __DIR__ . '/admin.php';
    });

// ✅ FIXED: Owner routes with proper middleware order
Route::prefix('owner')
    ->middleware(['auth', 'hasOrganization', 'role:owner,hostel_manager'])
    ->group(function () {
        require __DIR__ . '/owner.php';

        // ✅ ADDED: Form reset route for circular form
        Route::post('/clear-form-flag', function () {
            session()->forget(['clear_form', 'success']);
            return response()->json(['success' => true]);
        })->name('owner.clear.form.flag');
    });

// ✅ FIXED: Student routes with proper middleware order
Route::prefix('student')
    ->middleware(['auth', 'role:student'])
    ->group(function () {
        require __DIR__ . '/student.php';
    });

/*|--------------------------------------------------------------------------
| Development Routes (Conditionally Loaded)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    require __DIR__ . '/dev.php';
}

/*|--------------------------------------------------------------------------
| Debug Routes (For Development Only - Remove in Production)
|--------------------------------------------------------------------------*/
Route::middleware(['auth'])->group(function () {
    // Debug route for document issues
    Route::get('/debug-owner-documents', function () {
        $user = Auth::user();

        echo "<h3>User Info:</h3>";
        echo "User ID: " . $user->id . "<br>";
        echo "Name: " . $user->name . "<br>";
        echo "Roles: " . $user->getRoleNames()->implode(', ') . "<br>";

        echo "<h3>Organization Info:</h3>";
        $organization = $user->organizations()->wherePivot('role', 'owner')->first();
        if ($organization) {
            echo "Organization ID: " . $organization->id . "<br>";
            echo "Organization Name: " . $organization->name . "<br>";

            echo "<h3>Hostels in Organization:</h3>";
            $hostels = $organization->hostels;
            foreach ($hostels as $hostel) {
                echo "Hostel ID: " . $hostel->id . " - " . $hostel->name . "<br>";
            }

            $hostelIds = $hostels->pluck('id');
            echo "<h3>Documents in these hostels:</h3>";
            $documents = \App\Models\Document::whereIn('hostel_id', $hostelIds)->get();
            foreach ($documents as $doc) {
                echo "Document ID: " . $doc->id . " - Student: " . ($doc->student->user->name ?? 'N/A') . " - Hostel ID: " . $doc->hostel_id . "<br>";
            }

            echo "<h3>Total documents found: " . $documents->count() . "</h3>";
        } else {
            echo "No organization found!";
        }

        return '';
    });

    // Debug route for document controller logic
    Route::get('/debug-documents-controller', function () {
        $user = Auth::user();

        echo "<h3>Testing DocumentController Logic</h3>";
        echo "User: " . $user->name . " (ID: " . $user->id . ")<br>";
        echo "Roles: " . $user->getRoleNames()->implode(', ') . "<br><br>";

        // DocumentController जस्तै logic
        $query = \App\Models\Document::with(['student.user', 'hostel', 'uploader']);

        if ($user->hasRole('admin')) {
            echo "Admin access<br>";
            $documents = $query->latest();
        } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            echo "Owner access - EMERGENCY MODE: Showing all documents<br>";
            $documents = $query->latest();
        } else {
            echo "Student access<br>";
            $student = $user->student;
            if ($student) {
                $documents = $query->where('student_id', $student->id)->latest();
            } else {
                $documents = $query->where('id', 0);
            }
        }

        $documents = $documents->get();

        echo "<h4>Documents Found:</h4>";
        foreach ($documents as $doc) {
            echo "ID: " . $doc->id . " | ";
            echo "Name: " . $doc->original_name . " | ";
            echo "Hostel: " . ($doc->hostel->name ?? 'N/A') . " | ";
            echo "Student: " . ($doc->student->user->name ?? 'N/A') . "<br>";
        }

        echo "<br><h4>Total: " . $documents->count() . " documents</h4>";

        return '';
    });

    // Debug route for document upload issues
    Route::get('/debug-document-upload', function () {
        $user = Auth::user();

        echo "<h3>Debug Document Upload</h3>";
        echo "User: " . $user->name . "<br>";
        echo "Organization: " . ($user->organizations()->first()->name ?? 'None') . "<br>";

        $students = \App\Models\Student::where('organization_id', $user->organizations()->first()->id ?? 0)->get();
        echo "Students in organization: " . $students->count() . "<br>";

        foreach ($students as $student) {
            echo "Student: " . ($student->user->name ?? 'N/A') . " (ID: " . $student->id . ")<br>";
        }

        return '';
    });

    // ✅ ADDED: Circular System Debug Routes
    Route::get('/debug-circular-system', function () {
        $user = Auth::user();

        echo "<h3>Circular System Debug</h3>";
        echo "User: " . $user->name . " (ID: " . $user->id . ")<br>";
        echo "Roles: " . $user->getRoleNames()->implode(', ') . "<br><br>";

        // Test circular access
        $circulars = \App\Models\Circular::with(['organization', 'recipients'])->get();

        echo "<h4>All Circulars in System:</h4>";
        foreach ($circulars as $circular) {
            echo "Circular ID: " . $circular->id . " | ";
            echo "Title: " . $circular->title . " | ";
            echo "Organization: " . ($circular->organization->name ?? 'N/A') . " | ";
            echo "Recipients: " . $circular->recipients->count() . "<br>";
        }

        echo "<h4>User's Organization:</h4>";
        $organization = $user->organizations()->first();
        if ($organization) {
            echo "Organization ID: " . $organization->id . " - " . $organization->name . "<br>";

            echo "<h4>Circulars in User's Organization:</h4>";
            $orgCirculars = \App\Models\Circular::where('organization_id', $organization->id)->get();
            foreach ($orgCirculars as $circular) {
                echo "Circular ID: " . $circular->id . " - " . $circular->title . "<br>";
            }
        }

        return '';
    });

    // ✅ ADDED: Circular Permission Debug Route
    Route::get('/debug-circular-permissions', function () {
        $user = Auth::user();

        echo "<h3>Circular Permissions Debug</h3>";
        echo "User: " . $user->name . "<br>";
        echo "Roles: " . $user->getRoleNames()->implode(', ') . "<br><br>";

        // Test Gates
        echo "<h4>Gate Permissions:</h4>";
        echo "access_circulars: " . (Gate::allows('access_circulars') ? 'YES' : 'NO') . "<br>";
        echo "create_circulars: " . (Gate::allows('create_circulars') ? 'YES' : 'NO') . "<br>";
        echo "edit_circulars: " . (Gate::allows('edit_circulars') ? 'YES' : 'NO') . "<br>";
        echo "delete_circulars: " . (Gate::allows('delete_circulars') ? 'YES' : 'NO') . "<br>";
        echo "publish_circulars: " . (Gate::allows('publish_circulars') ? 'YES' : 'NO') . "<br>";
        echo "view_circulars_analytics: " . (Gate::allows('view_circulars_analytics') ? 'YES' : 'NO') . "<br>";

        return '';
    });
});

// Temporary test route for document upload
Route::get('/test-document-fix', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head><title>Test Document Fix</title></head>
    <body style="padding:20px;">
        <h2>Test Document Upload - FILE REQUIRED</h2>
        <form action="' . route('owner.documents.store') . '" method="POST" enctype="multipart/form-data">
            ' . csrf_field() . '
            <div style="margin:10px 0;">
                <label>Student ID:</label>
                <input type="number" name="student_id" value="7" required>
            </div>
            <div style="margin:10px 0;">
                <label>Document Type:</label>
                <input type="text" name="document_type" value="admission_form" required>
            </div>
            <div style="margin:10px 0;">
                <label>Title:</label>
                <input type="text" name="title" value="Test Document" required>
            </div>
            <div style="margin:10px 0;">
                <label>File: *</label>
                <input type="file" name="file_path" required>
                <br><small>कृपया एउटा PDF, JPG, वा PNG file छनौट गर्नुहोस्</small>
            </div>
            <div style="margin:10px 0;">
                <label>Issue Date:</label>
                <input type="date" name="issue_date" value="' . date('Y-m-d') . '" required>
            </div>
            <div style="margin:10px 0;">
                <label>Status:</label>
                <select name="status">
                    <option value="active">Active</option>
                </select>
            </div>
            <button type="submit" style="padding:10px 20px; background:blue; color:white; border:none;">Submit Test</button>
        </form>
        
        <script>
            document.querySelector("form").addEventListener("submit", function(e) {
                const fileInput = document.querySelector("input[name=\'file_path\']");
                if (!fileInput.files.length) {
                    alert("कृपया FILE छनौट गर्नुहोस्!");
                    e.preventDefault();
                }
            });
        </script>
    </body>
    </html>
    ';
});

// ✅ ADDED: Circular Test Route
Route::get('/test-circular-fix', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head><title>Test Circular System</title></head>
    <body style="padding:20px;">
        <h2>Test Circular System Fix</h2>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Owner Circular Routes Test:</h3>
            <a href="' . route('owner.circulars.index') . '" style="display:inline-block; padding:10px; background:green; color:white; margin:5px;">Owner Circulars</a>
            <a href="' . route('owner.circulars.create') . '" style="display:inline-block; padding:10px; background:blue; color:white; margin:5px;">Create Circular</a>
        </div>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Student Circular Routes Test:</h3>
            <a href="' . route('student.circulars.index') . '" style="display:inline-block; padding:10px; background:green; color:white; margin:5px;">Student Circulars</a>
        </div>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Debug Routes:</h3>
            <a href="/debug-circular-system" style="display:inline-block; padding:10px; background:orange; color:white; margin:5px;">Circular System Debug</a>
            <a href="/debug-circular-permissions" style="display:inline-block; padding:10px; background:orange; color:white; margin:5px;">Circular Permissions Debug</a>
        </div>
    </body>
    </html>
    ';
});
