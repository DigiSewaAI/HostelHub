# FIXED Dockerfile (Dockerfile.txt सेव गर्ने):
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

# 🔥 FIX: Use PORT environment variable, NOT hardcoded 8080
RUN sed -ri 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# 3️⃣ Workdir
WORKDIR /var/www/html

# 4️⃣ Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5️⃣ App files - COPY ONLY NEEDED FILES
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Now copy the rest
COPY . .

# 6️⃣ Create .env file from Railway variables
RUN touch .env
RUN echo "APP_ENV=production" >> .env
RUN echo "APP_DEBUG=false" >> .env
RUN echo "LOG_CHANNEL=stderr" >> .env

# 7️⃣ Fix permissions
RUN mkdir -p bootstrap/cache storage/framework/sessions storage/framework/views storage/framework/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 8️⃣ Clear Laravel cache before starting
RUN php artisan optimize:clear

# 9️⃣ Expose Railway port
EXPOSE 8080

# 🔟 Use safe_deploy.sh as entrypoint
COPY safe_deploy.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/safe_deploy.sh
CMD ["/usr/local/bin/safe_deploy.sh"]