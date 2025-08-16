@echo off
echo ========================================
echo    Kecamatan Ngluyu PWA Setup
echo ========================================
echo.

echo [1/5] Checking Git installation...
git --version >nul 2>&1
if %errorlevel% neq 0 (
    echo Git tidak terinstall. Silakan install Git terlebih dahulu:
    echo https://git-scm.com/download/win
    echo.
    echo Setelah install Git, jalankan script ini lagi.
    pause
    exit /b 1
)
echo Git sudah terinstall: 
git --version
echo.

echo [2/5] Setup Git repository...
git init
echo Repository Git berhasil diinisialisasi.
echo.

echo [3/5] Adding files to Git...
git add .
echo Semua file berhasil ditambahkan ke Git.
echo.

echo [4/5] Making initial commit...
git commit -m "Initial commit: Kecamatan Ngluyu PWA - Complete Progressive Web App with 6 features and admin panel"
echo Commit berhasil dibuat.
echo.

echo [5/5] Setup remote repository...
echo.
echo Sekarang ikuti langkah berikut:
echo.
echo 1. Buka https://github.com
echo 2. Login ke akun GitHub Anda
echo 3. Klik "New repository"
echo 4. Isi:
echo    - Repository name: kecamatan-ngluyu-pwa
echo    - Description: Aplikasi Layanan Kecamatan Ngluyu - Progressive Web App
echo    - Public
echo 5. Klik "Create repository"
echo 6. Copy URL repository (https://github.com/username/kecamatan-ngluyu-pwa.git)
echo 7. Jalankan perintah berikut di terminal:
echo.
echo    git remote add origin https://github.com/username/kecamatan-ngluyu-pwa.git
echo    git branch -M main
echo    git push -u origin main
echo.
echo Setelah selesai, aplikasi akan tersedia di GitHub!
echo.
pause
