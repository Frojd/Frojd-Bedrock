# Frojd-Bedrock Backport Audit

Goal: bring the boilerplate back up to date by mining improvements, fixes, and
common patterns from a set of active in-house WordPress projects, then
backporting the good ones into the cookiecutter template
(`{{cookiecutter.project_name}}/`).

This directory documents **what should change in the template and why**, in
enough detail to implement later. It deliberately contains **no references to
the specific source projects** — only the resulting change-spec for the
boilerplate. Prevalence is described in neutral bands (how widely a pattern
appeared across the reference projects), not per-project.

## Method

1. **Audit (done)** — compared each area of the template against a set of
   reference projects. Findings captured per area under `findings/`.
2. **Triage** — review findings, mark each candidate: backport / skip / discuss.
   The checkbox list lives in `findings/common-patterns.md`.
3. **Implement** — apply selected backports to the template, re-parameterizing
   any project-specific values into cookiecutter variables. One PR per area.

## Reference set (anonymized)

The audit compared the template against several active WordPress projects sharing
the boilerplate's lineage (Bedrock + Sage, `src/app` layout, PHP 8.3), plus one
**divergent, more recently modernized** project on a standard Bedrock `web/app`
layout with a hardened CI pipeline. The divergent project is not treated as a
drop-in backport source for most areas, but its **CI/deploy model was explicitly
chosen as the target** (see `findings/ci-deploy.md`).

Boilerplate (target): `{{cookiecutter.project_name}}/` — Bedrock + Sage, currently
PHP **8.1** (target 8.3).

## Audit areas

- `findings/infra-tooling.md` — composer.json, Docker, docker-compose, scripts, config
- `findings/ci-deploy.md` — .github/workflows, deploy/ (Ansible/ansistrano) — **target decided**
- `findings/theme-build.md` — `src/app/themes/main`: build tooling, npm deps, Gutenberg/ACF
- `findings/plugins-wp.md` — composer plugin list, mu-plugins, ACF, WP conventions
- `findings/docs-conventions.md` — README, docs/, git-hooks, coding standards, stale-doc fixes
- `findings/common-patterns.md` — **consolidated triage sheet** (start here): every candidate as a checkbox, grouped bugs / strong / discuss / skip

## Finding format

Each finding:
- **What** — the change/pattern, with exact template file paths + line numbers
- **Where** — neutral prevalence band across the reference set
- **Why it's better than the current boilerplate**
- **Backport effort** — low / medium / high; note re-parameterization needed
- **Recommendation** — backport / skip / discuss

## Prevalence bands (what the counts were replaced with)

- **near-universal** — present in nearly all reference projects
- **most** — a clear majority
- **a majority** — more than half
- **about half**
- **a few** / **one project**
- **none — template-only** — the template has it but no reference project does
  (usually a sign of a template-only bug or dead config)
