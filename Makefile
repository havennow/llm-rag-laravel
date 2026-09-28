MAKE_BUILD=./.docker/build.sh
DOCKER_RUN :=docker compose

NIX_OS := $(shell uname -s)

ifeq ($(OS),Windows_NT)
	EXECUTABLE=winpty
else
	EXECUTABLE=
endif

ifeq ($(NIX_OS),Darwin)
	SED_ON=sed -i "" 's/OCTANE=0/OCTANE=1/g' .env
	SED_OFF=sed -i "" 's/OCTANE=1/OCTANE=0/g' .env
else
	SED_ON=sed -i 's/OCTANE=0/OCTANE=1/g' .env
	SED_OFF=sed -i 's/OCTANE=1/OCTANE=0/g' .env
endif

build: ## Build containers images
	$(MAKE_BUILD)

migrate: ## Build containers images
	clear && $(EXECUTABLE) docker run -it -v $(PWD):/var/www lcinstall bash -c "php artisan migrate && php artisan db:seed && php artisan route:clear"

install: ## Run composer, install vendor
	clear && $(DOCKER_RUN) up -d
	rm -rf vendor node_modules;\
	docker run -it -v $(PWD):/var/www lcinstall bash -c "composer install && yarn install"

clear: ## Run composer, install vendor
	$(DOCKER_RUN) down && clear && $(EXECUTABLE) docker run -it -v $(PWD):/var/www lcinstall bash -c "php artisan config:clear && php artisan cache:clear && php artisan route:clear"

start: ## Up containers with fpm
	export WEBSERVER_MODE=artisan ; \
	make env && $(SED_OFF) ; \
	$(DOCKER_RUN) up -d

start-octane: ## Up containers with swoole for octane
	export WEBSERVER_MODE=swoole ; \
	make env && $(SED_ON) ; \
	$(DOCKER_RUN) up -d

stop: ## Stop containers
	$(DOCKER_RUN) stop

ps: ## PS docker
	$(DOCKER_RUN) ps

web-socket: ## PS docker
	$(EXECUTABLE) $(DOCKER_RUN) exec core bash -c "php artisan notifications:websocket"

queue: ## Run the queue worker (QUEUE_CONNECTION do .env, fila default)
	$(EXECUTABLE) $(DOCKER_RUN) exec core bash -c "php artisan queue:work --sleep=1 --tries=2 --timeout=180"

logs: ## PS docker
	$(DOCKER_RUN) logs -f $(container)

test-phpstan: ## run phpstan
	clear && $(EXECUTABLE) $(DOCKER_RUN) exec core bash -c "./vendor/bin/phpstan analyse --memory-limit=2G -a /var/www/vendor/autoload.php $(phpfiles)"

test-pint: ## run pint
	clear && $(EXECUTABLE) $(DOCKER_RUN) exec core bash -c "./vendor/bin/pint"

env: ## Copy .env.example to .env
	if [ ! -f .env ] ; \
    then \
         cp .env.example .env ; \
    fi;

orphan-clear: ## Remove orphan container
	$(DOCKER_RUN) up -d --remove-orphan

shell: ## Access bash in, core container
	clear && $(EXECUTABLE) docker run -it --expose=9501 -v $(PWD):/var/www lcinstall bash

shell-container: ## Access bash in, core container
	clear && $(EXECUTABLE) $(DOCKER_RUN) exec core bash

service: ## Access bash in, core container
	clear && $(EXECUTABLE) docker run -it -v $(PWD):/var/www lcinstall bash -c "/usr/bin/supervisord -n -c /etc/supervisord.conf && supervisorctl start schedule"

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help
