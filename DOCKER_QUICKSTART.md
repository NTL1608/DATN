# 🚀 Hướng Dẫn Nhanh - Docker Setup

## Cách 1: Sử dụng Script Tự Động (Khuyến nghị)

### Windows:
```bash
# Chạy file setup
docker-setup.bat
```

### Linux/Mac:
```bash
# Cấp quyền thực thi
chmod +x docker-setup.sh

# Chạy script
./docker-setup.sh
```

## Cách 2: Sử dụng Makefile (Nhanh và tiện lợi)

### Cài đặt lần đầu:
```bash
make fresh-install
```

### Các lệnh thường dùng:
```bash
make help           # Xem tất cả lệnh có sẵn
make up             # Khởi chạy containers
make down           # Dừng containers
make logs           # Xem logs
make shell          # Truy cập bash
make migrate        # Chạy migrations
make cache-clear    # Clear cache
```

## Cách 3: Thủ Công (Step by Step)

### Bước 1: Chuẩn bị file .env
```bash
# Nếu chưa có file .env.example, copy từ file mẫu Docker
cp .env.docker .env

# Hoặc nếu đã có .env.example
cp .env.example .env
```

### Bước 2: Build và khởi chạy
```bash
docker-compose up -d --build
```

### Bước 3: Cài đặt Laravel
```bash
# Chờ MySQL khởi động (khoảng 15 giây)
timeout 15  # Windows
sleep 15    # Linux/Mac

# Cài đặt dependencies
docker-compose exec app composer install

# Generate keys
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan jwt:secret

# Set permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache

# Chạy migrations
docker-compose exec app php artisan migrate --seed

# Tạo storage link
docker-compose exec app php artisan storage:link
```

### Bước 4: Truy cập ứng dụng
Mở trình duyệt và truy cập: **http://localhost:8000**

## ✅ Kiểm Tra Cài Đặt

```bash
# Kiểm tra containers đang chạy
docker-compose ps

# Kiểm tra MySQL
docker-compose exec mysql mysql -u root -proot -e "SHOW DATABASES;"

# Kiểm tra Redis
docker-compose exec redis redis-cli ping

# Kiểm tra Elasticsearch
curl http://localhost:9200
```

## 🔧 Xử Lý Sự Cố Nhanh

### Port đã được sử dụng?
Sửa ports trong `docker-compose.yml`:
```yaml
nginx:
  ports:
    - "8080:80"  # Thay 8000 thành 8080
```

### Permission denied?
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### MySQL không kết nối được?
```bash
# Restart MySQL
docker-compose restart mysql

# Hoặc kiểm tra logs
docker-compose logs mysql
```

### Cần reset toàn bộ?
```bash
# Xóa tất cả và cài lại
docker-compose down -v
make fresh-install
```

## 📝 Thông Tin Kết Nối

| Service | Host | Port | Username | Password |
|---------|------|------|----------|----------|
| Web | localhost | 8000 | - | - |
| MySQL | localhost | 3307 | doctorbooking | root |
| Redis | localhost | 6380 | - | - |
| Elasticsearch | localhost | 9200 | - | - |

## 📚 Tài Liệu Chi Tiết

Xem file **README.Docker.md** để biết thêm thông tin chi tiết về:
- Cấu trúc Docker
- Các lệnh nâng cao
- Performance tuning
- Production deployment
- Troubleshooting

## 🎯 Làm Gì Tiếp Theo?

1. ✅ Truy cập http://localhost:8000
2. ✅ Đăng nhập với tài khoản từ seeder
3. ✅ Kiểm tra các chức năng
4. ✅ Bắt đầu development!

## 🆘 Cần Trợ Giúp?

```bash
# Xem logs để tìm lỗi
docker-compose logs -f

# Xem logs Laravel
docker-compose exec app tail -f storage/logs/laravel.log

# Truy cập container để debug
docker-compose exec app bash
```

---

**Happy Coding! 🚀**










