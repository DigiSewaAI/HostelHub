# Use official PHP Apache image
FROM php:8.3-apache-bookworm

# 1. Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache rewrite & point doc root to /public
RUN a2enmod rewrite
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Set working directory
WORKDIR /var/www/html

# 4. Copy Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Copy application code
COPY . .

# 6. Create Laravel required directories (build-time)
RUN mkdir -p \
    storage/framework/{cache,sessions,views} \
    bootstrap/cache \
    /tmp/views \
    /tmp/cache

# 7. Set permissions (safe & minimal)
RUN chown -R www-data:www-data storage bootstrap/cache /tmp \
    && chmod -R 775 storage bootstrap/cache /tmp

# 8. Install PHP dependencies (NO scripts)
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# 9. Railway internal port
EXPOSE 8080

# 10. Start Apache (NO artisan here ❌)
CMD sed -i "s/Listen 80/Listen ${PORT:-8080}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT:-8080}/g" /etc/apache2/sites-enabled/*.conf && \
    apache2-foreground
