# PHP CLI image (NO Apache)
FROM php:8.3-cli-bookworm

# System deps
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App files
COPY . .

# Laravel required dirs
RUN mkdir -p bootstrap/cache storage/framework/{cache,sessions,views} \
    && chmod -R 775 storage bootstrap/cache

# Dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Railway PORT
ENV PORT=8000

EXPOSE 8000

# 🚀 Start Laravel correctly on Railway
CMD php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan serve --host=0.0.0.0 --port=${PORT}
