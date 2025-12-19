#!/bin/bash

echo "🚀 HostelHub Railway Startup"
echo "=============================="

# Railway को dynamic PORT लिने
PORT=${PORT:-8080}
echo "Railway PORT: $PORT"

# Apache को मूल configuration मै PORT set गर्ने (यो नयाँ तरिका)
echo "Listen ${PORT}" > /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf 2>/dev/null || true

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
    # पहिले .env मा database configuration हटाउने
    sed -i '/DB_/d' .env 2>/dev/null || true
    echo "DB_CONNECTION=mysql" >> .env
    echo "DB_HOST=$MYSQLHOST" >> .env
    echo "DB_PORT=$MYSQLPORT" >> .env
    echo "DB_DATABASE=$MYSQLDATABASE" >> .env
    echo "DB_USERNAME=$MYSQLUSER" >> .env
    echo "DB_PASSWORD=$MYSQLPASSWORD" >> .env
fi

# Railway URL सेटअप
if [ ! -z "$RAILWAY_STATIC_URL" ]; then
    sed -i '/APP_URL=/d' .env 2>/dev/null || true
    sed -i '/ASSET_URL=/d' .env 2>/dev/null || true
    echo "APP_URL=$RAILWAY_STATIC_URL" >> .env
    echo "ASSET_URL=$RAILWAY_STATIC_URL" >> .env
fi

# Laravel basic setup
echo "Setting up Laravel..."
php artisan storage:link --force 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

# Apache सुरु गर्ने
echo "Starting Apache on port ${PORT}..."
exec apache2-foreground