<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "=== BLADE RENDERING DEBUG ===\n";

// Test 1: Check if view compiles
try {
    $compiler = app('blade.compiler');
    $viewPath = view()->getFinder()->find('frontend.home');
    echo "✅ View path: $viewPath\n";

    $compiledPath = $compiler->getCompiledPath($viewPath);
    echo "✅ Compiled path: $compiledPath\n";

    if (file_exists($compiledPath)) {
        echo "✅ Compiled view exists\n";
        $compiledContent = file_get_contents($compiledPath);

        // Check for hero section in compiled view
        if (strpos($compiledContent, 'hero-section') !== false) {
            echo "✅ Hero section found in compiled view\n";
        } else {
            echo "❌ Hero section NOT found in compiled view\n";
        }

        // Check for yield statements
        if (strpos($compiledContent, 'yield') !== false) {
            echo "✅ Yield statements found\n";
        } else {
            echo "❌ No yield statements found\n";
        }
    } else {
        echo "❌ Compiled view does not exist\n";
    }
} catch (Exception $e) {
    echo "❌ Compiler error: " . $e->getMessage() . "\n";
}

// Test 2: Render view with minimal data
try {
    $minimalData = [
        'heroSliderItems' => collect(),
        'galleryItems' => collect(),
        'testimonials' => collect(),
        'metrics' => ['total_students' => 0],
        'cities' => collect(),
        'hostels' => collect()
    ];

    $content = view('frontend.home', $minimalData)->render();

    // Check for specific content in output
    if (strpos($content, 'hero-section') !== false) {
        echo "✅ 'hero-section' string found in output\n";
    } else {
        echo "❌ 'hero-section' string NOT found in output\n";
    }

    if (strpos($content, 'HostelHub — तपाइँको') !== false) {
        echo "✅ Hero title found in output\n";
    } else {
        echo "❌ Hero title NOT found in output\n";
    }

    echo "Output size: " . strlen($content) . " bytes\n";
} catch (Exception $e) {
    echo "❌ Render error: " . $e->getMessage() . "\n";
}

$kernel->terminate($request, $response);
