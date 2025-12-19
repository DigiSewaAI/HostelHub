#!/bin/bash

echo "🚀 HostelHub Starting..."
PORT=${PORT:-8080}
echo "Port: $PORT"

# MPM conflict fix
echo "Ensuring only prefork MPM is enabled..."
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Apache port सेट गर्ने
echo "Listen ${PORT}" > /etc/apache2/ports.conf

# Laravel setup
cd /var/www/html
php artisan storage:link --force 2>/dev/null || true

# ✅✅✅ IMPORTANT: Create health.php in public folder
echo "Creating health check endpoint..."
echo '<?php' > public/health.php
echo 'http_response_code(200);' >> public/health.php
echo 'header("Content-Type: text/plain");' >> public/health.php
echo 'echo "OK";' >> public/health.php
chmod 644 public/health.php

# Apache सुरु गर्ने
echo "Starting Apache..."
exec apache2-foreground