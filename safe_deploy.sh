#!/bin/bash
echo "=== HostelHub Deployment Script ==="

# 1. Storage link
php artisan storage:link

# 2. Copy all folders from storage to public
echo "Copying images and folders..."
folders=("classic_optimized" "dark_optimized" "documents" "galleries" "gallery" "hero" "hostel" "hostels" "hostel_logos" "images" "meals" "room_images")

for folder in "${folders[@]}"; do
    if [ -d "storage/app/public/$folder" ]; then
        echo "  Copying: $folder"
        mkdir -p "public/storage/$folder"
        cp -r "storage/app/public/$folder/"* "public/storage/$folder/" 2>/dev/null || :
    fi
done

echo "✓ All folders copied"

# 3. Database migration (safe)
php artisan migrate --force

# 4. Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Start Apache server (Railway uses PORT environment variable)
echo "Starting Apache server on port ${PORT:-8080}..."
# Configure Apache to use Railway's PORT
sed -i "s/Listen 8080/Listen ${PORT:-8080}/g" /etc/apache2/ports.conf
sed -i "s/:8080/:${PORT:-8080}/g" /etc/apache2/sites-enabled/*.conf
exec apache2-foreground