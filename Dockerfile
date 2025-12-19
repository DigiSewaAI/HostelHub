# ✅ Apache + PHP (REQUIRED for Railway HTTP)
FROM php:8.3-apache

# 1. System dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache rewrite & set Laravel public directory
RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Working directory
WORKDIR /var/www/html

# 4. Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Copy application
COPY . .

# 6. Laravel required directories & permissions
RUN mkdir -p bootstrap/cache storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 7. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 8. Railway port support
EXPOSE 8080

# 9. Start Apache correctly for Railway
CMD sed -i "s/Listen 80/Listen ${PORT:-8080}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT:-8080}/g" /etc/apache2/sites-enabled/*.conf && \
    apache2-foreground
