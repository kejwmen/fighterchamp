COMPOSE_EXEC_PHP = docker-compose exec php
COMPOSE_EXEC_CONSOLE = ${COMPOSE_EXEC_PHP} bin/console

up:
	docker-compose up -d

docker-build:
	docker-compose build

composer-install:
	${COMPOSE_EXEC_PHP} composer install

composer-update:
	${COMPOSE_EXEC_PHP} composer update

db-migrate:
	${COMPOSE_EXEC_CONSOLE} doctrine:migrations:migrate

db-create:
	${COMPOSE_EXEC_CONSOLE} doctrine:database:create

db-update-schema:
	${COMPOSE_EXEC_CONSOLE} doctrine:schema:update --force

db-drop:
	${COMPOSE_EXEC_CONSOLE} doctrine:database:drop --force
