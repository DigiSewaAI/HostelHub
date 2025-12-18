FROM php:8.3-cli

# Install system deps
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App files
COPY . .

# Laravel dirs
RUN mkdir -p bootstrap/cache storage/framework/{sessions,views,cache} \
    && chmod -R 775 bootstrap/cache storage

# Install deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=${PORT}
