.DEFAULT_GOAL := help

# Run silent.
MAKEFLAGS += --silent

RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
$(eval $(RUN_ARGS):;@:)

# Current user ID and group ID.
export UID=$(shell id -u)
export GID=$(shell id -g)

up:
	docker compose up -d --remove-orphans

ps:
	docker compose ps

logs:
	docker compose logs -f
