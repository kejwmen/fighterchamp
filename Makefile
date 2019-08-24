COMPOSE_EXEC_PHP = docker-compose exec php
COMPOSE_EXEC_CONSOLE = ${COMPOSE_EXEC_PHP} bin/console

composer-install:
	${COMPOSE_EXEC_PHP} composer install

composer-update:
	${COMPOSE_EXEC_PHP} composer update

db-migrate:
	${COMPOSE_EXEC_CONSOLE} doctrine:migrations:migrate

db-create:
	${COMPOSE_EXEC_CONSOLE} doctrine:database:create

db-drop:
	${COMPOSE_EXEC_CONSOLE} doctrine:database:drop --force
