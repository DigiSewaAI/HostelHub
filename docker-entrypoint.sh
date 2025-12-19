#!/bin/bash

echo "🚀 HostelHub Railway Startup"
echo "=============================="

# Railway को dynamic PORT लिने
PORT=${PORT:-8080}
echo "Railway PORT: $PORT"

# ✅ CRITICAL FIX 1: Apache को मूल configuration मै PORT set गर्ने
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Laravel directory मा जाने
cd /var/www/html

# .env file check गर्ने
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env 2>/dev/null || touch .env
    php artisan key:generate --force
fi

# Railway Database सेटअप
if [ ! -z "$MYSQLHOST" ]; then
    echo "Configuring Railway MySQL..."
    echo "DB_CONNECTION=mysql" >> .env
    echo "DB_HOST=$MYSQLHOST" >> .env
    echo "DB_PORT=$MYSQLPORT" >> .env
    echo "DB_DATABASE=$MYSQLDATABASE" >> .env
    echo "DB_USERNAME=$MYSQLUSER" >> .env
    echo "DB_PASSWORD=$MYSQLPASSWORD" >> .env
fi

# Railway URL सेटअप
if [ ! -z "$RAILWAY_STATIC_URL" ]; then
    echo "APP_URL=$RAILWAY_STATIC_URL" >> .env
    echo "ASSET_URL=$RAILWAY_STATIC_URL" >> .env
fi

# Laravel basic setup
echo "Setting up Laravel..."
php artisan storage:link --force 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

# ✅ CRITICAL FIX 2: PORT कसरी चलिरहेछ check गर्ने
echo "Checking Apache configuration..."
echo "Apache will listen on port: ${PORT}"
echo "Test command: curl -I http://localhost:${PORT}"

# Apache सुरु गर्ने
echo "Starting Apache on port ${PORT}..."
exec apache2-foreground