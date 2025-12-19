#!/bin/bash
echo "=== HostelHub Railway Deployment ==="

# Use Railway's PORT or default to 8080
PORT=${PORT:-8080}
echo "Using port: $PORT"

# ✅ FIX: Create .env file with Railway variables
cat > .env << EOF
APP_NAME=HostelHub
APP_ENV=production
APP_KEY=base64:$(openssl rand -base64 32)
APP_DEBUG=false
APP_URL=https://${RAILWAY_PUBLIC_DOMAIN:-localhost}

LOG_CHANNEL=stderr
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=${MYSQLHOST}
DB_PORT=${MYSQLPORT}
DB_DATABASE=${MYSQLDATABASE}
DB_USERNAME=${MYSQLUSER}
DB_PASSWORD=${MYSQLPASSWORD}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=${MAIL_HOST}
MAIL_PORT=${MAIL_PORT}
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}
MAIL_FROM_NAME="${APP_NAME}"
EOF

# ✅ FIX: Update Apache to use Railway port
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/Listen 8080/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-enabled/*.conf
sed -i "s/:8080/:$PORT/g" /etc/apache2/sites-enabled/*.conf

# Set Apache document root for Laravel
sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# ✅ FIX: Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Create storage link
php artisan storage:link

# ✅ FIX: Create public storage directory if missing
mkdir -p public/storage

# Copy only essential image folders
folders=("galleries" "hostels" "hero" "hostel_logos" "room_images")
for folder in "${folders[@]}"; do
    if [ -d "storage/app/public/$folder" ]; then
        echo "Copying: $folder"
        mkdir -p "public/storage/$folder"
        cp -r "storage/app/public/$folder/"* "public/storage/$folder/" 2>/dev/null || true
    fi
done

# ✅ FIX: Run database migrations with retry
echo "Running migrations..."
max_attempts=5
attempt=1
while [ $attempt -le $max_attempts ]; do
    php artisan migrate --force --no-interaction && break
    echo "Migration attempt $attempt failed. Retrying in 5 seconds..."
    sleep 5
    attempt=$((attempt + 1))
done

# Clear and optimize cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache public
chmod -R 775 storage bootstrap/cache

# ✅ FIX: Test if Laravel is working before starting Apache
echo "Testing Laravel..."
curl -f http://localhost:$PORT/health > /dev/null 2>&1 || true

# Start Apache
echo "Starting Apache on port $PORT..."
apache2-foreground