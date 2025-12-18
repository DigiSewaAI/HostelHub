@echo off
echo =====================================
echo 🔒 SAFE IMAGE COPY - HOSTELHUB
echo =====================================
echo.

cd /d "C:\laragon\www\HostelHub"

echo [1/3] Locked files बन्द गर्दै...
taskkill /f /im photos.exe 2>nul
taskkill /f /im Microsoft.Photos.exe 2>nul
taskkill /f /im "Windows Photo Viewer" 2>nul

echo [2/3] Gallery र Hostel images मात्र कपी गर्दै...
xcopy "storage\app\public\galleries\*" "public\storage\galleries\" /E /I /H /Y /C
xcopy "storage\app\public\hostels\*" "public\storage\hostels\" /E /I /H /Y /C

echo [3/3] Documents folder लाई छोड्दै...
echo (Documents folder लाई skip गरियो किनभने तपाईंलाई public मा चाहिँदैन)

echo.
echo ✅ Images कपी सफल भयो!
echo Gallery images: 
dir /b "public\storage\galleries\*.jpg" 2>nul | find /c /v ""
echo Hostel images:
dir /b "public\storage\hostels\*.jpg" 2>nul | find /c /v ""
pause