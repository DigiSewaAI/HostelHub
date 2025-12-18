#!/bin/bash
echo "=== HostelHub Railway Deployment ==="

# Use Railway's PORT or default to 8080
PORT=${PORT:-8080}
echo "Using port: $PORT"

# Update Apache to use the Railway port
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-enabled/*.conf

# Set Apache document root for Laravel
sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Create storage link
php artisan storage:link

# Copy only essential image folders
folders=("galleries" "hostels" "hero" "hostel_logos" "room_images")
for folder in "${folders[@]}"; do
    if [ -d "storage/app/public/$folder" ]; then
        echo "Copying: $folder"
        mkdir -p "public/storage/$folder"
        cp -r "storage/app/public/$folder/"* "public/storage/$folder/" 2>/dev/null || true
    fi
done

# Database migrations
php artisan migrate --force

# Clear and optimize cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Start Apache
echo "Starting Apache on port $PORT..."
exec apache2-foreground