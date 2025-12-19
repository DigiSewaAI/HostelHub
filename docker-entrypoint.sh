# पूरै फाइल यसरी REPLACE गर्नुहोस्:
#!/bin/bash

echo "🚀 HostelHub Railway मा सुरु हुदैछ..."

# Railway को PORT प्रयोग गर्ने
PORT=${PORT:-8080}
echo "Port: $PORT"

# Apache लाई यही PORT मा चलाउने
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/*.conf 2>/dev/null || true

# Laravel setup
cd /var/www/html

# .env file बनाउने
if [ ! -f ".env" ]; then
    cp .env.example .env 2>/dev/null || touch .env
    php artisan key:generate --force
fi

# Database configuration
if [ ! -z "$MYSQLHOST" ]; then
    echo "DB_CONNECTION=mysql" >> .env
    echo "DB_HOST=$MYSQLHOST" >> .env
    echo "DB_PORT=$MYSQLPORT" >> .env
    echo "DB_DATABASE=$MYSQLDATABASE" >> .env
    echo "DB_USERNAME=$MYSQLUSER" >> .env
    echo "DB_PASSWORD=$MYSQLPASSWORD" >> .env
fi

# Basic Laravel setup
php artisan storage:link --force
php artisan optimize:clear

# Apache start गर्ने
echo "Apache $PORT मा सुरु हुदैछ..."
exec apache2-foreground