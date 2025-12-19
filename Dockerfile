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

# 2️⃣ Apache config - FIX MPM DURING BUILD
RUN a2enmod rewrite
RUN a2dismod mpm_event mpm_worker
RUN a2enmod mpm_prefork

# 3️⃣ Force single MPM by editing config directly
RUN echo "LoadModule mpm_prefork_module /usr/lib/apache2/modules/mod_mpm_prefork.so" > /etc/apache2/mods-enabled/mpm.load
RUN echo "<IfModule mpm_prefork_module>\n    StartServers            5\n    MinSpareServers         5\n    MaxSpareServers        10\n    MaxRequestWorkers      150\n    MaxConnectionsPerChild   0\n</IfModule>" > /etc/apache2/mods-enabled/mpm.conf

# 4️⃣ Laravel public directory
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
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 8️⃣ Copy package files first for better caching
COPY composer.json composer.lock ./

# 9️⃣ Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 🔟 Copy the rest of the app
COPY . .

# 1️⃣1️⃣ Fix permissions
RUN mkdir -p bootstrap/cache storage/framework/sessions storage/framework/views storage/framework/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 1️⃣2️⃣ Create .env with APP_KEY during build
RUN touch .env && \
    echo "APP_NAME=HostelHub" >> .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_DEBUG=false" >> .env && \
    echo "APP_KEY=base64:$(openssl rand -base64 32)" >> .env && \
    echo "APP_URL=http://localhost" >> .env

# 1️⃣3️⃣ Copy and setup scripts
COPY safe_deploy.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/safe_deploy.sh

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 1️⃣4️⃣ Expose Railway port
EXPOSE 8080

# 1️⃣5️⃣ Start with entrypoint
CMD ["/usr/local/bin/docker-entrypoint.sh"]