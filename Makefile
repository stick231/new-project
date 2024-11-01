up:
	docker-compose build --pull && docker-compose up -d --remove-orphans

start:
	docker-compose start

stop:
	docker-compose stop

down:
	docker-compose down -v

restart:
	docker-compose restart

shell:
	docker-compose exec --user devilbox api bash

mysql-shell:
	docker-compose exec -it mysql bash

restart-worker:
	docker-compose exec --user devilbox api php artisan horizon:terminate

logs:
	docker-compose logs -f
lint:
	docker-compose exec --user devilbox api ./vendor/bin/pint -v

install:
	docker compose exec -u devilbox api composer install --no-interaction --prefer-dist
	docker compose exec -u devilbox api artisan optimize:clear

migrate:
	docker compose exec -u devilbox api artisan migrate
