# Hướng dẫn sử dụng Docker cho Doctor Booking System

## Yêu cầu hệ thống
- Docker Desktop (Windows/Mac) hoặc Docker Engine (Linux)
- Docker Compose v3.8+
- Tối thiểu 4GB RAM

## Cấu trúc Docker

Project sử dụng các services sau:
- **app**: PHP 7.4-FPM (Laravel Application)
- **nginx**: Web server
- **mysql**: MySQL 8.0 Database
- **redis**: Cache và Queue
- **elasticsearch**: Search engine
- **queue**: Laravel Queue Worker
- **scheduler**: Laravel Task Scheduler

## Cài đặt & Khởi chạy

### 1. Clone project và chuẩn bị môi trường

```bash
# Copy file .env.example sang .env
cp .env.example .env
```

### 2. Build và khởi chạy containers

```bash
# Build và khởi chạy tất cả services
docker-compose up -d --build

# Hoặc chỉ build không chạy
docker-compose build

# Khởi chạy sau khi đã build
docker-compose up -d
```

### 3. Cài đặt Laravel

```bash
# Truy cập vào container app
docker-compose exec app bash

# Cài đặt dependencies (nếu chưa có)
composer install

# Tạo application key
php artisan key:generate

# Tạo JWT secret key
php artisan jwt:secret

# Chạy migrations và seeders
php artisan migrate --seed

# Tạo symbolic link cho storage
php artisan storage:link

# Thoát khỏi container
exit
```

### 4. Cài đặt NPM (nếu cần)

```bash
# Nếu bạn muốn build assets
npm install
npm run dev
# hoặc
npm run production
```

## Truy cập ứng dụng

- **Website**: http://localhost:8000
- **MySQL**: localhost:3307 (username: doctorbooking, password: root)
- **Redis**: localhost:6380
- **Elasticsearch**: http://localhost:9200

## Các lệnh thường dùng

### Quản lý containers

```bash
# Xem danh sách containers đang chạy
docker-compose ps

# Xem logs
docker-compose logs -f

# Xem logs của một service cụ thể
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f mysql

# Dừng tất cả containers
docker-compose stop

# Khởi động lại containers
docker-compose restart

# Dừng và xóa containers
docker-compose down

# Dừng và xóa containers kèm volumes (XÓA TOÀN BỘ DATABASE!)
docker-compose down -v
```

### Làm việc với Laravel

```bash
# Chạy artisan commands
docker-compose exec app php artisan [command]

# Ví dụ:
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:list

# Chạy composer
docker-compose exec app composer install
docker-compose exec app composer update

# Truy cập bash trong container
docker-compose exec app bash

# Chạy tinker
docker-compose exec app php artisan tinker
```

### Làm việc với Database

```bash
# Import database từ file SQL
docker-compose exec mysql mysql -u root -proot doctorbooking < db_doctorbooking.sql

# Export database
docker-compose exec mysql mysqldump -u root -proot doctorbooking > backup.sql

# Truy cập MySQL CLI
docker-compose exec mysql mysql -u root -proot doctorbooking
```

### Làm việc với Queue

```bash
# Xem logs của queue worker
docker-compose logs -f queue

# Restart queue worker
docker-compose restart queue

# Chạy queue worker manually (trong container app)
docker-compose exec app php artisan queue:work --sleep=3 --tries=3
```

### Làm việc với Elasticsearch

```bash
# Kiểm tra health của Elasticsearch
curl http://localhost:9200/_cluster/health?pretty

# Xem các indices
curl http://localhost:9200/_cat/indices?v

# Index lại data từ Laravel
docker-compose exec app php artisan [elasticsearch-command]
```

## Xử lý sự cố

### 1. Port đã được sử dụng

Nếu gặp lỗi port đã được sử dụng, bạn có thể thay đổi ports trong `docker-compose.yml`:

```yaml
# Ví dụ thay đổi port của nginx từ 8000 sang 8080
nginx:
  ports:
    - "8080:80"
```

### 2. Permission denied

```bash
# Fix permissions cho storage và cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### 3. Rebuild từ đầu

```bash
# Dừng và xóa tất cả
docker-compose down -v

# Xóa images
docker-compose down --rmi all

# Build lại từ đầu
docker-compose up -d --build
```

### 4. Xem chi tiết lỗi

```bash
# Xem logs chi tiết
docker-compose logs -f [service_name]

# Xem Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log
```

## Performance Tuning

### 1. Optimize Laravel

```bash
# Cache configurations
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Clear all caches khi development
docker-compose exec app php artisan optimize:clear
```

### 2. Tăng resources cho Docker

Trong Docker Desktop Settings:
- Resources > Advanced
- Tăng CPUs và Memory theo nhu cầu

## Production Deployment

### 1. Cập nhật Dockerfile

Đảm bảo Dockerfile sử dụng production settings:
- `composer install --optimize-autoloader --no-dev`
- Set `APP_ENV=production` trong `.env`
- Set `APP_DEBUG=false`

### 2. Sử dụng docker-compose.prod.yml

Tạo file riêng cho production với các cấu hình bảo mật cao hơn.

### 3. Backup định kỳ

Thiết lập cronjob để backup database và files quan trọng.

## Cấu trúc thư mục Docker

```
.
├── Dockerfile                    # Build image cho PHP application
├── docker-compose.yml           # Orchestration file
├── .dockerignore               # Files bỏ qua khi build
├── docker/
│   ├── nginx/
│   │   └── conf.d/
│   │       └── default.conf    # Nginx configuration
│   └── php/
│       └── local.ini           # PHP configuration
└── README.Docker.md            # File này
```

## Support

Nếu gặp vấn đề, hãy kiểm tra:
1. Docker Desktop đã chạy chưa
2. Có đủ dung lượng ổ đĩa không
3. Có đủ RAM không
4. Các port có bị conflict không
5. Kiểm tra logs: `docker-compose logs -f`

