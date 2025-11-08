<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "=== CSS DEBUG ===\n";

// Check if home.css exists
$homeCssPath = public_path('build/assets/home.css');
if (file_exists($homeCssPath)) {
    echo "✅ home.css exists: " . filesize($homeCssPath) . " bytes\n";

    // Check hero styles in CSS
    $cssContent = file_get_contents($homeCssPath);
    if (strpos($cssContent, '.hero') !== false) {
        echo "✅ .hero class found in CSS\n";

        // Extract hero styles
        preg_match('/\.hero\s*\{[^}]+\}/', $cssContent, $matches);
        if (!empty($matches)) {
            echo "Hero styles: " . substr($matches[0], 0, 200) . "...\n";
        }
    } else {
        echo "❌ .hero class NOT found in CSS\n";
    }
} else {
    echo "❌ home.css not found!\n";
}

// Check Vite manifest
$viteManifest = public_path('build/manifest.json');
if (file_exists($viteManifest)) {
    echo "✅ Vite manifest exists\n";
    $manifest = json_decode(file_get_contents($viteManifest), true);
    if (isset($manifest['resources/css/home.css'])) {
        echo "✅ home.css in Vite manifest\n";
    }
}
