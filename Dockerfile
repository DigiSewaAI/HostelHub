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

# 2️⃣ Apache config - MPM FIX
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true && \
    a2enmod mpm_prefork && \
    a2enmod rewrite

# 2.1️⃣ Create custom Apache config for health check
RUN echo '<Directory /var/www/html>' >> /etc/apache2/apache2.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/apache2.conf && \
    echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

# 3️⃣ MPM configuration
RUN printf '<IfModule mpm_prefork_module>\n    StartServers            5\n    MinSpareServers         5\n    MaxSpareServers        10\n    MaxRequestWorkers      150\n    MaxConnectionsPerChild   0\n</IfModule>\n' > /etc/apache2/mods-enabled/mpm.conf

# 4️⃣ Laravel public directory को लागि Apache configuration
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri 's!/var/www/!/var/www/html/public!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# 5️⃣ Set port to 8080 for Railway
RUN sed -ri 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# 6️⃣ Workdir
WORKDIR /var/www/html

# 7️⃣ Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 8️⃣ TEMPORARY: Create dummy artisan file for composer install
RUN touch artisan && echo "<?php echo 'Dummy artisan';" > artisan

# 9️⃣ Copy package files for caching
COPY composer.json composer.lock ./

# 🔟 Install dependencies WITH NO SCRIPTS
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 1️⃣1️⃣ Remove dummy artisan
RUN rm -f artisan

# 1️⃣2️⃣ Copy ALL application files
COPY . .

# 1️⃣3️⃣ Fix permissions
RUN mkdir -p bootstrap/cache storage/framework/sessions storage/framework/views storage/framework/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 1️⃣4️⃣ Run package discover manually (optional)
RUN php artisan package:discover --no-interaction 2>/dev/null || true

# 1️⃣5️⃣ Create .env with APP_KEY
RUN touch .env
RUN echo "APP_NAME=HostelHub" >> .env
RUN echo "APP_ENV=production" >> .env  
RUN echo "APP_DEBUG=false" >> .env
RUN echo "APP_KEY=base64:$(openssl rand -base64 32 | tr -d '\n')" >> .env
RUN echo "APP_URL=http://localhost" >> .env

# 1️⃣6️⃣ Copy deployment scripts
COPY safe_deploy.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/safe_deploy.sh

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 1️⃣7️⃣ Expose port
EXPOSE 8080

# 1️⃣8️⃣ Start with entrypoint
CMD ["/usr/local/bin/docker-entrypoint.sh"]