# CI & deploy — findings

**DECISION (Sara, 2026-06-26):** target the **hardened CI reference model** — its
stronger security posture — and keep a **single deploy workflow** (no prod/stage
file split). This supersedes the earlier "split deploy.yml into two files"
recommendation (C1 below is withdrawn). Note: the reference model already uses a
single `deploy.yml`; prod and stage are two *jobs* in one reusable workflow, so
"follow the reference model" and "don't split into two files" are the same target.

Template today: single `deploy.yml` (branch-switching, push-to-main for prod, no
security gates, no Slack, tag-pinned actions) + `update-dependencies.yml`,
ansistrano `deploy/`.

---

## Target architecture (hardened CI reference model, adapted to the boilerplate)

Two workflow files + one reusable notifier:

### `ci.yml` — quality gates + dispatcher
- `on: push (branches '**', tags 'v*') + pull_request`; `permissions: contents: read`; `concurrency` group per ref with `cancel-in-progress: true`.
- **lint** job — runs `npm run lint` in the theme. (Boilerplate has ONE theme `main`, so this is a single job; collapse the reference model's multi-package lint matrix to the single `main` theme.)
- **security** job — **Trivy** fs scan (`aquasecurity/trivy-action`, SHA-pinned), `scanners: vuln,misconfig,secret`, `severity: CRITICAL,HIGH`, `exit-code: 1`, reads `trivy.yaml`.
- **security-actions** job — **zizmor** GitHub Actions audit (`uvx zizmor` via `astral-sh/setup-uv`, SHA-pinned), reads `zizmor.yml`.
- **deploy** job — `needs: [lint, security, security-actions]`, `if:` develop OR tag `v*`; calls `./.github/workflows/deploy.yml` with **explicit secrets** (`ACF_PRO_KEY` only — NOT `secrets: inherit`, which zizmor flags). Drop any vendor-specific license-key secrets the boilerplate doesn't need.
- **notify** job — `needs: [lint, security, security-actions, deploy]`, fires `if: always() && push on tracked ref && contains(needs.*.result, 'failure')`; calls `notify-slack.yml`.

### `deploy.yml` — single reusable build+deploy workflow (NOT split)
- `on: workflow_call (secrets: ACF_PRO_KEY) + workflow_dispatch` with `environment` choice (staging/production) + optional `ref` input → **manual rollback** without going through CI.
- `permissions: contents: read`.
- **composer** job (ubuntu) — checkout `ref || github.ref` with `persist-credentials: false`; cache `vendor` keyed on `composer.lock`; render `auth.example.json → auth.json` via **sed** (keeps the ACF key out of `/proc/<pid>/cmdline` — better than `composer config`); `composer install --no-dev --optimize-autoloader`; upload `vendor` + `src` as the `main` artifact (boilerplate paths: `src/`, not `web/`).
- **frontend** job (ubuntu) — checkout, node 24 w/ npm cache, `npm run build:production` in `src/app/themes/main/frontend`, **verify build output** (assert `dist/manifest.json` exists + entry present), upload `dist` artifact. (Single theme → no matrix.)
- **deploy-staging** job — `needs: [composer, frontend]`, `if:` develop OR dispatch=staging; download artifacts, `ansible-galaxy install -r deploy/requirements.yml`, `ansible-playbook -i stages/stage.yml deploy.yml`. `environment: staging` + `concurrency` group (cancel-in-progress).
- **deploy-production** job — same shape, `if:` tag `v*` OR dispatch=production; `stages/prod.yml`; `environment: production` + `concurrency` group with `cancel-in-progress: false`.
- **health-check** job — `needs: [deploy-staging, deploy-production]`, curls the just-deployed public URL(s) with retry (30×10s); fails the run if unreachable. Parameterize URLs via cookiecutter vars (`https://{{cookiecutter.domain_prod}}` / `https://{{cookiecutter.domain_stage}}`).
- **security-sbom** job — `v*`/dispatch=production only; CycloneDX SBOM via trivy-action, uploaded 365-day retention (EU Cyber Resilience Act, deadline 2027-12-11). Non-blocking.
- **notify** job — manual-dispatch failures only (push runs are covered by ci.yml's notify).

### `notify-slack.yml` — reusable failure notifier
`workflow_call` with `workflow_name`/`jobs`/`commit_*` inputs + `SLACK_BOT_TOKEN` secret; `jq`-parses a jobs JSON array into emoji (incl. `cancelled`), posts Slack Block Kit (repo/branch-tag/commit link/author/per-job results + run button) via `slackapi/slack-github-action@v2.0.0`. Channel from the channel ID var (`vars.SLACK_CHANNEL`). This notifier is near-universal across reference projects — the byte-identical version is the one to adopt.

### Runner note (DISCUSS)
The reference model uses **self-hosted [stage]/[prod] runners** (local SSH, no SSH key in GitHub). Most other boilerplate-lineage projects deploy from **ubuntu-latest via ansistrano over SSH using an `SSH_PRIVATE_KEY` secret**. Decide which the boilerplate should default to:
- self-hosted — most secure (no SSH key in CI) but requires runner infra per project;
- ubuntu + `SSH_PRIVATE_KEY` (current boilerplate + most projects) — works out of the box.
Recommend: **default to ubuntu + SSH_PRIVATE_KEY** (no infra assumption) but document the self-hosted option. The security gates (Trivy/zizmor/SBOM/health-check/SHA-pin) are runner-independent and apply either way.

---

## New supporting files to add (from the reference model)
- `trivy.yaml` — Trivy config (severity/ignore rules).
- `zizmor.yml` — zizmor config (audit rules/exclusions).
- `auth.example.json` with `%%ACF_PRO_KEY%%` placeholder (template already ships `auth.example.json` — adapt to the sed-substitution placeholder form).
- `deploy/requirements.yml` — already present in template; confirm ansistrano galaxy ref.

---

## Findings / decisions

### C1 — ~~Split deploy.yml into deploy-prod + deploy-stage~~ — WITHDRAWN
Superseded by the single-file reference model above. Prod/stage are jobs in one `deploy.yml`, gated by `if:` on ref. **Do not create two deploy files.**

### CI-SEC — Adopt the reference model's security gates  ★ the new core of this area
- **What:** Trivy fs scan + zizmor Actions audit as required gates before deploy; CycloneDX SBOM on prod; SHA-pinned actions throughout; explicit secrets (no `inherit`); sed-rendered `auth.json`; health-check job; `workflow_dispatch` manual rollback; concurrency groups.
- **Where:** in one reference project (the chosen model) — explicitly chosen as target despite low fleet prevalence; it's the newest/most-secure lineage.
- **Effort:** **high** — most involved area. Reparam: collapse the reference model's multi-package lint/frontend matrices to the single `main` theme; `web/`→`src/` paths; drop vendor-specific license-key secrets; parameterize stage/prod hosts + health-check URLs via cookiecutter vars (`{{cookiecutter.domain_prod}}`, `{{cookiecutter.domain_stage}}`); pin action SHAs at build time.
- **Recommendation:** **backport (adapted).** This is the agreed direction.

### C2 — Reusable notify-slack.yml + notify jobs  ★ keep
- Near-universal across reference projects and byte-identical; the version that handles `cancelled` is the one to use. Low effort. **Backport.** Document `SLACK_BOT_TOKEN` secret + `SLACK_CHANNEL` var.

### C3 — Fix php8.1-fpm → php8.3-fpm in after-symlink.yml (bug)
- `deploy/tasks/after-symlink.yml:3` restarts the wrong FPM socket. A majority of reference projects use 8.3. **Backport** (pairs with infra I1). *(Confirmed template bug — see common-patterns §0.)*

### C4 — Delete bogus "Create .htaccess" provision task (bug)
- `deploy/provision.yml:48-56` writes empty `shared/src/.env`. None of the reference projects have it — template-only. **Backport (delete).** *(Confirmed template bug.)*

### C5 — Node 22 → 24 in workflows
- Node 24 is near-universal across reference projects. The new `ci.yml`/`deploy.yml` use 24 natively. **Backport.**

### C6 — SHA-pin + bump GitHub Actions
- **Absorbed into CI-SEC** — the reference model workflows are already fully SHA-pinned (checkout v6, setup-node v6.4.0, cache v5, upload/download-artifact v7/v8, trivy v0.36.0, slack-action v2.0.0). Pin all actions to commit SHAs with version comments at backport time.

### C7 — Composer cache strategy
- The reference model caches `vendor` keyed on `composer.lock` (restore-keys `composer-<os>-`). Adopt that form. **Backport** (rolls into CI-SEC composer job).

### C-sentry — sentry_dsn vars_prompt gap (provision)
- Template `.env.j2` references `{{ sentry_dsn }}` but `provision.yml` never prompts → renders empty. About half of reference projects add the prompt. **Discuss / small backport.**

### Build-output verification
- The reference model's frontend job asserts `dist/manifest.json` + entry present after build. Cheap guardrail against shipping a broken build. **Backport** (in the frontend job).

## Skip / reference-monorepo-specific
- Multi-package lint/frontend matrices, `theme.json` symlink check across child themes, extra feature-branch environments, vendor-specific license-key secrets, multi-domain health-check lists, `web/app` paths — all specific to the reference model's monorepo layout; collapse/drop when adapting.

## Suggested backport order
1. C4 delete bogus .htaccess task + C3 php8.3 restart (bugfixes, trivial).
2. C2 notify-slack.yml (verbatim) + document Slack secret/var.
3. **CI-SEC**: build the adapted `ci.yml` + single `deploy.yml` (Trivy, zizmor, SBOM, health-check, SHA-pin, explicit secrets, sed auth, manual-rollback dispatch, node 24, composer cache, build verify) — the main effort.
4. trivy.yaml + zizmor.yml + auth.example.json placeholder form.
5. Discuss: self-hosted vs ubuntu+SSH_PRIVATE_KEY runner default; sentry_dsn prompt.

Files: replace `.github/workflows/deploy.yml` (single reusable build+deploy);
add `.github/workflows/ci.yml`, `.github/workflows/notify-slack.yml`,
`trivy.yaml`, `zizmor.yml`; update `auth.example.json`; `update-dependencies.yml`
(node 24); `deploy/provision.yml` (delete task; maybe sentry prompt);
`deploy/tasks/after-symlink.yml` (php 8.3).
