<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "=== FINAL VERIFICATION ===\n";

try {
    $content = view('frontend.home')->render();

    // Check for hero content
    if (strpos($content, 'HostelHub — तपाइँको') !== false) {
        echo "✅ HERO TITLE FOUND - FIX SUCCESSFUL!\n";
    } else {
        echo "❌ Hero title not found\n";
    }

    if (strpos($content, 'hero-section') !== false) {
        echo "❌ Old hero-section still exists\n";
    } else {
        echo "✅ Old hero-section removed\n";
    }

    if (strpos($content, 'hero-content') !== false) {
        echo "✅ New hero content found\n";
    }

    echo "Output size: " . strlen($content) . " bytes\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
