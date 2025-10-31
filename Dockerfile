# Use official PHP 8.3 image with Apache
FROM php:8.3-apache

# Set environment variable for Render detection
ENV RENDER=true

# Enable required Apache modules
RUN a2enmod rewrite headers

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        gd \
        zip \
        bcmath \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js 20 (CRITICAL FIX - Updated from 18 to 20)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Copy Composer from official Composer image
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all project files
COPY . .

# Copy deploy script
COPY deploy.sh /deploy.sh
RUN chmod +x /deploy.sh

# Ensure writable directories exist
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache

# Fix permissions
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install Node.js dependencies with retry and timeout
RUN npm install --registry https://registry.npmjs.org/ --timeout=600000

# Build assets (CRITICAL FOR UI - Added this missing step)
RUN npm run build

# Set Apache document root to Laravel public directory
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Fix ownership for all project files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Expose Apache port
EXPOSE 80

# Health check (Render-friendly)
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Use the deploy script
CMD ["/bin/bash", "/deploy.sh"]