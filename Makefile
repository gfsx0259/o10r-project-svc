.DEFAULT_GOAL := start

# Current user ID and group ID.
export UID=$(shell id -u)
export GID=$(shell id -g)

start: env up composer migrate

env:
	@if [ ! -f .env ]; then cp .env.example .env; fi

up:
	docker compose up -d --remove-orphans

composer:
	docker compose exec project composer install

migrate:
	docker compose exec project php yii migrate:up -q
