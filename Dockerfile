FROM php:8.3-apache-bookworm

# 1️⃣ System deps & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2️⃣ Apache config
RUN a2enmod rewrite

# Laravel public directory
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri 's!/var/www/!/var/www/html/public!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# 3️⃣ Workdir
WORKDIR /var/www/html

# 4️⃣ Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5️⃣ App files
COPY . .

# 6️⃣ Fix permissions
RUN mkdir -p bootstrap/cache storage/framework/sessions storage/framework/views storage/framework/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 7️⃣ Install deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 8️⃣ Clear cache
RUN php artisan optimize:clear

# 9️⃣ Copy deployment script
COPY safe_deploy.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/safe_deploy.sh

# 🔟 Expose port (Railway will handle port)
EXPOSE 8080

# Start with deployment script
CMD ["/usr/local/bin/safe_deploy.sh"]