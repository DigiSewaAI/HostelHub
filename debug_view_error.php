<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "=== VIEW ERROR DEBUG ===\n";

try {
    $content = view('frontend.home')->render();
    echo "✅ View rendered successfully: " . strlen($content) . " bytes\n";
} catch (Exception $e) {
    echo "❌ View error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

// Test with minimal data
try {
    $minimalData = [
        'featuredRooms' => collect(),
        'metrics' => ['total_hostels' => 0, 'total_students' => 0],
        'cities' => collect(),
        'hostels' => collect(),
        'testimonials' => collect(),
        'roomTypes' => collect(),
        'heroSliderItems' => collect(),
        'galleryItems' => collect(),
        'meals' => collect(),
        'featuredMealMenus' => collect()
    ];

    $content = view('frontend.home', $minimalData)->render();
    echo "✅ Minimal data view: SUCCESS\n";
} catch (Exception $e) {
    echo "❌ Minimal data error: " . $e->getMessage() . "\n";
}
