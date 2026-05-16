#!/bin/bash

echo "========================================="
echo "Doctor Booking - Docker Setup Script"
echo "========================================="
echo ""

# Chuyen ve thu muc chua script
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR" || exit 1

# Kiểm tra Docker
if ! command -v docker &> /dev/null
then
    echo "❌ Docker chưa được cài đặt. Vui lòng cài Docker Desktop trước."
    exit 1
fi

if command -v docker-compose &> /dev/null; then
    DC="docker-compose"
elif docker compose version &> /dev/null; then
    DC="docker compose"
else
    echo "❌ Docker Compose chưa được cài đặt."
    exit 1
fi

echo "✅ Docker và Docker Compose đã được cài đặt"
echo ""

# Tạo file .env nếu chưa có
# Tao file .env
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "📝 Tạo file .env từ .env.example..."
        cp .env.example .env
        echo "✅ Đã tạo file .env"
    elif [ -f .env.docker ]; then
        echo "📝 Tạo file .env từ .env.docker..."
        cp .env.docker .env
        echo "✅ Đã tạo file .env"
    elif [ -f env-docker-example.txt ]; then
        echo "📝 Tạo file .env từ env-docker-example.txt..."
        cp env-docker-example.txt .env
        echo "✅ Đã tạo file .env"
    else
        echo "⚠️  Không tìm thấy file mẫu (.env.example hoặc .env.docker). Bạn cần tạo file .env thủ công."
    fi
else
    echo "✅ File .env đã tồn tại"
fi
echo ""

# Build và khởi chạy containers
echo "🔨 Building Docker containers..."
$DC build
echo ""

echo "🚀 Khởi chạy các services..."
$DC up -d
echo ""

# Chờ MySQL khởi động
echo "⏳ Chờ MySQL khởi động..."
sleep 15
echo ""

# Cài đặt dependencies
echo "📦 Cài đặt Composer dependencies..."
$DC exec -T app composer install --optimize-autoloader
echo ""

# Generate keys
echo "🔑 Tạo Application Key..."
$DC exec -T app php artisan key:generate
echo ""

echo "🔑 Tạo JWT Secret..."
$DC exec -T app php artisan jwt:secret
echo ""

# Set permissions
echo "🔒 Thiết lập permissions..."
$DC exec -T app chmod -R 775 storage bootstrap/cache
$DC exec -T app chown -R www-data:www-data bootstrap/cache
echo ""

# Run migrations
echo "🗄️  Chạy database migrations..."
$DC exec -T app php artisan migrate --seed
echo ""

# Create storage link
echo "🔗 Tạo symbolic link cho storage..."
$DC exec -T app php artisan storage:link
echo ""

# Clear caches
echo "🧹 Clear caches..."
$DC exec -T app php artisan config:clear
$DC exec -T app php artisan cache:clear
$DC exec -T app php artisan view:clear
echo ""

echo "========================================="
echo "✅ Setup hoàn tất!"
echo "========================================="
echo ""
echo "Ứng dụng đang chạy tại: http://localhost:8000"
echo ""
echo "Thông tin truy cập services:"
echo "  - MySQL: localhost:3307 (user: doctorbooking, pass: root)"
echo "  - Redis: localhost:6380"
echo "  - Elasticsearch: http://localhost:9200"
echo ""
echo "Các lệnh hữu ích:"
echo "  - Xem logs: docker-compose logs -f"
echo "  - Dừng containers: docker-compose stop"
echo "  - Khởi động lại: docker-compose restart"
echo "  - Truy cập bash: docker-compose exec app bash"
echo ""
echo "Chi tiết xem thêm trong README.Docker.md"
echo ""



