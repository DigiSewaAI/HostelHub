# -------------------------------
# ✅ Laravel Production Dockerfile
# PHP 8.3 + Apache + Composer + Optimized Laravel Setup
# -------------------------------

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

# Copy Composer from official Composer image
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all project files
COPY . .

# Copy environment files explicitly
#COPY .env.local .env.local
#COPY .env.production .env.production
COPY deploy.sh /deploy.sh

# Make deploy script executable
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

# Set Apache document root to Laravel public directory
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Create Apache VirtualHost
RUN echo '<VirtualHost *:80>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
        FallbackResource /index.php\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Fix ownership for all project files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Expose Apache port
EXPOSE 80

# Health check (Render-friendly)
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# ✅ FIXED: Use the external deploy.sh script instead of embedded one
CMD ["/bin/bash", "/deploy.sh"]