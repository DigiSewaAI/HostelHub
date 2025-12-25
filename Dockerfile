# =========================================================
#  FRONTEND BUILD (VITE / NODE)
# =========================================================
FROM node:22 AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY resources resources
COPY vite.config.js .
COPY public public

RUN npm run build


# =========================================================
#  BACKEND (PHP + APACHE)
# =========================================================
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

# 2.1️⃣ Apache directory config
RUN echo '<Directory /var/www/html>' >> /etc/apache2/apache2.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/apache2.conf && \
    echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

# 3️⃣ MPM configuration
RUN printf '<IfModule mpm_prefork_module>\n\
    StartServers            5\n\
    MinSpareServers         5\n\
    MaxSpareServers        10\n\
    MaxRequestWorkers      150\n\
    MaxConnectionsPerChild   0\n\
</IfModule>\n' > /etc/apache2/mods-enabled/mpm.conf

# 4️⃣ Apache VirtualHost (Laravel public)
RUN echo '<VirtualHost *:8080>' > /etc/apache2/sites-available/000-default.conf && \
    echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    ErrorLog ${APACHE_LOG_DIR}/error.log' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    CustomLog ${APACHE_LOG_DIR}/access.log combined' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# 5️⃣ Railway port
RUN sed -ri 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# 6️⃣ Workdir
WORKDIR /var/www/html

# 7️⃣ Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 8️⃣ Dummy artisan (composer safety)
RUN touch artisan && echo "<?php echo 'Dummy artisan';" > artisan

# 9️⃣ Copy composer files
COPY composer.json composer.lock ./

# 🔟 Install deps (no scripts)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 1️⃣1️⃣ Remove dummy artisan
RUN rm -f artisan

# 1️⃣2️⃣ Copy full app
COPY . .

# ✅ COPY BUILT FRONTEND ASSETS (CRITICAL FIX)
COPY --from=frontend /app/public/build public/build

# 1️⃣3️⃣ Storage symlink (media)
RUN php artisan storage:link || \
    (mkdir -p public/storage && ln -sf ../storage/app/public public/storage)

# 1️⃣4️⃣ Permissions
RUN mkdir -p bootstrap/cache storage/framework/sessions storage/framework/views storage/framework/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# 1️⃣5️⃣ Package discover
RUN php artisan package:discover --no-interaction || true

# 1️⃣6️⃣ Minimal .env
RUN touch .env && \
    echo "APP_NAME=HostelHub" >> .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_DEBUG=false" >> .env && \
    echo "APP_KEY=base64:$(openssl rand -base64 32 | tr -d '\n')" >> .env

# 1️⃣7️⃣ Entrypoints
COPY safe_deploy.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/safe_deploy.sh

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 1️⃣8️⃣ Expose
EXPOSE 8080

# 1️⃣9️⃣ Start
CMD ["/usr/local/bin/docker-entrypoint.sh"]
