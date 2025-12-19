#!/bin/bash

echo "🚀 HostelHub Railway मा सुरु हुदैछ..."

# Railway को PORT प्रयोग गर्ने
PORT=${PORT:-8080}
echo "Port: $PORT"

# ✅✅✅ यो CRITICAL FIX हो: Apache लाई सिधै PORT मा चलाउने
echo "Listen ${PORT}" > /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/*.conf 2>/dev/null || true
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/*.conf 2>/dev/null || true

# Laravel setup
cd /var/www/html

# .env file बनाउने
if [ ! -f ".env" ]; then
    cp .env.example .env 2>/dev/null || touch .env
    php artisan key:generate --force
fi

# ✅ Database configuration
if [ ! -z "$MYSQLHOST" ]; then
    echo "DB_CONNECTION=mysql" >> .env
    echo "DB_HOST=$MYSQLHOST" >> .env
    echo "DB_PORT=$MYSQLPORT" >> .env
    echo "DB_DATABASE=$MYSQLDATABASE" >> .env
    echo "DB_USERNAME=$MYSQLUSER" >> .env
    echo "DB_PASSWORD=$MYSQLPASSWORD" >> .env
    echo "✅ Database सेट भयो"
fi

# ✅ Railway URL सेट गर्ने
if [ ! -z "$RAILWAY_STATIC_URL" ]; then
    echo "APP_URL=$RAILWAY_STATIC_URL" >> .env
    echo "✅ APP_URL सेट भयो"
fi

# Basic Laravel setup
php artisan storage:link --force 2>/dev/null || true
php artisan optimize:clear 2>/dev/null || true

# ✅ Apache सुरु गर्ने
echo "Apache ${PORT} मा सुरु हुदैछ..."
apache2-foreground