# Theme & build — findings

Template baseline: Vite ^5 in `frontend/`, Sage 9 alpha base, ITCSS SCSS
structure, `gutenberg.php`/`gutenberg.js` (soft-hyphen format, Ingress block,
allowed-block whitelist), `acf-json` local JSON.

Among reference projects, two frontend lineages exist: most use a **Vite
frontend** comparable to the template's; a couple are **headless Next.js +
Storybook** themes with no comparable Vite/SCSS frontend and are excluded from the
build comparison. Build findings below describe the Vite-frontend projects, where
they are **near-universal**.

## Build-tooling summary (Vite-frontend projects vs. template)

- **Vite:** template `^5`; reference projects run `^7`–`^8` (most converged on `^8`).
- **node engine:** template `≥22`; reference projects `≥24` (most on 24).
- **@vitejs/plugin-legacy:** template `^5`; reference projects `^7`/`^8`.
- **vite-plugin-static-copy:** absent in template; **near-universal** in reference projects.
- **Font-name preservation (`assetFileNames`):** absent in template; **near-universal**.
- **`editor.scss` build input:** absent in template; **near-universal**.
- **PostCSS/autoprefixer/stylelint-scss:** absent in template; **one project** only.
- **theme.json:** absent in template; **one project** ships one (v2).

**Key signal:** the Vite-frontend reference projects independently upgraded Vite
past the template's ^5 (→ ^7/^8), moved node to 24, added `vite-plugin-static-copy`,
an `editor.scss` build input, and font-name preservation — a consistent migration
pattern. `functions.php` is essentially identical to the template across projects
(only project-specific `$sage_includes` wiring differs) — **no reusable
functions.php pattern to backport.**

All theme paths below are under `{{cookiecutter.project_name}}/src/app/themes/main/`.

## Findings

### T1 — Upgrade Vite/plugin-legacy/sass/node to current majors  ★ top priority
- **What:** `vite` ^5→**^8**, `@vitejs/plugin-legacy` ^5→^8, `sass`/`terser`/`core-js`/`jquery` to current, node engine ≥22→**≥24**.
- **Where:** near-universal among applicable projects (most on `^8` + node ≥24). Files: `frontend/package.json`, `.nvmrc`.
- **Why:** template is two Vite majors behind; security/perf; node 24 is the new standard. The Vite 5→8 breaking config changes are reflected in the target config snippets in T2/T3.
- **Effort:** low–med. No reparam (versions universal). Update `.nvmrc` + CI node.
- **Recommendation:** **backport** — target Vite ^8 + node ≥24.

### T2 — Font-name preservation in `assetFileNames`
- **What:** in `frontend/vite.config.js`, set `build.rollupOptions.output.assetFileNames` to route font extensions to an unhashed path, e.g.:
  ```js
  assetFileNames: (assetInfo) => {
    if (/\.(woff2?|ttf|otf|eot)$/.test(assetInfo.name)) {
      return 'assets/fonts/[name][extname]'; // unhashed, so they can be preloaded
    }
    return 'assets/[name]-[hash][extname]';
  }
  ```
- **Where:** near-universal among applicable projects (identical logic, comment "so they can be preloaded").
- **Why:** stable font URLs enable `<link rel=preload>` (hashed names break preload links).
- **Effort:** low, drop-in, no reparam.
- **Recommendation:** **backport.**

### T3 — Add `vite-plugin-static-copy` for static assets
- **What:** add the `vite-plugin-static-copy` dev dep and a `viteStaticCopy` plugin block copying static assets into the dist output, e.g.:
  ```js
  import { viteStaticCopy } from 'vite-plugin-static-copy';
  // plugins: [
  viteStaticCopy({ targets: [{ src: 'assets/*', dest: 'assets' }] })
  // ]
  ```
- **Where:** near-universal among applicable projects. Files: `frontend/vite.config.js`, `frontend/package.json`.
- **Why:** replaces the brittle dev-only symlink workaround documented at the top of the template's own `vite.config.js`. Static assets work in build without manual symlinks.
- **Effort:** low. Backport a generic `assets/*` target; per-project subdir targets are project-specific.
- **Recommendation:** **backport** (generic target).

### T4 — Add `editor.scss` build input (fixes existing bug)
- **What:** add `./styles/editor.scss` to `build.rollupOptions.input` in `frontend/vite.config.js`.
- **Where:** near-universal among applicable projects. **Template bug:** `gutenberg.php:111` enqueues `styles/editor.css` via `asset_path()` but the Vite config never builds `editor.scss` → dangling enqueue. Also needed for soft-hyphen marker styling referenced in `gutenberg.js`.
- **Effort:** low. Ensure a `styles/editor.scss` stub exists in the template.
- **Recommendation:** **backport** — pairs with the soft-hyphen feature already in baseline.

### T5 — ESLint flat config (eslint 9) + lint/test npm scripts
- **What:** replace legacy `.eslintrc.js` with eslint 9 flat config (`@eslint/js`, `globals`, `eslint-plugin-import`); add `lint`/`lint:scripts`/`test` scripts to `frontend/package.json`.
- **Where:** most applicable projects ship eslint ^9 + flat config + lint scripts. Template has NO lint script.
- **Why:** eslint 8 legacy config is EOL; the newest projects converged on flat config.
- **Effort:** med — the exact `eslint.config.js` was not captured, so the rule mapping (single quotes, comma-dangle, no-console off) must be reconstructed from the template's existing `.eslintrc.js`.
- **Recommendation:** **discuss / backport** — add the npm scripts regardless (low); confirm rule mapping before the flat-config rewrite.

### T6 — theme.json (block settings declaratively)
- **Where:** one project. Duplicates logic the template already does imperatively in `gutenberg.php` (disable custom colors/gradients/font-sizes). Palette is project-specific.
- **Recommendation:** **discuss** — low adoption, overlaps existing code. Skip unless the team wants to standardize on theme.json going forward.

### T7 — PostCSS/autoprefixer/stylelint-scss pipeline
- **Where:** one project. Requires stylelint 16 migration (template's `.stylelintrc.js` uses removed rules); source config not captured.
- **Recommendation:** **skip / discuss** — lower priority than Vite items.

### T8 — Gutenberg editor enhancements
- The template's `gutenberg.php`/`gutenberg.js` (soft-hyphen, Ingress block, allowed-block whitelist) is the **most advanced** version — the template is the *upstream* of the soft-hyphen feature, not behind. **Skip** (template already ahead).

## Recommended backport set (priority)
1. T1 Vite ^8 + node ≥24 + dep bumps (near-universal, low–med)
2. T2 Font-name preservation (near-universal, low)
3. T3 vite-plugin-static-copy (near-universal, low)
4. T4 editor.scss build input (near-universal, low; fixes dangling enqueue)
5. T5 ESLint flat config + lint/test scripts (most, med)

Discuss: T6 theme.json, T7 stylelint-16. Skip: T8 (template already ahead), functions.php.
