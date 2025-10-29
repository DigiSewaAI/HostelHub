# Use PHP 8.3 with Apache
FROM php:8.3-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Set working directory
WORKDIR /var/www/html

# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Create ALL necessary Laravel directories with proper structure
RUN mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/testing \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache

# Set full permissions for build process
RUN chmod -R 777 storage bootstrap/cache

# Install dependencies WITHOUT running scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Now manually create the cache files that Laravel needs
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

# Generate application key
RUN if [ ! -f .env ]; then \
        cp .env.example .env; \
    fi
RUN php artisan key:generate --force || true

# Change Apache root to Laravel /public directory
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Run package discovery manually (this should work now)
RUN php artisan package:discover --ansi || true

# Run migrations and create storage link
RUN php artisan migrate --force || true
RUN php artisan storage:link || true

# Cache configuration for production
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Fix permissions for web server
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]