# Plugins & WP setup — findings

Comparison of `composer.json` across the surveyed projects vs the boilerplate
template. `T` = currently in the template. Prevalence is given as a band
(near-universal / most / a majority / about half / a few / one project /
none) rather than per-project detail. Note: one surveyed project is a divergent
lineage with a different plugin/config stack — its plugins are NOT treated as
backport signals.

## PHP version
- Template: **8.1.\***
- Nearly all surveyed projects: **8.3** (most as `8.3.*`, the divergent one as `^8.3`).
- A single project still on 8.1.\*.
→ **Backport: bump template to PHP 8.3** (strongest single signal; touches composer.json, Docker, deploy — see infra & ci-deploy findings).

## Plugin matrix (anonymized — prevalence bands, no per-project columns)

| package | prevalence | in template? | note |
|---|---|---|---|
| composer/installers | near-universal | T | core |
| stayallive/wp-sentry | near-universal | T | |
| vlucas/phpdotenv | near-universal | T | |
| wpengine/advanced-custom-fields-pro | near-universal | T | ACF Pro |
| johnpbloch/wordpress | near-universal | T | (divergent project uses roots/wordpress) |
| sentry/sdk | near-universal | T | |
| duracelltomi-google-tag-manager | near-universal | T | GTM |
| redirection | near-universal | T | |
| wordpress-seo (Yoast) | near-universal | T | |
| nginx-cache | most | T | |
| disable-embeds | a majority | T | mu-plugin |
| elasticpress | a majority | T | |
| **wp-nested-pages** | **a majority** | — | **backport candidate** |
| **wp-smartcrop** | **a majority** | — | **backport candidate** |
| members | about half | — | role mgmt; discuss |
| woocommerce | about half | — | project-specific; skip |
| better-wp-security | a few | — | discuss |
| setasign/fpdf + fpdi | a few | — | PDF gen, project-specific |
| (client-specific custom packages) | one project each | — | skip — client-namespaced vendor packages |

## Findings

### P1 — Bump PHP 8.1 → 8.3
- **What:** template pins `"php": "8.1.*"`; align to `8.3`.
- **Where:** near-universal across surveyed projects (a single one still on 8.1).
- **Why:** 8.1 is near EOL; new projects standardize on 8.3.
- **Effort:** medium — coordinated change across composer.json + Docker images + deploy php-fpm restart + update-dependencies workflow. See infra/ci findings.
- **Recommendation:** **backport.**

### P2 — Add wp-nested-pages
- **What:** `wpackagist-plugin/wp-nested-pages` — page-tree admin UI.
- **Where:** a majority of surveyed projects.
- **Why:** adopted across much of the fleet for managing large page hierarchies; sensible default.
- **Effort:** low — add one require line.
- **Recommendation:** **discuss** (default-on vs opt-in). Lean backport.

### P3 — Add wp-smartcrop
- **What:** `wpackagist-plugin/wp-smartcrop` — focal-point image cropping.
- **Where:** a majority of surveyed projects.
- **Why:** common editorial need; consistent image cropping.
- **Effort:** low.
- **Recommendation:** **discuss.** Lean backport.

### P4 — Security hardening plugin (no clear winner)
- **What:** `better-wp-security` (iThemes/Solid Security) in a few projects; `members` (role mgmt) in about half.
- **Where:** scattered, no majority.
- **Recommendation:** **discuss** — decide on a standard hardening approach rather than copy ad hoc.

## Open items (need deeper read — next pass)
- **mu-plugins comparison**: some projects ship custom mu-plugins (config, security, login tweaks) beyond the vendor ones. Worth diffing custom (non-vendor) mu-plugins for common helpers worth backporting — e.g. a custom config/security mu-plugin seen in some projects. Template mu-plugins = disable-embeds + acf-pro only.
- **require-dev** divergence (debug-bar, query-monitor placement) — verify vs template dev block.
- **johnpbloch/wordpress version pins** — template uses `*`; check if projects pin majors.
- **ACF source**: all via `wpengine/advanced-custom-fields-pro` + `connect.advancedcustomfields.com` — consistent with template. ✓
