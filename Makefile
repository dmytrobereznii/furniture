-include .env.deploy

PROD_DIR := /var/www/sasha

up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear docker-pull docker-build docker-up composer-update key migrate
db-rebuild: rollback migrate

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

composer-install:
	docker-compose run --rm php-cli composer install

composer-update:
	docker-compose run --rm php-cli composer update

rollback:
	docker-compose run --rm php-cli php artisan migrate:rollback

migrate:
	docker-compose run --rm php-cli php artisan migrate

seed:
	docker-compose run --rm php-cli php artisan db:seed

key:
	docker-compose run --rm php-cli php artisan key:generate

artisan:
	docker-compose run --rm php-cli php artisan $(c)

require:
	docker-compose run --rm php-cli composer require $(p)

feature:
	docker-compose run --rm php-cli php artisan test --testsuite=Feature

test:
	docker-compose run --rm php-cli php artisan test

check:
	@test "$$(git branch --show-current)" = master || { echo 'Switch to master first'; exit 1; }
	@test -z "$$(git status --porcelain)" || { echo 'Commit or discard local changes first'; exit 1; }
	@git fetch -q origin master
	@test "$$(git rev-parse HEAD)" = "$$(git rev-parse origin/master)" || { echo 'Local master differs from GitHub'; exit 1; }

prod-host:
	@test -n "$(PROD_HOST)" || { echo 'Set PROD_HOST in .env.deploy'; exit 1; }

deploy: prod-host check test
	ssh $(PROD_HOST) 'cd $(PROD_DIR) && git pull --ff-only origin master && bash scripts/deploy.sh'

status: prod-host
	ssh $(PROD_HOST) 'cd $(PROD_DIR) && git log -1 --oneline && git status --short && systemctl is-active nginx php8.5-fpm mysql'

logs: prod-host
	ssh $(PROD_HOST) 'sudo tail -n 50 $(PROD_DIR)/storage/logs/laravel.log'
