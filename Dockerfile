FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# USER ko fix garnu
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

# Sabai file ownership change garnu
RUN chown -R www:www /var/www

# Switch to non-root user
USER www

# Storage directories create garnu with proper permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/framework/cache/data \
    && mkdir -p storage/logs \
    && chmod -R 775 storage \
    && chmod -R 775 bootstrap/cache

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-zip

# Expose port
EXPOSE 8000

# Start command
CMD php artisan serve --host=0.0.0.0 --port=8000