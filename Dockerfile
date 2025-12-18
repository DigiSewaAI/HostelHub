FROM php:8.3-cli

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App files
COPY . .

# Laravel directories
RUN mkdir -p bootstrap/cache storage/framework/{sessions,views,cache} \
    && chmod -R 775 bootstrap/cache storage

# Install deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Railway port
EXPOSE 8080

# Start Laravel
CMD php artisan migrate --force || true && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
