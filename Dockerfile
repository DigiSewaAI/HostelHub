# =========================================================
#  FRONTEND BUILD (VITE / NODE)
# =========================================================
FROM node:22 AS frontend

WORKDIR /app

# Install deps
COPY package.json package-lock.json ./
RUN npm install

# Copy required files for Vite
COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

# Build frontend
RUN npm run build


# =========================================================
#  BACKEND (PHP + APACHE)
# =========================================================
FROM php:8.3-apache-bookworm

# ---------------------------------------------------------
# 1️⃣ System deps & PHP extensions
# ---------------------------------------------------------
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ---------------------------------------------------------
# 2️⃣ Apache configuration
# ---------------------------------------------------------
RUN a2enmod rewrite

# Railway uses 8080
RUN sed -ri 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# Laravel public directory
RUN sed -ri 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

# Allow .htaccess
RUN printf "<Directory /var/www/html/public>\n\
Options Indexes FollowSymLinks\n\
AllowOverride All\n\
Require all granted\n\
</Directory>\n" >> /etc/apache2/apache2.conf

# ---------------------------------------------------------
# 3️⃣ Workdir
# ---------------------------------------------------------
WORKDIR /var/www/html

# ---------------------------------------------------------
# 4️⃣ Composer
# ---------------------------------------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy full Laravel app FIRST (so helpers.php exists)
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction


# ---------------------------------------------------------
# 5️⃣ Copy Laravel app
# ---------------------------------------------------------
COPY . .

# ---------------------------------------------------------
# 6️⃣ Copy built frontend assets (CRITICAL)
# ---------------------------------------------------------
COPY --from=frontend /app/public/build public/build

# ---------------------------------------------------------
# 7️⃣ Storage & permissions
# ---------------------------------------------------------
RUN php artisan storage:link || true

RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# ---------------------------------------------------------
# 8️⃣ Environment (minimal safe defaults)
# ---------------------------------------------------------
RUN touch .env && \
    echo "APP_NAME=HostelHub" >> .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_DEBUG=false" >> .env && \
    echo "APP_KEY=base64:$(openssl rand -base64 32 | tr -d '\n')" >> .env

# ---------------------------------------------------------
# 9️⃣ Expose & start
# ---------------------------------------------------------
EXPOSE 8080

CMD ["apache2-foreground"]
