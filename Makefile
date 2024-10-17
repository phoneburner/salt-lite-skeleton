SHELL := bash

app = docker compose run --rm web

build:
	docker compose build --pull
	$(app) mkdir --parents build
	$(app) cp --no-clobber .env.example .env
	$(app) cp --no-clobber phpstan.dist.neon phpstan.neon
	$(app) cp --no-clobber phpunit.dist.xml phpunit.xml
	$(app) composer install

.PHONY: clean
clean:
	$(app) -rf ./build ./vendor html/phpunit
	$(app) find /var/www/storage/ -type f -not -name .gitignore -delete

.PHONY: up
up:
	docker compose up --detach

.PHONY: down
down:
	docker compose down --remove-orphans

.PHONY: bash
bash: build
	@$(app) bash

.PHONY: lint
lint: build
	@$(app) composer run-script lint

.PHONY: test
test: build
	@$(app) composer run-script test

.PHONY: test-coverage
test-coverage: build
	@$(app) composer run-script test-coverage

.PHONY: phpcs
phpcs: build
	@$(app) composer run-script phpcs

.PHONY: phpcbf
phpcbf: build
	@$(app) composer run-script phpcbf

.PHONY: phpstan
phpstan: build
	@$(app) composer run-script phpstan

.PHONY: rector
rector: build
	@$(app) composer run-script rector

.PHONY: ci
ci: build
	@$(app) composer run-script ci