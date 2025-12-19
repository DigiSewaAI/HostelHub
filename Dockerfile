FROM php:8.3-apache

# 1. System deps & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Apache rewrite + Laravel public
RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Workdir
WORKDIR /var/www/html

# 4. Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. App files
COPY . .

# 6. Laravel dirs + perms
RUN mkdir -p bootstrap/cache storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 7. Composer install
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 🚫 NO PORT SED
# 🚫 NO EXPOSE NEEDED

# 8. Start Apache (DEFAULT 80)
CMD ["apache2-foreground"]
