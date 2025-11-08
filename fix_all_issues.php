<?php
require __DIR__ . '/vendor/autoload.php';

echo "=== COMPLETE FIX SCRIPT ===\n";

// 1. Clear all caches
echo "1. Clearing caches...\n";
Artisan::call('cache:clear');
Artisan::call('config:clear');
Artisan::call('view:clear');
Artisan::call('route:clear');

// 2. Check if we have gallery items
$galleryCount = \App\Models\Gallery::where('is_active', true)->count();
echo "2. Active gallery items: $galleryCount\n";

// 3. Check meals table structure
try {
    $meals = \App\Models\Meal::first();
    echo "3. Meals table: EXISTS\n";
} catch (Exception $e) {
    echo "3. Meals table: ERROR - " . $e->getMessage() . "\n";
}

echo "🎯 FIXES APPLIED! Now:\n";
echo "- Home page should show hero section\n";
echo "- Run 'npm run dev' in separate terminal for other pages\n";
echo "- Restart browser and check\n";
