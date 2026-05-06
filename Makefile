.PHONY: help build up down restart logs shell composer artisan migrate cache-clear

help: ## Hiển thị trợ giúp
	@echo "Doctor Booking - Docker Commands"
	@echo "================================="
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Build Docker containers
	docker-compose build

up: ## Khởi chạy tất cả services
	docker-compose up -d

down: ## Dừng và xóa containers
	docker-compose down

restart: ## Khởi động lại containers
	docker-compose restart

logs: ## Xem logs (tất cả services)
	docker-compose logs -f

logs-app: ## Xem logs của app
	docker-compose logs -f app

logs-nginx: ## Xem logs của nginx
	docker-compose logs -f nginx

logs-mysql: ## Xem logs của mysql
	docker-compose logs -f mysql

shell: ## Truy cập bash trong container app
	docker-compose exec app bash

composer-install: ## Cài đặt composer dependencies
	docker-compose exec app composer install

composer-update: ## Update composer dependencies
	docker-compose exec app composer update

artisan: ## Chạy artisan command (sử dụng: make artisan CMD="migrate")
	docker-compose exec app php artisan $(CMD)

migrate: ## Chạy migrations
	docker-compose exec app php artisan migrate

migrate-fresh: ## Chạy migrate:fresh (XÓA TOÀN BỘ DATA!)
	docker-compose exec app php artisan migrate:fresh --seed

seed: ## Chạy database seeder
	docker-compose exec app php artisan db:seed

cache-clear: ## Clear all caches
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan view:clear
	docker-compose exec app php artisan route:clear

cache-optimize: ## Optimize caches cho production
	docker-compose exec app php artisan config:cache
	docker-compose exec app php artisan route:cache
	docker-compose exec app php artisan view:cache

tinker: ## Chạy Laravel Tinker
	docker-compose exec app php artisan tinker

test: ## Chạy tests
	docker-compose exec app php artisan test

db-import: ## Import database từ db_doctorbooking.sql
	docker-compose exec -T mysql mysql -u root -proot doctorbooking < db_doctorbooking.sql

db-export: ## Export database ra file backup.sql
	docker-compose exec mysql mysqldump -u root -proot doctorbooking > backup.sql

fresh-install: ## Cài đặt mới từ đầu
	make build
	make up
	sleep 15
	make composer-install
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan jwt:secret
	make migrate
	docker-compose exec app php artisan storage:link
	docker-compose exec app chmod -R 775 storage bootstrap/cache
	@echo "Setup completed! Visit http://localhost:8000"

clean: ## Dọn dẹp containers và volumes (XÓA TOÀN BỘ DATA!)
	docker-compose down -v
	docker-compose down --rmi all

ps: ## Xem trạng thái containers
	docker-compose ps

stats: ## Xem tài nguyên sử dụng
	docker stats

npm-install: ## Cài đặt NPM dependencies
	npm install

npm-dev: ## Build assets (development)
	npm run dev

npm-watch: ## Watch và build assets
	npm run watch

npm-prod: ## Build assets (production)
	npm run production










