#!/bin/bash

APP_CONTAINER = captcha-client
UID = $(shell id -u)

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#' | awk '{gsub("Makefile", "")}1'

run: ## Start the containers
	docker network create captcha-network || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
	cp -n .env.dist .env.development.local || true
	U_ID=${UID} docker-compose up -d

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

ssh: ## SSH into the Node container
	U_ID=${UID} docker exec -it ${APP_CONTAINER} bash

install-dependencies: ## Install dependencies
	U_ID=${UID} docker exec -it ${APP_CONTAINER} npm install

start-dev: ## Runs local development server
	U_ID=${UID} docker exec -it ${APP_CONTAINER} npm run start

build: ## Build prod package
	U_ID=${UID} docker exec -it ${APP_CONTAINER} npm run build

lint: ## Run Linter
	U_ID=${UID} docker exec -it ${APP_CONTAINER} npm run lint

.PHONY: build
