# Use an official PHP runtime as the base image
FROM php:8.3-apache-bookworm

# 1. Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache mod_rewrite and set the document root to /public
RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Set working directory
WORKDIR /var/www/html

# 4. Copy Composer from its own image for better caching
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Copy application files
COPY . .

# 6. 🔥 CRITICAL FIX: Create Laravel directories and set permissions BEFORE composer install
RUN mkdir -p bootstrap/cache storage/framework/{sessions,views,cache}
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# 7. Install PHP dependencies (excluding dev dependencies)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 8. The port Railway will use internally
EXPOSE 8080

# 9. FIX: Ensure everything works in Railway without breaking local
CMD mkdir -p bootstrap/cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 bootstrap/cache storage && \
    php artisan package:discover --quiet && \
    sed -i "s/Listen 80/Listen ${PORT:-8080}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT:-8080}/g" /etc/apache2/sites-enabled/*.conf && \
    apache2-foreground