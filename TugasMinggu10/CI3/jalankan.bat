@echo off
REM Menjalankan CodeIgniter 3 (Toko Buah) di http://localhost:8090
cd /d "%~dp0"
where php >nul 2>nul || (echo [ERROR] PHP tidak ditemukan di PATH. & pause & exit /b 1)
echo.
echo  Aplikasi berjalan di http://localhost:8090  (tekan Ctrl+C untuk berhenti)
echo.
start "" http://localhost:8090
REM index.php dipakai sebagai router agar URL tanpa index.php tetap bekerja
php -S localhost:8090 index.php
