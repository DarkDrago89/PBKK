@echo off
REM ==========================================================
REM  Menjalankan ShopEase (Authentication System) di http://localhost:8080
REM ==========================================================
cd /d "%~dp0"
where php >nul 2>nul || (echo [ERROR] PHP tidak ditemukan di PATH. & pause & exit /b 1)
if not exist vendor\autoload.php (
    echo [INFO] Menjalankan composer install...
    call composer install --no-interaction || (echo [ERROR] composer install gagal. & pause & exit /b 1)
)
if not exist .env (
    echo [INFO] Membuat .env...
    echo CI_ENVIRONMENT = development> .env
    echo app.baseURL = 'http://localhost:8080/'>> .env
)
if not exist writable\shopease.db (
    echo [INFO] Membuat database dan akun demo...
    php spark migrate --all
    php spark db:seed DatabaseSeeder
) else (
    php spark migrate --all
)
echo.
echo  ShopEase berjalan di http://localhost:8080  (tekan Ctrl+C untuk berhenti)
echo.
start "" http://localhost:8080
php spark serve --port 8080
