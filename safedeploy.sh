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
        cp -r "storage/app/public/$folder" "public/storage/" 2>/dev/null || :
    fi
done

echo "✓ All folders copied"

# 3. Database migration (safe)
php artisan migrate --force

# 4. Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Start server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}