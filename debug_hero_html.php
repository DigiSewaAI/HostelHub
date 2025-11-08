<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "=== HERO HTML DEBUG ===\n";

try {
    $content = view('frontend.home')->render();

    // Check if hero HTML exists in output
    if (strpos($content, 'hero-section') !== false) {
        echo "✅ Hero section HTML: EXISTS in output\n";

        // Extract hero section
        preg_match('/<section class="hero".*?<\/section>/s', $content, $matches);
        if (!empty($matches)) {
            echo "✅ Hero section found: " . strlen($matches[0]) . " bytes\n";

            // Check for display: none
            if (strpos($matches[0], 'display: none') !== false) {
                echo "❌ Hero has display: none\n";
            } else {
                echo "✅ No display: none found\n";
            }

            // Check for visibility: hidden
            if (strpos($matches[0], 'visibility: hidden') !== false) {
                echo "❌ Hero has visibility: hidden\n";
            } else {
                echo "✅ No visibility: hidden found\n";
            }
        } else {
            echo "❌ Could not extract hero section\n";
        }
    } else {
        echo "❌ Hero section HTML: NOT FOUND in output\n";
    }
} catch (Exception $e) {
    echo "❌ View error: " . $e->getMessage() . "\n";
}

$kernel->terminate($request, $response);
