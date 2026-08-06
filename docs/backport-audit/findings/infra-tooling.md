# Infra & tooling — findings

Comparison of the template `{{cookiecutter.project_name}}/` against a set of
reference WordPress projects (src/app layout). One reference project uses a
divergent layout and is excluded from prevalence judgements. Prevalence is given
in neutral bands ("near-universal", "most", "a majority", "about half", "a few").

## Highest-signal common patterns
1. **PHP 8.1 → 8.3 across the whole stack** — in most reference projects. Touches composer.json, compose images, Dockerfile, php-ini mount path, deploy ansible php-fpm names. Dominant pattern.
2. **Xdebug 2 → 3 Dockerfile cleanup** — template writes Xdebug-2 ini keys (`xdebug.remote_*`) while compose passes Xdebug-3 env → **step-debugging silently broken**. A majority of reference projects dropped the ini block entirely. *Template-internal bug.*
3. **nginx `gzip_disable` curly-quote fix** — template uses curly smart-quotes → no-op/syntax error. Fixed in a few reference projects. Unambiguous correctness fix. *Template-internal bug.*
4. **`WP_REDIS_HOST=redis` with no redis service** — near-universal: the dead constant is carried everywhere and no project ever added a redis service. *Template-internal bug — fix by removal.*
5. php-ini runtime tuning — a majority add `max_execution_time`/`pm.max_children`/`memory_limit` (values vary).
6. Shared internal GHCR image namespace — near-universal, **template already current** (no change needed).

## Findings

### I1 — PHP 8.1 → 8.3 across the stack  ★ top priority
- **What:** composer.json `require.php` `8.1.*`→`8.3.*` + add `config.platform.php: "8.3"`; compose `wp-cli-php-8.3`/`composer-php-8.3` images; php-ini mount path `/etc/php/8.1/`→`/etc/php/8.3/`; deploy ansible `php8.1-fpm`→`php8.3-fpm`.
- **Where:** in most reference projects.
- **Effort:** med, no reparam. ⚠ keep the ini mount path consistent — at least one project bumped the image but left its ini mount at `/etc/php/8.1/`, which is a bug to avoid.
- **Recommendation:** **backport.**

### I2 — Dockerfile: official php:8.3-fpm + bcmath + drop Xdebug-2 block
- **What:** base `phpdockerio/php:8.1-fpm`→official `php:8.3-fpm` with explicit `php8.3-*` extensions + `docker-php-ext-install bcmath`; **remove the Xdebug-2 ini block** (`xdebug.remote_*` written to `20-xdebug.ini`, at `{{cookiecutter.project_name}}/docker/php-fpm/Dockerfile:14-21`) and the dead `XDEBUG_REMOTE_HOST`/`XDEBUG_IDEKEY` build args; rely on compose Xdebug-3 env.
- **Where:** a majority of reference projects (official base + dropped xdebug ini).
- **Why:** official image better maintained; missing `bcmath` was a real extension bug observed in practice; Xdebug-2 keys are ignored by Xdebug 3 (step-debugging silently fails).
- **Effort:** med, no reparam.
- **Recommendation:** **backport.** Discuss `XDEBUG_MODE` value: template uses `develop`; some reference projects use `debug` (the mode that actually enables step-debugging).

### I3 — nginx.conf gzip_disable smart-quote fix
- **What:** curly quotes → straight quotes on `gzip_disable`; drop the `server {` trailing space. File: `{{cookiecutter.project_name}}/docker/files/config/nginx.conf` (`gzip_disable` line ~18; `server {` line 1).
- **Where:** fixed in a few reference projects; template + several others still broken. Unambiguous.
- **Effort:** low, no reparam. **Recommendation:** **backport.**

### I4 — Remove dead WP_REDIS_HOST from development.php
- **What:** remove `WP_REDIS_HOST=redis` at `{{cookiecutter.project_name}}/config/environments/development.php:6` (compose has no redis service). Do NOT add redis (no reference project did).
- **Where:** near-universal non-adoption confirms it's dead config.
- **Effort:** low. **Recommendation:** **backport** (removal).

### I5 — sync/remote_to_local.sh: MariaDB sandbox-mode strip + elasticpress sync rename
- **What:** sed out the `M!999999`/sandbox-mode comment that newer mariadb-dump prepends (breaks `wp db import`); add `-T` to `docker compose exec`; elasticpress `index`→`sync` command rename. File: `{{cookiecutter.project_name}}/scripts/sync/remote_to_local.sh`.
- **Where:** sandbox strip in about half; elasticpress rename common.
- **Why:** recurring real fix — newer MariaDB dumps abort import without the strip.
- **Effort:** med (STAGES already parameterized).
- **Recommendation:** **backport** the sandbox strip + elasticpress rename. Other sync tweaks are project-specific.

### I6 — php-ini-overrides.ini runtime tuning
- **What:** add `max_execution_time`/`pm.max_children`/`memory_limit` to `{{cookiecutter.project_name}}/docker/php-fpm/php-ini-overrides.ini`.
- **Where:** a majority of reference projects, but values vary widely (max_execution_time ~60–1200, pm.max_children ~25–100, memory_limit 512M to unlimited).
- **Recommendation:** **discuss** — backport the *keys* with conservative defaults (e.g. `max_execution_time=120`), not any one project's values. Skip project-specific extensions such as `v8js.so`.

### I7 — nginx remote-uploads @fallback to prod CDN
- **What:** `location @fallback` rewriting missing `/app/uploads/...` to the production domain (`resolver 8.8.8.8`) — enables local dev without syncing all media.
- **Where:** in about half of reference projects. Reparam prod domain → `{{cookiecutter.domain_prod}}`.
- **Recommendation:** **discuss** — nice generic dev feature but adds an external resolver. Skip headless reverse-proxy blocks (project-specific).

### I8 — Drop debug-bar / debug-bar-elasticpress from require-dev
- **Where:** a majority of reference projects dropped them (query-monitor is the kept tool).
- **Recommendation:** **discuss** — mild cleanup.

### Skip (project-specific)
- application.php domain integrations (S3, payments, CRM, headless flags) — each in only one project.
- update.sh per-project plugin lists; Makefile `open_ci` CI-tool links (churning); headless reverse-proxy nginx blocks.
- Note: a few reference projects use an env-substituted `nginx.conf.template` (`${PHP_HOST}` etc.) instead of the static `nginx.conf` — a structural shift, worth a separate discussion if the team wants env-driven nginx.

## Confirmed template-internal bugs (fix regardless of backport)
- (a) Xdebug 2 ini keys vs Xdebug 3 env — fixed by a majority of reference projects → **I2** (`docker/php-fpm/Dockerfile:14-21`)
- (b) nginx curly quotes — fixed by a few → **I3** (`docker/files/config/nginx.conf` ~line 18)
- (c) WP_REDIS_HOST with no redis — near-universal non-adoption → **I4** (`config/environments/development.php:6`)

## Ranked backport set
1. I1 PHP 8.1→8.3 everywhere — backport, med
2. I2 Dockerfile official 8.3 + bcmath + Xdebug-3 cleanup — backport, med
3. I3 nginx smart-quote fix — backport, low
4. I4 remove WP_REDIS_HOST — backport, low
5. I5 sync MariaDB sandbox strip + elasticpress rename — backport, med
6. I6 php-ini keys — discuss, low
7. I7 nginx remote-uploads fallback — discuss, med
8. I8 drop debug-bar dev deps — discuss, low
