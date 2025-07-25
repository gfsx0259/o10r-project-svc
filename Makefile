.DEFAULT_GOAL := up

# Run silent.
MAKEFLAGS += --silent

# Current user ID and group ID.
export UID=$(shell id -u)
export GID=$(shell id -g)

up:
	docker compose up -d --remove-orphans

ps:
	docker compose ps

logs:
	docker compose logs -f
