@echo off
echo =========================================
echo Doctor Booking - Docker Setup Script
echo =========================================
echo.

REM Chuyen ve thu muc chua script
cd /d "%~dp0"

REM Kiểm tra Docker
where docker >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Docker chua duoc cai dat. Vui long cai Docker Desktop truoc.
    pause
    exit /b 1
)

where docker-compose >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    set "DC=docker-compose"
) else (
    docker compose version >nul 2>nul
    if %ERRORLEVEL% EQU 0 (
        set "DC=docker compose"
    ) else (
        echo [ERROR] Docker Compose chua duoc cai dat.
        pause
        exit /b 1
    )
)

echo [OK] Docker va Docker Compose da duoc cai dat
echo.

REM Tạo file .env nếu chưa có
if not exist ".env" (
    if exist ".env.example" (
        echo [INFO] Tao file .env tu .env.example...
        copy ".env.example" ".env" >nul
        echo [OK] Da tao file .env
    ) else (
        if exist ".env.docker" (
            echo [INFO] Tao file .env tu .env.docker...
            copy ".env.docker" ".env" >nul
            echo [OK] Da tao file .env
        ) else (
            if exist "env-docker-example.txt" (
                echo [INFO] Tao file .env tu env-docker-example.txt...
                copy "env-docker-example.txt" ".env" >nul
                echo [OK] Da tao file .env
            ) else (
                echo [WARNING] Khong tim thay file mau (.env.example hoac .env.docker). Ban can tao file .env thu cong.
            )
        )
    )
) else (
    echo [OK] File .env da ton tai
)
echo.

REM Build và khởi chạy containers
echo [INFO] Building Docker containers...
call %DC% build
echo.

echo [INFO] Khoi chay cac services...
call %DC% up -d
echo.

REM Chờ MySQL khởi động
echo [INFO] Cho MySQL khoi dong...
timeout /t 15 /nobreak >nul
echo.

REM Cài đặt dependencies
echo [INFO] Cai dat Composer dependencies...
call %DC% exec -T app composer install --optimize-autoloader
echo.

REM Generate keys
echo [INFO] Tao Application Key...
call %DC% exec -T app php artisan key:generate
echo.

echo [INFO] Tao JWT Secret...
call %DC% exec -T app php artisan jwt:secret
echo.

REM Set permissions
echo [INFO] Thiet lap permissions...
call %DC% exec -T app chmod -R 775 storage bootstrap/cache
call %DC% exec -T app chown -R www-data:www-data storage bootstrap/cache
echo.

REM Run migrations
echo [INFO] Chay database migrations...
call %DC% exec -T app php artisan migrate --seed
echo.

REM Create storage link
echo [INFO] Tao symbolic link cho storage...
call %DC% exec -T app php artisan storage:link
echo.

REM Clear caches
echo [INFO] Clear caches...
call %DC% exec -T app php artisan config:clear
call %DC% exec -T app php artisan cache:clear
call %DC% exec -T app php artisan view:clear
echo.

echo =========================================
echo [OK] Setup hoan tat!
echo =========================================
echo.
echo Ung dung dang chay tai: http://localhost:8000
echo.
echo Thong tin truy cap services:
echo   - MySQL: localhost:3307 (user: doctorbooking, pass: root)
echo   - Redis: localhost:6380
echo   - Elasticsearch: http://localhost:9200
echo.
echo Cac lenh huu ich:
echo   - Xem logs: %DC% logs -f
echo   - Dung containers: %DC% stop
echo   - Khoi dong lai: %DC% restart
echo   - Truy cap bash: %DC% exec app bash
echo.
echo Chi tiet xem them trong README.Docker.md
echo.
pause



