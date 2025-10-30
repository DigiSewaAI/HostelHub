FROM php:8.3-apache

RUN a2enmod rewrite
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring gd zip

WORKDIR /var/www/html

COPY . .

# Remove corrupted cache
RUN rm -rf bootstrap/cache/*

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Basic setup without route caching
RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN php artisan key:generate --force || true

# Only config and view cache (skip route cache to avoid conflicts)
RUN php artisan config:cache || true
RUN php artisan view:cache || true

# Fix Apache document root
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Final permissions
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

# Use a startup script that handles route caching safely
COPY deploy.sh /usr/local/bin/deploy.sh
RUN chmod +x /usr/local/bin/deploy.sh

CMD ["/usr/local/bin/deploy.sh"]