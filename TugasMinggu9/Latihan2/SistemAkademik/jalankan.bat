@echo off
REM ==========================================================
REM  Menjalankan aplikasi SIAKAD CI4 di http://localhost:8081
REM  Dipanggil dari Visual Studio (tasks.vs.json) atau double-click.
REM ==========================================================
cd /d "%~dp0"

where php >nul 2>nul || (echo [ERROR] PHP tidak ditemukan di PATH. Lihat Readme.md bagian Prasyarat. & pause & exit /b 1)

if not exist vendor\autoload.php (
    echo [INFO] Folder vendor belum ada, menjalankan composer install...
    call composer install --no-interaction || (echo [ERROR] composer install gagal. & pause & exit /b 1)
)

if not exist .env (
    echo [INFO] Membuat file .env dari template env...
    copy env .env >nul
    echo CI_ENVIRONMENT = development>> .env
)

if not exist writable\siakad.db (
    echo [INFO] Database belum ada, membuat tabel dan data contoh...
    php spark migrate --all
    php spark db:seed DatabaseSeeder
) else (
    php spark migrate --all
)

echo.
echo  Aplikasi berjalan di http://localhost:8081  (tekan Ctrl+C untuk berhenti)
echo.
start "" http://localhost:8081
php spark serve --port 8081
