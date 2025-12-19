#!/bin/bash

echo "========================================"
echo "🚀 HostelHub Docker Entrypoint"
echo "========================================"

# Set environment
PORT=${PORT:-8080}
echo "Using PORT: $PORT"

# Update Apache port configuration
sed -i "s/Listen 8080/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:8080/:$PORT/g" /etc/apache2/sites-available/000-default.conf 2>/dev/null || true

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel setup
cd /var/www/html

# Check if .env exists, if not create from example
if [ ! -f ".env" ]; then
    echo "⚠️  .env file not found, creating from example..."
    cp .env.example .env 2>/dev/null || touch .env
    php artisan key:generate --force 2>/dev/null || true
fi

# Set up environment variables from Railway
if [ ! -z "$MYSQLHOST" ]; then
    echo "🔧 Setting up Railway database configuration..."
    echo "DB_CONNECTION=mysql" >> .env
    echo "DB_HOST=$MYSQLHOST" >> .env
    echo "DB_PORT=$MYSQLPORT" >> .env
    echo "DB_DATABASE=$MYSQLDATABASE" >> .env
    echo "DB_USERNAME=$MYSQLUSER" >> .env
    echo "DB_PASSWORD=$MYSQLPASSWORD" >> .env
    echo "✅ Database configuration set"
fi

# Set up other Railway environment variables
if [ ! -z "$RAILWAY_STATIC_URL" ]; then
    echo "APP_URL=$RAILWAY_STATIC_URL" >> .env
    echo "ASSET_URL=$RAILWAY_STATIC_URL" >> .env
fi

# Create storage link
php artisan storage:link --force 2>/dev/null || true

# Clear and optimize
php artisan optimize:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Run migrations if safe
echo "🔄 Checking database..."
if php artisan migrate:status > /dev/null 2>&1; then
    echo "📦 Running database migrations..."
    php artisan migrate --force --no-interaction 2>/dev/null || echo "⚠️  Migration failed, but continuing..."
else
    echo "⚠️  Database not available, skipping migrations"
fi

# Cache everything for production
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Wait for Apache to be ready
echo "⏳ Starting Apache on port $PORT..."
sleep 2

# Start Apache in foreground
exec apache2-foreground