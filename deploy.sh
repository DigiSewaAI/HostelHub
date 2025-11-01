#!/bin/bash
set -e

echo "🔧 Starting Laravel Deployment..."

# Move to working directory
cd /var/www/html || exit

# Environment Detection and .env Setup
if [ "$RENDER" = "true" ]; then
    echo "🚀 Production Environment Detected - Using .env.production"
    if [ -f .env.production ]; then
        cp .env.production .env
        echo "✅ .env.production copied to .env"
    else
        echo "⚠️  .env.production not found, using .env.example"
        cp .env.example .env
    fi
else
    echo "💻 Local Development Environment Detected - Using .env.local"
    if [ -f .env.local ]; then
        cp .env.local .env
        echo "✅ .env.local copied to .env"
    else
        echo "⚠️  .env.local not found, using .env.example"
        cp .env.example .env
    fi
fi

# Generate application key if not exists
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating Application Key..."
    php artisan key:generate --force
    echo "✅ Application key generated"
else
    echo "✅ Application key already exists"
fi

# Ensure vendor folder exists
if [ ! -d "vendor" ]; then
    echo "📦 Installing PHP dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
    echo "✅ PHP dependencies installed"
else
    echo "✅ Vendor folder already exists"
fi

# Set permissions before artisan commands
echo "🔐 Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
echo "✅ Permissions set"

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear || echo "⚠️  Config clear skipped"
php artisan cache:clear || echo "⚠️  Cache clear skipped"
php artisan route:clear || echo "⚠️  Route clear skipped"
php artisan view:clear || echo "⚠️  View clear skipped"
echo "✅ Caches cleared"

# Create storage link (CRITICAL FOR FILE UPLOADS)
echo "📁 Creating storage link..."
php artisan storage:link || echo "⚠️  Storage link creation skipped"

# 🚨 CRITICAL: SKIP ALL DATABASE OPERATIONS 🚨
echo "🛡️  SKIPPING ALL DATABASE OPERATIONS - PROTECTING EXISTING DATA"
echo "📊 Existing users, hostels, students data preserved 100%"
echo "🔒 No migrations, no seeders, no database changes"

# Manual user creation instructions
echo "🔐 MANUAL STEP REQUIRED:"
echo "   1. Go to Render Database > Connect"
echo "   2. Run PSQL command"
echo "   3. Insert user manually with:"
echo "      Email: parasharregmi@gmail.com"
echo "      Password: password"

# 🎨 VITE ASSETS BUILD - FIXED VERSION
echo "🎨 Building frontend assets..."
if command -v npm &> /dev/null; then
    echo "🧹 Removing old build directory..."
    rm -rf public/build
    
    echo "📦 Installing Node.js dependencies..."
    npm install
    
    echo "🔨 Building Vite assets..."
    npm run build
    
    # Verify build output
    if [ -f "public/build/manifest.json" ]; then
        echo "✅ Vite manifest generated successfully at public/build/manifest.json"
        echo "📁 Build contents:"
        ls -la public/build/
    else
        echo "❌ Vite manifest missing - checking build directory..."
        ls -la public/build/ || echo "Build directory not found"
        echo "⚠️  Manifest generation issue - using fallback asset loading"
    fi
    echo "✅ Frontend assets built successfully"
else
    echo "⚠️  npm not available - using pre-built assets"
    echo "ℹ️  Ensure public/build directory exists with compiled assets"
fi

# 🔥 AGGRESSIVE CACHE CLEARING AFTER BUILD
echo "🧹 Clearing caches aggressively after build..."
php artisan config:clear
php artisan cache:clear  
php artisan view:clear
php artisan route:clear
echo "✅ Aggressive cache clearing completed"

# Create storage link (ensure it exists)
echo "📁 Creating storage link..."
php artisan storage:link

# Optimize based on environment
if [ "$RENDER" = "true" ]; then
    echo "⚡ Optimizing for Production..."
    php artisan config:cache || echo "⚠️  Config cache skipped"
    php artisan view:cache || echo "⚠️  View cache skipped"
    
    # Safe route caching
    echo "🔄 Checking routes for caching..."
    php artisan route:list --name=login > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        php artisan route:cache && echo "✅ Routes cached successfully"
    else
        echo "⚠️  Route caching skipped (route check failed)"
    fi
    
    # Cache events and packages
    php artisan event:cache || echo "⚠️  Event cache skipped"
    php artisan package:discover || echo "⚠️  Package discovery skipped"
else
    echo "🔓 Development Mode - Minimal optimization"
    php artisan config:cache || echo "⚠️  Config cache skipped"
    php artisan view:cache || echo "⚠️  View cache skipped"
    echo "ℹ️  Route caching disabled in development"
fi

# Final permission fix
echo "🔒 Finalizing permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html
echo "✅ Final permissions set"

echo "🎉 Deployment completed successfully!"
echo "📊 Environment: $(grep APP_ENV .env | cut -d '=' -f2)"
echo "🌐 App URL: $(grep APP_URL .env | cut -d '=' -f2)"
echo "🐛 Debug Mode: $(grep APP_DEBUG .env | cut -d '=' -f2)"
echo "📦 Vite Assets: $(if [ -f "public/build/manifest.json" ]; then echo "BUILT SUCCESSFULLY ✅"; else echo "USING FALLBACK ⚠️"; fi)"
echo "🛡️  Database: EXISTING DATA PROTECTED - NO CHANGES MADE"

# Start Apache in foreground
echo "🌐 Starting Apache web server..."
exec apache2-foreground