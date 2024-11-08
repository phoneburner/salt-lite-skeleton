SHELL := bash
.DEFAULT_GOAL := build

app = docker compose run --rm web

# Define behavior to safely source file (1) to dist file (2), without overwriting
# if the dist file already exists. This is more portable than using `cp --no-clobber`.
define copy-safe
	if [ ! -f "$(2)" ]; then \
		echo "Copying $(1) to $(2)"; \
		cp "$(1)" "$(2)"; \
	else \
		echo "$(2) already exists, not overwriting."; \
	fi
endef

# Define behavior to check if a token (1) is set in .env, and prompt user to set it if not.
# If the token is already set, inform the user. If the token name is not found in .env,
# it will be appended, otherwise, the existing value will be updated.
define check-token
	if grep -q "^$(1)=" ".env"; then \
		TOKEN_VALUE=$$(grep "^$(1)=" ".env" | cut -d'=' -f2); \
		if [ -z "$$TOKEN_VALUE" ]; then \
			read -p "Please enter your $(1): " NEW_TOKEN; \
			sed -i "s/^$(1)=.*/$(1)=$$NEW_TOKEN/" ".env"; \
			echo "$(1) updated successfully!"; \
		else \
			echo "$(1) is already set."; \
		fi; \
	else \
		read -p "$(1) not found. Please enter your $(1): " NEW_TOKEN; \
		echo -e "\n$(1)=$$NEW_TOKEN" >> ".env"; \
		echo "$(1) added successfully!"; \
	fi
endef

# Define behavior to generate a new 256-bit key for the application, if it is
# not already set in the .env file.
define generate-key
	if grep -q "^$(1)=" ".env"; then \
		KEY_VALUE=$$(grep "^$(1)=" ".env" | cut -d'=' -f2); \
		if [ -z "$$KEY_VALUE" ]; then \
			NEW_KEY=$$(docker compose run --rm web php -r 'echo "base64:" . \base64_encode(\random_bytes(32));'); \
			sed -i "s;^$(1)=.*;$(1)=$$NEW_KEY;" ".env"; \
			echo "New $(1) generated successfully!"; \
		else \
			echo "$(1) is already set."; \
		fi; \
	else \
		NEW_KEY=$$(docker compose run --rm web php -r 'echo "base64:" . \base64_encode(\random_bytes(32));'); \
		echo -e "$(1)=$$NEW_KEY" >> ".env"; \
		echo "New $(1) generated successfully!"; \
	fi
endef

phpunit.xml:
	@$(call copy-safe,phpunit.dist.xml,phpunit.xml)

phpstan.neon:
	@$(call copy-safe,phpstan.dist.neon,phpstan.neon)

.env:
	@$(call copy-safe,.env.example,.env)

# The build target dependencies must be set as "order-only" prerequisites to prevent
# the target from being rebuilt everytime the dependencies are updated.
build: | phpstan.neon phpunit.xml .env
	@$(call check-token,GITHUB_TOKEN)
	@docker compose build --pull
	@$(call generate-key,SALT_APP_KEY)
	@$(app) composer install
	@$(app) mkdir --parents build
	@touch build

.PHONY: clean
clean:
	$(app) rm -rf ./build ./vendor html/phpunit
	$(app) find /var/www/storage/ -type f -not -name .gitignore -delete

.PHONY: app-key
app-key: .env
	@$(call generate-key,SALT_APP_KEY)

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

.PHONY: rector-dry-run
rector-dry-run: build
	@$(app) composer run-script rector-dry-run

.PHONY: ci
ci: build
	@$(app) composer run-script ci