.DEFAULT_GOAL := up

# Current user ID and group ID.
export UID=$(shell id -u)
export GID=$(shell id -g)

up:
	docker compose up -d --remove-orphans

migrate:
	docker compose up project-migrate

ps:
	docker compose ps

logs:
	docker compose logs -f
