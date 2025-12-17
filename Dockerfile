FROM php:8.3-apache-bookworm

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Laravel public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ✅ Apache must listen on 8080 (Railway standard)
RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App code
WORKDIR /var/www/html
COPY . .

# Laravel permissions
RUN mkdir -p bootstrap/cache \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    && chown -R www-data:www-data storage bootstrap \
    && chmod -R 775 storage bootstrap

# Install PHP dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

EXPOSE 8080

CMD ["apache2-foreground"]
