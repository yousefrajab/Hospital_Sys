@echo off
title Hospital Management System
cd /d "%~dp0"

echo [1/3] Checking MySQL...
tasklist /FI "IMAGENAME eq mysqld.exe" | find /I "mysqld.exe" >nul
if errorlevel 1 (
    echo     Starting XAMPP MySQL...
    start "MySQL" /min "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
    timeout /t 5 /nobreak >nul
) else (
    echo     MySQL is already running.
)

echo [2/3] Opening browser...
start "" "http://127.0.0.1:8000/login"

echo [3/3] Starting Laravel server on http://127.0.0.1:8000
echo     Close this window to stop the server.
php artisan serve --host=127.0.0.1 --port=8000
pause
