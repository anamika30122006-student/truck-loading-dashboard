@echo off
title Logistics Pro Management System
echo ========================================================
echo        Starting Logistics Pro Management System
echo ========================================================
echo.
echo Checking PHP installation...
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP is not installed or not added to your PATH!
    echo Please install PHP from https://windows.php.net/download/ or install XAMPP.
    echo.
    pause
    exit /b
)

echo [OK] PHP is available.
echo Starting server on http://localhost:8000 ...
echo Press Ctrl + C anytime in this window to stop the server.
echo.

start http://localhost:8000
php -S 127.0.0.1:8000 -t public
pause
