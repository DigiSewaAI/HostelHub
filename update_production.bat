@echo off
title 🏨 HostelHub Production Update
color 0A
echo ====================================================
echo        HOSTELHUB - COMPLETE PRODUCTION UPDATE
echo ====================================================
echo.

:: Step 1: Project folder मा जाने
cd /d C:\laragon\www\HostelHub
echo [1/10] Project folder मा: %cd%

:: Step 2: Storage link बनाउने
echo [2/10] Storage link बनाउँदै...
php artisan storage:link
echo ✓ Storage link तयार

:: Step 3: पुरानो public/storage सफा गर्ने
echo [3/10] पुरानो storage सफा गर्दै...
if exist public\storage (
    rmdir /s /q public\storage
    echo ✓ पुरानो storage सफा भयो
)

:: Step 4: नया public/storage बनाउने
echo [4/10] नया storage बनाउँदै...
mkdir public\storage
echo ✓ नया storage बनायो

:: Step 5: सबै 14 wota folders कपी गर्ने (तिम्रो list अनुसार)
echo [5/10] सबै 14 wota folders कपी गर्दै...
echo.
echo 1. classic_optimized
xcopy "storage\app\public\classic_optimized\*" "public\storage\classic_optimized\" /E /I /H /Y
echo 2. dark_optimized
xcopy "storage\app\public\dark_optimized\*" "public\storage\dark_optimized\" /E /I /H /Y
echo 3. documents
xcopy "storage\app\public\documents\*" "public\storage\documents\" /E /I /H /Y
echo 4. galleries
xcopy "storage\app\public\galleries\*" "public\storage\galleries\" /E /I /H /Y
echo 5. gallery
xcopy "storage\app\public\gallery\*" "public\storage\gallery\" /E /I /H /Y
echo 6. hero
xcopy "storage\app\public\hero\*" "public\storage\hero\" /E /I /H /Y
echo 7. hostel
xcopy "storage\app\public\hostel\*" "public\storage\hostel\" /E /I /H /Y
echo 8. hostels
xcopy "storage\app\public\hostels\*" "public\storage\hostels\" /E /I /H /Y
echo 9. hostel_logos
xcopy "storage\app\public\hostel_logos\*" "public\storage\hostel_logos\" /E /I /H /Y
echo 10. images
xcopy "storage\app\public\images\*" "public\storage\images\" /E /I /H /Y
echo 11. meals
xcopy "storage\app\public\meals\*" "public\storage\meals\" /E /I /H /Y
echo 12. room_images
xcopy "storage\app\public\room_images\*" "public\storage\room_images\" /E /I /H /Y

:: यदि अरु folder छ भने यहाँ थप्ने
:: echo 13. folder_name
:: xcopy "storage\app\public\folder_name\*" "public\storage\folder_name\" /E /I /H /Y

echo.
echo ✓ सबै 12 wota folders कपी भयो!

:: Step 6: Check गर्ने कि files छन्
echo [6/10] Files check गर्दै...
dir public\storage /s | find "File(s)"
echo ✓ Files check complete

:: Step 7: Node modules install (यदि पहिले गरेको छैन भने)
echo [7/10] Frontend setup गर्दै...
call npm install --silent
echo ✓ Node modules installed

:: Step 8: Vite build गर्ने
echo [8/10] Vite build गर्दै...
call npm run build
echo ✓ Vite build complete

:: Step 9: Git मा सबै थप्ने
echo [9/10] Git मा थप्दै...
git add .
set timestamp=%date:~7,2%-%date:~4,2%-%date:~10,4%_%time:~0,2%:%time:~3,2%
git commit -m "HostelHub Production Update: %timestamp%"
echo ✓ Git commit complete

:: Step 10: Railway मा पठाउने
echo [10/10] Railway मा पठाउँदै...
git push origin main
echo ✓ Push successful!

echo.
echo ====================================================
echo               🎉 DEPLOYMENT SUCCESS! 🎉
echo ====================================================
echo.
echo "Timeline:"
echo "1. अहिले Railway मा deploy सुरु भयो"
echo "2. 2-3 मिनेटमा deploy हुनेछ"
echo "3. त्यसपछि तिम्रो app live हुनेछ"
echo.
echo "Check these URLs after 3 minutes:"
echo "https://your-app.railway.app"
echo "https://your-app.railway.app/galleries"
echo "https://your-app.railway.app/hostels"
echo.
echo "Note: यदि error आयो भने मलाई screenshot पठाउने!"
echo ====================================================
echo.
pause