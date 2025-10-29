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

# Create ALL Laravel directories with proper permissions FIRST
RUN mkdir -p storage/framework/{cache,data,sessions,views} \
    && mkdir -p bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Install dependencies without running scripts initially
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Now run the composer scripts with proper permissions
RUN composer run-script post-autoload-dump

# Generate application key
RUN if [ ! -f .env ]; then \
        cp .env.example .env; \
    fi
RUN php artisan key:generate --force || true

# Change Apache root to Laravel /public directory
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Run migrations and create storage link
RUN php artisan migrate --force || true
RUN php artisan storage:link || true

# Cache configuration for production
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Fix permissions again for web server
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]