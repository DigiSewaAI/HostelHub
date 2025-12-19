#!/bin/bash
echo "=== HostelHub Railway Deployment ==="

# Use Railway's PORT or default to 8080
PORT=${PORT:-8080}
echo "Using port: $PORT"

# ✅ FIX: Create .env file with ACTUAL VALUES (not variable placeholders)
cat > .env << EOF
APP_NAME="HostelHub"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://${RAILWAY_STATIC_URL:-localhost}
APP_KEY=base64:$(openssl rand -base64 32)

LOG_CHANNEL=stderr
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=${MYSQLHOST:-mysql}
DB_PORT=${MYSQLPORT:-3306}
DB_DATABASE=${MYSQLDATABASE:-railway}
DB_USERNAME=${MYSQLUSER:-root}
DB_PASSWORD=${MYSQLPASSWORD}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=${MAIL_HOST:-smtp.mailgun.org}
MAIL_PORT=${MAIL_PORT:-587}
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-tls}
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-noreply@hostelhub.com}
MAIL_FROM_NAME="HostelHub"
EOF

echo "✅ .env file created successfully"

# Update Apache to use Railway port
echo "Updating Apache to port $PORT..."
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-enabled/*.conf

# Set Apache document root for Laravel
sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# ✅ FIX: Verify APP_KEY exists before running artisan commands
if grep -q "APP_KEY=" .env; then
    echo "✅ APP_KEY found in .env"
else
    echo "❌ APP_KEY missing, generating..."
    php artisan key:generate --force
fi

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

# Clear and optimize cache (skip route:cache due to error)
php artisan optimize:clear
php artisan config:cache
# php artisan route:cache  # TEMPORARILY DISABLED due to duplicate route error
php artisan view:cache

echo "Route caching skipped for initial deployment."

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache public/storage
chmod -R 775 storage bootstrap/cache

# Start Apache
echo "Starting Apache on port $PORT..."
exec apache2-foreground