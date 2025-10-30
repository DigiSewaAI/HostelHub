#!/bin/bash

echo "🔧 Starting Laravel Deployment..."

# Clear all caches first
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
php artisan migrate --force

# Optimize without route caching (to avoid conflicts)
php artisan config:cache
php artisan view:cache

# Only cache routes if no conflicts
php artisan route:list --name=register.organization > /dev/null 2>&1
if [ $? -eq 0 ]; then
    php artisan route:cache
    echo "✅ Routes cached successfully"
else
    echo "⚠️  Route caching skipped due to conflicts"
fi

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "🚀 Deployment completed!"