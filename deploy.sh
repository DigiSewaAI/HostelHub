#!/bin/bash
set -e

echo "🔧 Starting Laravel Deployment..."

# Move to working directory
cd /var/www/html || exit

# Ensure vendor folder exists
if [ ! -d "vendor" ]; then
    echo "📦 Installing PHP dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
fi

# Copy .env if not present
if [ ! -f .env ]; then
    echo "⚙️  No .env found, copying default..."
    cp .env.example .env
fi

# Set permissions before artisan commands
echo "🔐 Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations safely
echo "🗃️  Running migrations..."
php artisan migrate --force || echo "⚠️  Migration skipped (DB might not be ready yet)"

# Optimize for production
echo "⚡ Optimizing Laravel..."
php artisan config:cache || true
php artisan view:cache || true

# Check and cache routes if safe
php artisan route:list --name=register.organization > /dev/null 2>&1
if [ $? -eq 0 ]; then
    php artisan route:cache && echo "✅ Routes cached successfully"
else
    echo "⚠️  Route caching skipped (potential conflicts)"
fi

# Final permission fix
echo "🔒 Finalizing permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html

echo "🚀 Deployment completed successfully!"
