@echo off
echo ============================================
echo HostelHub Storage Upload to Railway
echo ============================================
cd /d C:\laragon\www\HostelHub

echo Step 1: Creating ZIP file...
powershell Compress-Archive -Path storage -DestinationPath storage.zip -Force

echo Step 2: Starting Python server...
start cmd /k "python -m http.server 8000"

echo Step 3: Your Local IP Address is:
ipconfig | findstr "IPv4"

echo.
echo ============================================
echo IMPORTANT: Follow these steps in Railway SSH
echo ============================================
echo 1. railway ssh
echo 2. cd /var/www/html
echo 3. curl -L -o storage.zip http://YOUR_IP:8000/storage.zip
echo 4. unzip storage.zip
echo 5. php artisan storage:link
echo ============================================
pause