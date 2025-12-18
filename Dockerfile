FROM php:8.3-apache-bookworm

# System deps
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath gd pdo_mysql mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Apache config
RUN a2enmod rewrite
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App code
COPY . .

# 🔥 CRITICAL: move Laravel runtime storage to /tmp
RUN rm -rf storage/framework && \
    mkdir -p /tmp/framework/{cache,views,sessions} && \
    ln -s /tmp/framework storage/framework && \
    chown -R www-data:www-data storage /tmp

# Install deps
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

EXPOSE 8080

CMD sed -i "s/Listen 80/Listen ${PORT:-8080}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT:-8080}/g" /etc/apache2/sites-enabled/*.conf && \
    apache2-foreground
