build:
	docker compose build
up:
	docker compose up -d
up-logs:
	docker compose up
down:
	docker compose down
stop:
	docker compose stop
start:
	docker compose start
php-bash:
	docker compose exec -it php-gitbot bash
php-logs:
	docker compose logs --follow php-gitbot
php-logs-f:
	docker compose logs --follow php-gitbot
nginx-bash:
	docker compose exec nginx-gitbot bash
nginx-logs:
	docker compose logs nginx-gitbot
nginx-logs-f:
	docker compose logs --follow php-gitbot
vendor:
	docker compose exec php-gitbot bash -c "composer install"
install: up vendor
	@cp .env.example .env && \
	docker compose exec php-gitbot bash -c "php artisan key:generate"
migrate:
	docker compose exec php-gitbot bash -c "php artisan migrate"
npm-install:
	@docker run -it --rm -v $$(pwd):/app -w /app --user 1000:1000 node:18.18 npm i
npm-build:
	@docker run -it --rm -v $$(pwd):/app -w /app --user 1000:1000 node:18.18 npm run build
npm-dev:
	@docker run -it --rm -v $$(pwd):/app -w /app --user 1000:1000 -p 5173:5173 node:18.18 npm run dev
vue-install:
	@docker run -it --rm -v $$(pwd):/app -w /app --user 1000:1000 node:18.18 npm i @vitejs/vue-plugin

