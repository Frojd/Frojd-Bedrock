{% raw %}# {% endraw %}{{cookiecutter.project_name}}{% raw %} — project recipes.
#
# These are shortcuts that combine the helper scripts in ./scripts and Docker
# into a few whole workflows. Run `make` (or `make help`) to see them all.
# Individual tools still live as scripts, e.g. ./scripts/wp.sh, ./scripts/composer.sh.

# Which server to copy content from. Override per run, e.g. `make resync stage=prod`.
stage ?= stage

SITE_URL := http://{% endraw %}{{cookiecutter.domain_prod}}.test:{{cookiecutter.docker_web_port}}{% raw %}
FRONTEND_DIR := src/app/themes/main/frontend

.DEFAULT_GOAL := help
.PHONY: help setup ensure-config bootstrap start resync

help: ## Show this help
	@echo ""
	@echo "  Getting a project running:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "    \033[36mmake %-10s\033[0m %s\n", $$1, $$2}'
	@echo ""

setup: ## Start a brand-new project (sets up git and git hooks). Run once, right after creating the project.
	./scripts/provision/setup_git.sh
	./scripts/provision/setup_git_flow.sh
	./scripts/provision/setup_envfiles.sh

# The compose services bind-mount these files. If any is missing when Docker
# runs, Docker creates it as an empty DIRECTORY in its place, breaking the
# setup. So refuse to run until they exist — they are created together by
# `make setup`.
REQUIRED_CONFIG := docker/config/web.env auth.json

ensure-config:
	@for f in $(REQUIRED_CONFIG); do \
		if [ ! -f "$$f" ]; then \
			echo "  Missing config file: $$f"; \
			echo "  Run 'make setup' first to create your env files and auth.json."; \
			exit 1; \
		fi; \
	done

bootstrap: ensure-config ## Create a clean, EMPTY site. Use this for a brand-new project or when you can't copy an existing database.
	docker compose up -d
	docker compose run --rm composer install
	cd $(FRONTEND_DIR) && npm install && npm run build
	docker compose run --rm wp-cli wp --allow-root core install \
		--url="$(SITE_URL)" \
		--title="{% endraw %}{{cookiecutter.public_site_name}}{% raw %}" \
		--admin_user="admin" \
		--admin_password="admin" \
		--admin_email="cms-admin@frojd.se" \
		--skip-email
	@$(MAKE) --no-print-directory banner
	docker compose up

start: ensure-config ## Start the site. First time on your machine it sets everything up and copies the site content; after that it just starts.
	docker compose up -d
	@if ./scripts/wp.sh core is-installed >/dev/null 2>&1; then \
		echo "  Site already set up — starting it."; \
	else \
		echo "  First time here — setting the project up (this can take a while)."; \
		docker compose run --rm composer install; \
		./scripts/sync/remote_to_local.sh $(stage); \
	fi
	cd $(FRONTEND_DIR) && npm install && npm run build
	@$(MAKE) --no-print-directory banner
	docker compose up

resync: ensure-config ## Copy the latest content from a server again and restart, e.g. `make resync stage=prod` (defaults to stage).
	docker compose up -d
	./scripts/sync/remote_to_local.sh $(stage)
	@$(MAKE) --no-print-directory banner

banner:
	@echo ""
	@echo "  ╭─ {% endraw %}{{cookiecutter.project_name}}{% raw %} ──────────────────────────────"
	@echo "  │ Your site is running at:  $(SITE_URL)"
	@echo "  │ WordPress admin:          $(SITE_URL)/wp/wp-admin"
	@echo "  │ Log in with:              admin / admin"
	@echo "  │ For hot reload run:       cd $(FRONTEND_DIR) && npm run dev"
	@echo "  ╰──────────────────────────────────────────────"
	@echo ""
{% endraw %}