<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Hostel;
use App\Models\Payment;

echo "🔍 **LOGO DEBUG START**\n\n";

// 1. सबै hostels check गर्ने
$hostels = Hostel::all();
foreach ($hostels as $hostel) {
    echo "Hostel ID: {$hostel->id}\n";
    echo "Name: {$hostel->name}\n";
    echo "Database logo_path: " . ($hostel->logo_path ?: 'NULL') . "\n";

    if ($hostel->logo_path) {
        $path = storage_path('app/public/' . $hostel->logo_path);
        echo "Full Path: {$path}\n";
        echo "File Exists: " . (file_exists($path) ? '✅ YES' : '❌ NO') . "\n";

        if (file_exists($path)) {
            // Base64 convert गरेर check गर्ने
            $imageData = file_get_contents($path);
            $base64 = base64_encode($imageData);
            $mimeType = mime_content_type($path);
            echo "MIME Type: {$mimeType}\n";
            echo "Base64 Length: " . strlen($base64) . "\n\n";
        }
    } else {
        echo "❌ ERROR: logo_path is NULL in database!\n\n";
    }
}

// 2. Payment 2 को लागि check गर्ने
echo "\n🎯 **CHECKING PAYMENT ID 2**\n";
$payment = Payment::with('hostel')->find(2);
if ($payment && $payment->hostel) {
    echo "Payment ID: {$payment->id}\n";
    echo "Hostel ID: {$payment->hostel_id}\n";
    echo "Hostel Name: {$payment->hostel->name}\n";
    echo "Hostel Logo Path: " . ($payment->hostel->logo_path ?: 'NULL') . "\n";

    // Logo directory मा files check गर्ने
    $logoDir = storage_path('app/public/hostel_logos/');
    echo "\n📁 **LOGO DIRECTORY FILES:**\n";

    if (is_dir($logoDir)) {
        $files = scandir($logoDir);
        $count = 0;
        foreach ($files as $file) {
            if (!in_array($file, ['.', '..'])) {
                $count++;
                echo "  - {$file}\n";
            }
        }
        echo "Total logo files: {$count}\n";
    } else {
        echo "❌ Logo directory doesn't exist!\n";
    }
} else {
    echo "❌ Payment ID 2 not found!\n";
}

// 3. Quick Fix सुझाव
echo "\n⚡ **QUICK FIX:**\n";
echo "1. तपाईंको database मा hostels table खोल्नुहोस्\n";
echo "2. 'logo_path' column मा यो value set गर्नुहोस्:\n";
echo "   'hostel_logos/1xQtVtb8kRbANadwN256H4z1e2LqNbQ6bDOvLAox.jpg'\n";
echo "3. Save गर्नुहोस्\n";
echo "4. फेरी PDF generate गर्नुहोस्\n";

echo "\n✅ **DEBUG COMPLETE**\n";
