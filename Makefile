.DEFAULT_GOAL := start

# Current user ID and group ID.
export UID=$(shell id -u)
export GID=$(shell id -g)

start: env up composer

env:
	cp .env.example .env

up:
	docker compose up -d --remove-orphans

composer:
	composer i

migrate:
	docker compose up project-migrate

ps:
	docker compose ps

logs:
	docker compose logs -f
