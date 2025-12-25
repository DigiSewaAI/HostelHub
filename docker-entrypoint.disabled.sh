#!/bin/bash

echo "🚀 HostelHub Starting..."
PORT=${PORT:-8080}
echo "Port: $PORT"

# 1️⃣ Apache MPM र modules setup
echo "Configuring Apache modules..."
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork rewrite expires headers 2>/dev/null || true

# 2️⃣ Apache port सेट गर्ने
echo "Listen ${PORT}" > /etc/apache2/ports.conf

# 3️⃣ Health check alias configuration
echo "Configuring Apache health check alias..."
cat > /etc/apache2/conf-available/health.conf << 'EOF'
Alias /health /var/www/html/public/health.php
<Directory /var/www/html/public>
    Require all granted
</Directory>
<Location /health>
    Require all granted
</Location>
EOF
a2enconf health 2>/dev/null || true

# 4️⃣ Laravel setup
cd /var/www/html
echo "Running Laravel setup..."
php artisan storage:link --force 2>/dev/null || true

# 5️⃣ Health check endpoint बनाउने
echo "Creating health check endpoint..."
cat > public/health.php << 'EOF'
<?php
http_response_code(200);
header("Content-Type: text/plain");
echo "OK";
EOF

# 6️⃣ Apache सुरु गर्ने
echo "Starting Apache..."
exec apache2-foreground