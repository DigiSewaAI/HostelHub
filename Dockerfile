# =========================
# FRONTEND (VITE)
# =========================
FROM node:22 AS frontend
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public
RUN npm run build


# =========================
# BACKEND (PHP CLI)
# =========================
FROM php:8.3-cli

# System deps
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install bcmath gd pdo_mysql mbstring zip exif pcntl \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# App
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

# Frontend build
COPY --from=frontend /app/public/build public/build

# Permissions
RUN php artisan storage:link || true \
 && chmod -R 775 storage bootstrap/cache

# Minimal env
RUN touch .env \
 && echo "APP_NAME=HostelHub" >> .env \
 && echo "APP_ENV=production" >> .env \
 && echo "APP_DEBUG=false" >> .env \
 && echo "APP_KEY=base64:$(openssl rand -base64 32)" >> .env

# 🔥 THIS IS THE KEY
CMD php -S 0.0.0.0:$PORT -t public
