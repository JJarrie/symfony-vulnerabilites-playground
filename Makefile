#################
# Configuration #
#################

# Define colors
ifndef CI_JOB_ID
	BLACK       := $(shell tput setaf 0)
	RED         := $(shell tput setaf 1)
	GREEN       := $(shell tput setaf 2)
	YELLOW      := $(shell tput setaf 3)
	LIGHTPURPLE := $(shell tput setaf 4)
	PURPLE      := $(shell tput setaf 5)
	BLUE        := $(shell tput setaf 6)
	WHITE       := $(shell tput setaf 7)
	BOLD        := $(shell tput bold)
	RESET       := $(shell tput sgr0)

	ANSI_BLACK := \033[30m
	ANSI_RED := \033[31m
	ANSI_GREEN := \033[32m
	ANSI_YELLOW := \033[33m
	ANSI_BLUE := \033[34m
	ANSI_MAGENTA := \033[35m
	ANSI_CYAN := \033[36m
	ANSI_WHITE := \033[37m

	ANSI_BRIGHT_BLACK := \033[30;1m
	ANSI_BRIGHT_RED := \033[31;1m
	ANSI_BRIGHT_GREEN := \033[32;1m
	ANSI_BRIGHT_YELLOW := \033[33;1m
	ANSI_BRIGHT_BLUE := \033[34;1m
	ANSI_BRIGHT_MAGENTA := \033[35;1m
	ANSI_BRIGHT_CYAN := \033[36;1m
	ANSI_BRIGHT_WHITE := \033[37;1m

	ANSI_BACKGROUND_BLACK := \033[40m
	ANSI_BACKGROUND_RED := \033[41m
	ANSI_BACKGROUND_GREEN := \033[42m
	ANSI_BACKGROUND_YELLOW := \033[43m
	ANSI_BACKGROUND_BLUE := \033[44m
	ANSI_BACKGROUND_MAGENTA := \033[45m
	ANSI_BACKGROUND_CYAN := \033[46m
	ANSI_BACKGROUND_WHITE := \033[47m

	ANSI_BACKGROUND_BRIGHT_BLACK := \033[40;1m
	ANSI_BACKGROUND_BRIGHT_RED := \033[41;1m
	ANSI_BACKGROUND_BRIGHT_GREEN := \033[42;1m
	ANSI_BACKGROUND_BRIGHT_YELLOW := \033[43;1m
	ANSI_BACKGROUND_BRIGHT_BLUE := \033[44;1m
	ANSI_BACKGROUND_BRIGHT_MAGENTA := \033[45;1m
	ANSI_BACKGROUND_BRIGHT_CYAN := \033[46;1m
	ANSI_BACKGROUND_BRIGHT_WHITE := \033[47;1m

	ANSI_RESET := \033[0m
	ANSI_BOLD := \033[1m
	ANSI_UNDERLINE := \033[4m
	ANSI_REVERSED := \033[7m
endif

TTY ?=
DOCKER_COMPOSE := docker compose

DOCKER_COMPOSE_FILES := compose.yaml
ifeq ($(shell test -f compose.override.yaml && echo -n yes), yes)
	DOCKER_COMPOSE_FILES := $(DOCKER_COMPOSE_FILES):compose.override.yaml
endif


PHP_CONTAINER = COMPOSE_FILE=$(DOCKER_COMPOSE_FILES) $(DOCKER_COMPOSE) exec $(TTY) php
PHP = $(PHP_CONTAINER)
CONSOLE = $(PHP) bin/console
COMPOSER = $(PHP_CONTAINER) composer
COMPOSER_INSTALL = install --no-interaction

PHPUNIT_INI_CONFIGURATION ?=
PHPUNIT_ARGS ?=

########################################################################################################################

##@ Help

.PHONY: help

MAX_SIZE_COMMAND := 20

help: ## Outputs this help screen
	@cat $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*##"; printf "\n${ANSI_YELLOW}Usage:${ANSI_RESET}\n  make <target>\n"} /^[a-zA-Z_0-9\-\/\.]+:.*?##/ { printf "  ${ANSI_GREEN}%-${MAX_SIZE_COMMAND}s${ANSI_RESET} %s\n", $$1, $$2 } /^##@/ { printf "\n${ANSI_YELLOW}%s:${ANSI_RESET}\n", substr($$0, 5) }'


##@ Docker

.PHONY: build up down

build: ## Build the Docker images
	@COMPOSE_FILES=$(DOCKER_COMPOSE_FILES) $(DOCKER_COMPOSE) build --pull --parallel

install: build start vendor assets/install database/setup database/load ## Build & setup the projet, then start it

start: ## Start the docker stack
	@COMPOSE_FILES=$(DOCKER_COMPOSE_FILES) $(DOCKER_COMPOSE) up -d

stop: ## Stop the docker stack
	@COMPOSE_FILES=$(DOCKER_COMPOSE_FILES) $(DOCKER_COMPOSE) down --remove-orphans

bash/php: ## Open a bash terminal in PHP container
	@${PHP_CONTAINER} bash

bash/nginx: ## Open a bash terminal in Nginx container
	@COMPOSE_FILE=$(DOCKER_COMPOSE_FILES) $(DOCKER_COMPOSE) exec $(TTY) nginx bash


##@ Composer

.PHONY: composer security outdated vendor

composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@${COMPOSER} $(c)

security: ## Check if vendors have security issues
	@${COMPOSER} audit --locked

outdated: ## Check if vendors have new versions
	@${COMPOSER} outdated

vendor: ## Install vendors according to the current composer.lock file
	@${COMPOSER} ${COMPOSER_INSTALL}


##@ Quality Tools

.PHONY: php-cs-checker php-cs-fixer phpstan

php-cs-checker: ## Run PHP Coding Standards Checker
	@${PHP} bash -c "export PHP_CS_FIXER_IGNORE_ENV=1 && ./vendor/bin/php-cs-fixer fix --allow-risky=yes --dry-run"

php-cs-fixer: ## Run PHP Coding Standards Fixer
	@${PHP} bash -c "export PHP_CS_FIXER_IGNORE_ENV=1 && ./vendor/bin/php-cs-fixer fix --allow-risky=yes"

phpstan: ## Run PHPStan
	@${PHP} ./vendor/bin/phpstan analyse --memory-limit=1G


##@ Tests

.PHONY: test test/unit test/functional

test: ## Run all tests
	@${PHP} ${PHPUNIT_INI_CONFIGURATION} bin/phpunit ${PHPUNIT_ARGS}

test/unit: ## Run unit tests
	@${PHP} ${PHPUNIT_INI_CONFIGURATION} bin/phpunit --testsuite=unit ${PHPUNIT_ARGS}

test/integration: ## Run integration tests
	@$(MAKE) -s database/setup env=test
	@$(MAKE) -s database/load env=test
	@${PHP} ${PHPUNIT_INI_CONFIGURATION} bin/phpunit --testsuite=integration ${PHPUNIT_ARGS}


##@ Asset Mapper

.PHONY: assets/install assets/outdated assets/security assets/build assets/watch

assets/install: ## Install the front dependencies
	@${CONSOLE} importmap:install

assets/outdated: ## Check if front dependencies have new versions
	@${CONSOLE} importmap:outdated || true

assets/security: ## Check if front dependencies have security issues
	@${CONSOLE} importmap:audit || true


##@ Translations

translation/debug: ## Debug translations for a specific language (ex: "make translation/debug lang=fr")
	@$(eval lang ?=)
	@${CONSOLE} debug:translation ${lang}

translation/extract: ## Update translations files for a specific language (ex: "make translation/extract lang=fr")
	@$(eval lang ?=)
	@${CONSOLE} translation:extract --force ${lang}


##@ Database

database/setup: ## Create and set schema at last migration
	@$(eval env ?= "dev")
	@${CONSOLE} doctrine:database:create -e ${env} --if-not-exists
	@${CONSOLE} doctrine:migrations:migrate -n -e ${env}

database/load: ## Load fixtures in database
	@$(eval env ?= "dev")
	@${CONSOLE} doctrine:fixtures:load -n -e ${env}

##@ Symfony console

console: ## Run a Symfony console command (ex: make console c="exploit:CVE-2024-51996")
	@$(eval c ?=)
	@${PHP_CONTAINER} bin/console ${c}