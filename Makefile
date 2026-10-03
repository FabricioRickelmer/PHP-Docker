.PHONY: up down build rebuild restart logs migrate fresh seed test phpstan cs-fix cs-check shell help

help:
	@echo "Available commands:"
	@echo "  make up        - Start containers in detached mode"
	@echo "  make down      - Stop containers"
	@echo "  make build     - Build containers"
	@echo "  make rebuild   - Rebuild and start containers"
	@echo "  make restart   - Restart containers"
	@echo "  make logs      - View application logs"
	@echo "  make migrate   - Run database migrations"
	@echo "  make fresh     - Wipe database and re-run all migrations"
	@echo "  make seed      - Run database seeders"
	@echo "  make test      - Run application tests"
	@echo "  make phpstan   - Run PHPStan static analysis"
	@echo "  make cs-fix    - Run PHP CS Fixer to fix code style"
	@echo "  make cs-check  - Run PHP CS Fixer in dry-run mode"
	@echo "  make shell     - Open shell in app container"

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build

rebuild:
	docker compose up -d --build

restart:
	docker compose restart

logs:
	docker compose logs -f app

migrate:
	docker compose exec app php artisan migrate

fresh:
	docker compose exec app php artisan migrate:fresh --seed

seed:
	docker compose exec app php artisan db:seed

test:
	docker compose exec app php artisan test

phpstan:
	docker compose exec app ./vendor/bin/phpstan analyse --memory-limit=256M

cs-fix:
	docker compose exec app ./vendor/bin/php-cs-fixer fix

cs-check:
	docker compose exec app ./vendor/bin/php-cs-fixer fix --dry-run --diff

shell:
	docker compose exec app sh
