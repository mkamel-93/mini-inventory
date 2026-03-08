# Define the path to your docker-compose file
COMPOSE_FILE      = .docker/docker-compose.yml
DOCKER_ENV        = .docker/.env.docker
ROOT_ENV          = .env

# Use a double dollar sign to force the shell to evaluate the variable
COMPOSE_CMD = docker compose \
	-f $(COMPOSE_FILE) \
	--env-file $(ROOT_ENV) \
 	--project-directory . \
	--project-name $$(val=$$(grep -m1 '^DOCKER_APP_NAME=' $(ROOT_ENV) | cut -d'=' -f2-); echo $${val:-mostafa-project})

.PHONY: symlink init-env sync-env destroy build rebuild-container up down \
        restart conf ps php-bash web-bash logs logs-watch log-php reset-data test

# Load environment variables from .env if it exists
-include .env
export

# make symlink to docker-compose into the root project
symlink:
	@if [ ! -L docker-compose.yml ]; then \
		echo "Creating symbolic link for docker-compose.yml..."; \
		ln -s $(COMPOSE_FILE) docker-compose.yml; \
	fi

# create .env if not exists
init-env:
	@if [ ! -f $(ROOT_ENV) ]; then \
		if [ ! -f .env.example ]; then \
			echo "Creating .env.example with Docker markers..."; \
			echo "# Start Docker Configuration" > .env.example; \
			echo "" >> .env.example; \
			echo "# End Docker Configuration" >> .env.example; \
		fi; \
		echo "Creating $(ROOT_ENV) from .env.example..."; \
		cp .env.example $(ROOT_ENV); \
	else \
		echo "$(ROOT_ENV) already exists. Skipping."; \
	fi

# SYNC: This runs whenever .env.docker changes
sync-env: init-env
	@echo "Updating Docker section in root .env..."
	@# Delete old data between markers in root .env using temporary file
	@sed '/# Start Docker Configuration/,/# End Docker Configuration/{//!d}' $(ROOT_ENV) > $(ROOT_ENV).tmp
	@# Read and insert variables from .env.docker
	@sed '/# Start Docker Configuration/r $(DOCKER_ENV)' $(ROOT_ENV).tmp > $(ROOT_ENV)
	@rm -f $(ROOT_ENV).tmp

destroy: sync-env
	$(COMPOSE_CMD) down --rmi all --volumes --remove-orphans
build: sync-env
	$(COMPOSE_CMD) build --build-arg USER_ID=$(shell id -u) --build-arg GROUP_ID=$(shell id -g)
	$(COMPOSE_CMD) up -d --build

rebuild-container:
	@$(MAKE) destroy
	@$(MAKE) build

up: sync-env
	$(COMPOSE_CMD) up -d
down: sync-env
	$(COMPOSE_CMD) down --remove-orphans

restart: sync-env
	@$(MAKE) down
	@$(MAKE) up

conf: sync-env
	$(COMPOSE_CMD) config

ps: sync-env
	docker ps --filter "network=$${DOCKER_APP_NAME}" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}\t{{.Networks}}"
php-bash: sync-env
	$(COMPOSE_CMD) exec --user www-data php bash
web-bash: sync-env
	$(COMPOSE_CMD) exec --user nginx web bash

logs: sync-env
	$(COMPOSE_CMD) logs
logs-watch: sync-env
	$(COMPOSE_CMD) logs --follow
log-php: sync-env
	$(COMPOSE_CMD) logs php

reset-data: sync-env
	$(COMPOSE_CMD) exec -T --user www-data php sh -c 'php artisan migrate:refresh --seed'

test: sync-env
	$(COMPOSE_CMD) exec -T --user www-data php sh -c 'composer run-script full:test'
