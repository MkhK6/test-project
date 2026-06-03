SHELL := /bin/sh

COMPOSE_DEV := docker compose
COMPOSE_PROD := docker compose -f docker-compose.yml -f docker-compose.prod.yaml

.PHONY: init build up down logs ps composer-install migrate test test-db shell \
	up-prod down-prod logs-prod build-prod ps-prod validate

init:
	cp -n .env.example .env || true
	cp -n src/.env.example src/.env || true

build:
	$(COMPOSE_DEV) build

up:
	$(COMPOSE_DEV) up -d --build

down:
	$(COMPOSE_DEV) down

logs:
	$(COMPOSE_DEV) logs -f --tail=200

ps:
	$(COMPOSE_DEV) ps

composer-install:
	$(COMPOSE_DEV) exec php-fpm composer install --no-interaction --prefer-dist --optimize-autoloader

migrate:
	$(COMPOSE_DEV) exec php-fpm php artisan migrate --force

test-db:
	@TEST_DB_NAME=$${POSTGRES_TEST_DB:-laravel_test}; \
	$(COMPOSE_DEV) exec postgres psql -U $${POSTGRES_USER:-laravel} -d $${POSTGRES_DB:-laravel} -tc \
		"SELECT 1 FROM pg_database WHERE datname = '$$TEST_DB_NAME'" | grep -q 1 \
		|| $(COMPOSE_DEV) exec postgres psql -U $${POSTGRES_USER:-laravel} -d $${POSTGRES_DB:-laravel} \
		-c "CREATE DATABASE \"$$TEST_DB_NAME\";"

test: test-db
	$(COMPOSE_DEV) exec php-fpm php artisan test

shell:
	$(COMPOSE_DEV) exec php-fpm sh

build-prod:
	$(COMPOSE_PROD) build

up-prod:
	$(COMPOSE_PROD) up -d --build

down-prod:
	$(COMPOSE_PROD) down

logs-prod:
	$(COMPOSE_PROD) logs -f --tail=200

ps-prod:
	$(COMPOSE_PROD) ps

validate:
	$(COMPOSE_DEV) config >/dev/null
	$(COMPOSE_PROD) config >/dev/null
