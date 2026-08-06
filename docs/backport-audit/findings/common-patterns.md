# Backport audit — consolidated summary & triage list

Cross-area roll-up of all area findings. This is the **triage sheet**: review
each row, mark Decision (backport / skip / discuss). Prevalence is given in
neutral bands across the reference set (see `README.md` for the band meanings) —
no per-project data. "applicable" = among reference projects with a comparable
setup (e.g. a Vite frontend; headless projects excluded from theme-build counts).

## 0. Confirmed template-internal bugs (fix regardless of backport)
These aren't really "backports" — they're broken in the template, and the
reference set confirms it by either fixing them or never having them. Each row
has an exact file path + line so you can go straight to the source.

| ID | Bug | File:line (in `{{cookiecutter.project_name}}/`) | Fix | Decision |
|---|---|---|---|---|
| C4 | "Create .htaccess" task writes empty `shared/src/.env` (wrong path + name); template-only (no reference project has it) | `deploy/provision.yml:48-56` | delete the task block | ☐ |
| C3 | restarts `php8.1-fpm` while CI installs 8.3 (a majority restart 8.3) | `deploy/tasks/after-symlink.yml:3` | `php8.1-fpm`→`php8.3-fpm` | ☐ |
| I2b | writes Xdebug **2** ini keys (`xdebug.remote_*`); compose passes Xdebug **3** env → step-debug dead (a majority dropped the block) | `docker/php-fpm/Dockerfile:14-21` | remove the ARG/echo xdebug ini block; rely on compose env | ☐ |
| I3 | `gzip_disable` uses curly smart-quotes `“ ”` → no-op (some projects fixed it) | `docker/files/config/nginx.conf:18` (also `server {` trailing space line 1) | straight quotes `"..."` | ☐ |
| I4 | `WP_REDIS_HOST=redis` but no redis service exists (no reference project ever added redis) | `config/environments/development.php:6` | remove the constant | ☐ |
| T4 | theme enqueues `editor.css` but Vite never builds `editor.scss` (near-universal among applicable projects) | theme `gutenberg.php:111` enqueue vs `frontend/vite.config.js` input | add `styles/editor.scss` to vite input | ☐ |

## 1. Strong common backports (high prevalence, clear win)

| ID | Item | Prevalence | Effort | Decision |
|---|---|---|---|---|
| I1 | **PHP 8.1 → 8.3** across composer/Docker/ini-path/ansible | most | med | ☐ |
| **CI-SEC** | **Adopt the hardened CI reference model** — single reusable `deploy.yml` (prod+stage as jobs, NOT split) gated by a `ci.yml` with **Trivy** fs scan + **zizmor** Actions audit + SHA-pinned actions + explicit secrets + sed-rendered auth + **health-check** + **CycloneDX SBOM** + `workflow_dispatch` rollback + concurrency. ✅ **DECIDED — this is the target.** Supersedes C1. Full spec in `ci-deploy.md`. | chosen direction | high | ☑ |
| C2 | **Reusable notify-slack.yml** + notify-on-failure job (use the `cancelled`-aware version) | near-universal | low | ☐ |
| T1 | **Vite ^5 → ^8 + node ≥24** + dep bumps (theme) | near-universal (applicable) | low-med | ☐ |
| C5 | Node 22 → 24 in workflows (native in the new ci/deploy) | most | low | ☐ |
| ~~C1~~ | ~~Split deploy.yml → two files~~ — **WITHDRAWN** (superseded by CI-SEC single-file model) | — | — | ✗ |
| ~~C6~~ | SHA-pin actions — **absorbed into CI-SEC** (the reference workflows are already fully SHA-pinned) | near-universal | — | — |
| T2 | Font-name preservation in `assetFileNames` (preload-safe) | near-universal (applicable) | low | ☐ |
| T3 | `vite-plugin-static-copy` (kills the dev symlink hack) | near-universal (applicable) | low | ☐ |
| I2 | Dockerfile official `php:8.3-fpm` + bcmath | a majority | med | ☐ |
| I5 | sync: MariaDB sandbox-mode strip + elasticpress `index`→`sync` | about half | med | ☐ |

## 2. Discuss (split signal, value judgment, or value vs. effort)

| ID | Item | Prevalence | Note | Decision |
|---|---|---|---|---|
| D-A | Templated **CLAUDE.md** skeleton | about half (+ more with a `.claude/` dir) | highest-value convention add | ☐ |
| T5 | ESLint flat config (eslint 9) + lint/test npm scripts | most (applicable) | flat-config file not captured; add scripts regardless | ☐ |
| C7 | Composer cache strategy (cache keyed on `composer.lock`) | about half | the direction with associated "fix" history | ☐ |
| P2 | Add `wp-nested-pages` | a majority | default-on vs opt-in? | ☐ |
| P3 | Add `wp-smartcrop` | a majority | default-on vs opt-in? | ☐ |
| I6 | php-ini keys (`max_execution_time` etc.) conservative defaults | a majority | values vary; backport keys only | ☐ |
| I7 | nginx remote-uploads `@fallback` to prod CDN | about half | adds external resolver | ☐ |
| I8 | Drop `debug-bar`/`debug-bar-elasticpress` from require-dev | a majority | query-monitor superseded them | ☐ |
| C-sentry | Add `sentry_dsn` vars_prompt (matches existing `.env.j2`) | about half | latent template gap | ☐ |
| D-B | `.editorconfig` at repo root | one project | trivial "lead the fleet" | ☐ |
| T6 | theme.json (declarative block settings) | one project | overlaps existing gutenberg.php | ☐ |

## 3. Stale docs to fix (no source needed — high confidence)
README/SECURITY/provisioning errors, each with exact file:line. All template-internal.

| # | Issue | File:line | Decision |
|---|---|---|---|
| 1 | "Circle CI" listed as a feature (ships GitHub Actions, no `.circleci/`) | root `README.md:18` | ☐ |
| 2 | dead link to `setting-up-deployment-with-circleci.md` (real file is `...-github-actions.md`); `/blob/master/` → 404 (default branch is `main`) | root `README.md:82-83` | ☐ |
| 3 | "make sure your CircleCI project as access to server" (wrong tool + typo); Deployment section never documents the shipped auto-deploy | `{{cookiecutter.project_name}}/README.md:177` & §Deployment | ☐ |
| 4 | stale "Ubuntu 20.04+ / PHP 7.4+" requirements | `docs/provisioning-servers-for-hosting.md:9-13` | ☐ |
| 5 | prompts `ssh_prod`/`ssh_stage` don't match `cookiecutter.json` (`ssh_host_prod`/`ssh_host_stage`) | root `README.md:45-46` | ☐ |
| 6 | "Sage version 9 alpha" note years-stale | root `README.md:73` | ☐ |
| 7 | `SECURITY.md` stub; mailto written `[security@frojd.se](security@frojd.se)` (no `mailto:`) → broken link | `SECURITY.md:5` | ☐ |
| 8 | "Update Example" `Company-Project` vs `Company-project` casing drift | root `README.md:64-68` | ☐ |

## 4. Skip (project-specific / no signal)
functions.php diffs (project-specific module wiring), per-project application.php
domain integrations, Makefile CI links, headless Next.js machinery, and
WooCommerce/payments/CRM integrations. (Items with no adoption across the
reference set — e.g. Tailwind/TS, root-level lint configs, CONTRIBUTING — are
omitted; there's nothing to backport.)

## 5. CI/deploy direction — DECIDED
The CI/deploy area **targets the hardened CI reference model** (CI-SEC above):
a single reusable `deploy.yml` + a `ci.yml` quality-gate dispatcher, with Trivy,
zizmor, SHA-pinning, health-check, CycloneDX SBOM, explicit secrets, sed-rendered
auth, and `workflow_dispatch` rollback. Adapted to the boilerplate: collapse the
reference model's multi-package lint/build matrices to the single `main` theme,
`web/`→`src/` paths, drop vendor-specific license-key secrets, parameterize
hosts/URLs via cookiecutter vars. **Open sub-decision:** runner default —
self-hosted (most secure, needs runner infra) vs ubuntu + `SSH_PRIVATE_KEY`
(works out of the box). See `ci-deploy.md`. The security gates apply either way.
Recommend ubuntu default, document self-hosted.

---

## Suggested implementation grouping (for whatever you select)
Nothing has been changed in the template. Once you mark Decisions above, these
group into reviewable PRs:
1. **Bugfixes** (§0) — C4, C3, I2b, I3, I4, T4. Small, uncontroversial.
2. **PHP 8.3 bump** (I1 + I2 + C3 + I5) — one coordinated PR across composer/docker/deploy.
3. **CI restructure** (CI-SEC + C2 + C5 + C7) — the hardened single-file ci.yml + deploy.yml + notify-slack.yml + supporting `trivy.yaml`/`zizmor.yml`/`auth.example.json`. See `ci-deploy.md` for the full job graph.
4. **Theme build** (T1 + T2 + T3 + T5) — Vite 8 + static-copy + fonts + editor.scss + lint.
5. **Stale docs** (§3) — can ship anytime.
6. **Discuss items** (§2) — after you mark decisions.
