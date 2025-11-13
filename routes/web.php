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
