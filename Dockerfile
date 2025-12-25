# =========================================================
# FRONTEND BUILD (VITE)
# =========================================================
FROM node:22 AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

RUN npm run build


# =========================================================
# BACKEND (PHP + APACHE)
# =========================================================
FROM php:8.3-apache-bookworm

# System deps
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Apache config
RUN a2enmod rewrite \
 && sed -ri 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf \
 && sed -ri 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

RUN printf "<Directory /var/www/html/public>\n\
Options Indexes FollowSymLinks\n\
AllowOverride All\n\
Require all granted\n\
</Directory>\n" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy full app (helpers.php safe)
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy built frontend
COPY --from=frontend /app/public/build public/build

# Storage & permissions
RUN php artisan storage:link || true \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Minimal env
RUN touch .env \
 && echo "APP_NAME=HostelHub" >> .env \
 && echo "APP_ENV=production" >> .env \
 && echo "APP_DEBUG=false" >> .env \
 && echo "APP_KEY=base64:$(openssl rand -base64 32 | tr -d '\n')" >> .env

EXPOSE 8080
CMD ["apache2-foreground"]

