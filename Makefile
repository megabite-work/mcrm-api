include docker/.env

DC = docker compose -f ./docker/docker-compose.yml
PHP = $(DC) exec -u www-data php
NGINX = $(DC) exec -it nginx
DB = $(DC) exec -it db
CI = composer install --prefer-dist --no-progress --no-scripts --no-interaction
DDC = bin/console d:d:c --if-not-exists
DMM = bin/console d:m:m -n
DIF = bin/console d:m:diff
BIN = bin/console

##################
# Docker compose
##################

dc_build:
	@$(DC) build

dc_start:
	@$(DC) start

dc_stop:
	@$(DC) stop

dc_up:
	@$(DC) up -d --remove-orphans

dc_ps:
	@$(DC) ps

dc_logs:
	@$(DC) logs -f

dc_down:
	@$(DC) down -v --rmi=local --remove-orphans


##################
# App
##################

php_bash:
	@$(PHP) bash

nginx_bash:
	@$(NGINX) bash

db_bash:
	@$(DB) sh

php_ci:
	@$(PHP) $(CI)

php_ddc:
	@$(PHP) $(DDC) 

php_dmm:
	@$(PHP) $(DMM)

php_dif:
	@$(PHP) $(DIF)

composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(PHP) composer $(c)

bc: ## Run bin/console, pass the parameter "c=" to run a given command, example: make bc c='make:entity'
	@$(eval c ?=)
	@$(PHP) $(BIN) $(c)

db_sql:
	@$(DB) psql -U ${DB_USER}