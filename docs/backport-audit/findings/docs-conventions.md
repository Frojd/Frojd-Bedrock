# Docs & conventions — findings

Template docs live at repo root (`README.md`, `SECURITY.md`, `docs/`, `git-hooks/`)
AND inside `{{cookiecutter.project_name}}/`.

## Stale docs to fix (high confidence, no source access needed — ready to apply)

| # | Issue | Location | Fix | Priority |
|---|---|---|---|---|
| 1 | "Circle CI" listed as a feature — template ships GitHub Actions, no `.circleci/` | root `README.md:18` | replace with "GitHub Actions" | now |
| 2 | Dead link to `setting-up-deployment-with-circleci.md` (actual file is `...-github-actions.md`); both guide links use `/blob/master/` but default branch is `main` → 404 | root `README.md:82-83` | repoint + `master`→`main` (or relative links) | now |
| 3 | "make sure your CircleCI project as access to server" (wrong tool + typo "as"→"has"); Deployment section never documents the shipped auto-deploy | `{{cookiecutter.project_name}}/README.md:177`, §Deployment | rewrite for GH Actions; condense from `docs/setting-up-deployment-with-github-actions.md` | now |
| 4 | Stale requirements "Ubuntu 20.04+ / PHP 7.4+" | `docs/provisioning-servers-for-hosting.md:9-13` | bump to PHP 8.3 + newer Ubuntu | now |
| 5 | Prompt names `ssh_prod`/`ssh_stage` don't match `cookiecutter.json` (`ssh_host_prod`/`ssh_host_stage`) | root `README.md:45-46` | align names | now |
| 6 | "Sage version 9 alpha" note is years-stale | root `README.md:73` | drop alpha qualifier / "Sage-based theme" | now |
| 7 | `SECURITY.md` stub; mailto written `[security@frojd.se](security@frojd.se)` (no `mailto:`) → broken link | `SECURITY.md:5` | add `mailto:`; expanding content optional | now (link) |
| 8 | "Update Example" name/casing drift `Company-Project` vs `Company-project` | root `README.md:64-68` | align casing | low |

## ⚠ Deploy-trigger discrepancy (flag for CI/deploy owner — see ci-deploy.md)
Template `deploy.yml` triggers **production on push to `main`**, but project
READMEs (and the CLAUDE.md files where present) indicate production deploys on a
**`v*` git tag** (via git-flow release). The template's workflow disagrees with
documented field practice — **decide which is canonical before documenting
deployment.**

## Conventions — prevalence across reference projects

| Convention | prevalence | note |
|---|---|---|
| `git-hooks/` dir | near-universal | template has it (bump-version.sh only) |
| README has GH Actions deploy section | most | template README still mentions CircleCI |
| `CLAUDE.md` | about half (more counting a `.claude/` dir) | **emerging majority** |
| README "Merge conflicts" / git merge driver | a few | seen in the headless projects |
| `.editorconfig` | one project | — |
| `pre-commit` hook | one project | script body not captured |

## Conventions to backport

### D-A — Templated `CLAUDE.md` skeleton  ★ highest-value convention add
- **What:** repo-root `CLAUDE.md` (Overview / Architecture / Dev Commands / Deployment / Gotchas), with cookiecutter vars for domain/ports/post-types.
- **Where:** about half the projects (more counting a `.claude/` dir). Use a non-headless project's `CLAUDE.md` structure as the base since it matches the default template.
- **Effort:** medium — needs a templated skeleton.
- **Recommendation:** **backport.**

### D-B — `.editorconfig` at repo root
- **What:** root `.editorconfig` (utf-8, LF, 4-space, final newline, trim WS, tab for Makefile).
- **Where:** one project — "lead the fleet" add, not codifying a majority. Copy that project's file verbatim.
- **Effort:** trivial.
- **Recommendation:** **backport** (low risk).

### D-C — `pre-commit` git hook
- **Where:** one project; **script body not captured** — must be retrieved from a source project before backporting.
- **Recommendation:** **defer** — do alongside CLAUDE.md work if retrievable.

### D-D — README git merge-driver note
- **Where:** a few (the headless projects). Incomplete without shipping a `.gitattributes` (template has none).
- **Recommendation:** **optional** — only if `.gitattributes` is also added.

## Summary
- **Apply now:** stale-docs 1–7 (factual errors / dead links / version drift).
- **Backport:** templated `CLAUDE.md` (highest value), `.editorconfig` (trivial).
- **Flag:** prod-deploy trigger mismatch (resolve before documenting).
