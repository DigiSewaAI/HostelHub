FROM php:8.3-apache

RUN a2enmod rewrite
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring gd zip

WORKDIR /var/www/html

# Copy project files FIRST
COPY . .

# Remove any existing corrupted cache
RUN rm -rf bootstrap/cache/*

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

# Install composer and dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Generate key and basic setup
RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN php artisan key:generate --force || true

# Fix Apache document root
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Final permissions
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]