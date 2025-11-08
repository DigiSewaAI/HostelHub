<?php
// fix_laravel.php - COMPLETE LARAVEL FIX

echo "🚨 STARTING COMPREHENSIVE LARAVEL FIX...\n";

// 1. Check critical files
$criticalFiles = [
    'bootstrap/app.php',
    'config/app.php',
    'app/Http/Kernel.php',
    'vendor/autoload.php'
];

foreach ($criticalFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file: EXISTS\n";
    } else {
        echo "❌ $file: MISSING - This is the problem!\n";
    }
}

// 2. Check View Service Provider
$appConfig = file_get_contents('config/app.php');
if (strpos($appConfig, 'Illuminate\View\ViewServiceProvider::class') !== false) {
    echo "✅ ViewServiceProvider: REGISTERED\n";
} else {
    echo "❌ ViewServiceProvider: MISSING - Adding it...\n";

    // Auto-fix
    $newConfig = str_replace(
        "'providers' => [",
        "'providers' => [\n        Illuminate\View\ViewServiceProvider::class,",
        $appConfig
    );
    file_put_contents('config/app.php', $newConfig);
    echo "✅ ViewServiceProvider: ADDED\n";
}

// 3. Check vendor directory
if (is_dir('vendor') && file_exists('vendor/autoload.php')) {
    echo "✅ Vendor directory: OK\n";
} else {
    echo "❌ Vendor directory: MISSING - Run composer install!\n";
}

echo "🎯 FIX COMPLETED! Now run these commands:\n";
echo "1. composer dump-autoload\n";
echo "2. php artisan config:cache\n";
echo "3. php artisan view:clear\n";
echo "4. Restart Laragon\n";
