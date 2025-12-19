#!/bin/bash
echo "=== HostelHub Railway Deployment ==="

PORT=${PORT:-8080}
echo "Using port: $PORT"

# Update port in Apache config (just in case)
sed -i "s/Listen 8080/Listen $PORT/g" /etc/apache2/ports.conf 2>/dev/null || true
sed -i "s/:8080/:$PORT/g" /etc/apache2/sites-enabled/*.conf 2>/dev/null || true

# Setup environment variables from Railway
if [ ! -z "$MYSQLHOST" ]; then
    echo "DB_CONNECTION=mysql" >> .env
    echo "DB_HOST=$MYSQLHOST" >> .env
    echo "DB_PORT=$MYSQLPORT" >> .env
    echo "DB_DATABASE=$MYSQLDATABASE" >> .env
    echo "DB_USERNAME=$MYSQLUSER" >> .env
    echo "DB_PASSWORD=$MYSQLPASSWORD" >> .env
fi

# Run Laravel setup
php artisan storage:link || true

# Copy images if they exist
if [ -d "storage/app/public" ]; then
    mkdir -p public/storage
    cp -r storage/app/public/* public/storage/ 2>/dev/null || true
fi

# Run migrations if DB is available
php artisan migrate --force --no-interaction || echo "Migration failed or not needed"

# Cache clear
php artisan optimize:clear || true

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache public/storage || true

echo "Starting Apache..."
exec apache2-foreground