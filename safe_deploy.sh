#!/bin/bash
echo "=== HostelHub Railway Deployment ==="

# Use Railway's PORT or default to 8080
PORT=${PORT:-8080}
echo "Using port: $PORT"

# Create basic .env file
cat > .env << EOF
APP_NAME=HostelHub
APP_ENV=production
APP_DEBUG=false
APP_URL=https://\${RAILWAY_STATIC_URL:-localhost}

LOG_CHANNEL=stderr
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=\${MYSQLHOST}
DB_PORT=\${MYSQLPORT}
DB_DATABASE=\${MYSQLDATABASE}
DB_USERNAME=\${MYSQLUSER}
DB_PASSWORD=\${MYSQLPASSWORD}
EOF

# Update Apache port
echo "Updating Apache to port $PORT..."
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-enabled/*.conf

# Set Apache document root for Laravel
sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Generate app key
php artisan key:generate --force

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
php artisan view:cache

# TEMPORARILY DISABLED due to duplicate route name error
# php artisan route:cache
echo "Route caching skipped for initial deployment."

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache public/storage
chmod -R 775 storage bootstrap/cache

# Start Apache
echo "Starting Apache on port $PORT..."
exec apache2-foreground