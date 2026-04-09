# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

WordPress child theme built on the **Understrap** framework (Bootstrap 5 + Underscores base), with WooCommerce support. The theme is named "Quality Components Child" and is developed by TruWeb.

- Parent theme: Understrap
- CSS framework: Bootstrap 5.2.2
- PHP: >=5.6, Node: >=14

## Local Environment

- **Stack:** MAMP PRO
- **PHP binary:** `/Applications/MAMP/bin/php/php8.4.17/bin/php`
- **MySQL socket:** `/Applications/MAMP/tmp/mysql/mysql.sock`
- **MySQL binary:** `/Applications/MAMP/Library/bin/mysql80/bin/mysql`
- **DB name:** `wp_ltwoo`, **DB user:** `wp_WordPress_user`
- **Local URL:** `http://qualitycomponents.local:8890`
- **WP root:** `/Users/fraser/dev-websites/ltwoo/`

When running WP-CLI or PHP scripts from the CLI, use the MAMP PHP binary and pass `--path=/Users/fraser/dev-websites/ltwoo` to WP-CLI. WP-CLI phar lives at `wp-content/themes/quality-components/wp-cli.phar`.

## Build Commands

### Frontend (Node/npm)

```bash
npm run dist         # Build production CSS and JS (both)
npm run css          # Compile, post-process, and minify SCSS only
npm run js           # Bundle, transpile (Babel), and minify JS only
npm run watch        # Watch src/ and auto-rebuild on changes
npm run watch-bs     # Watch + BrowserSync live reload server
```

### PHP Quality Assurance (Composer)

```bash
composer php-lint    # PHP syntax check
composer phpcs       # Check against WordPress Coding Standards
composer phpcs-fix   # Auto-fix coding standards violations
composer phpmd       # PHP Mess Detector
composer phpstan     # Static analysis (level: max), analyzes inc/ only
```

## Architecture

### Build Pipeline

**SCSS → CSS:** `src/sass/` → Sass compiler → PostCSS/Autoprefixer → CleanCSS minifier → `css/child-theme.css` + `css/child-theme.min.css`

**JS → JS:** `src/js/` → Rollup bundler → Babel transpiler → Terser minifier → `js/child-theme.js` + `js/child-theme.min.js`

Never edit files in `css/` or `js/` directly — they are compiled output. Always edit source files in `src/`.

### Key Source Files

- `src/sass/child-theme.scss` — Main SCSS entry point; imports Bootstrap 5, Understrap, WooCommerce, Font Awesome, and custom partials
- `src/sass/theme/` — Custom SCSS partials: `_child_theme_variables.scss`, `_child_theme.scss` (main custom rules), `_buttons.scss`, `_footer.scss`, `_single-product.scss`, `_archive-product.scss`, `_shop-filters.scss`
- `src/js/custom-javascript.js` — Place for custom JS additions
- `src/js/bootstrap.js` — Bootstrap 5 component imports
- `src/build/` — Rollup, PostCSS, Babel, Terser, and BrowserSync configs

### PHP Theme Structure

- `functions.php` — Core theme hooks: dequeues parent styles, enqueues compiled child theme CSS/JS, sets Bootstrap 5 as default, registers block editor button variants, WooCommerce product tab JS, SVG upload support
- `woocommerce/` — WooCommerce template overrides (16+ templates covering cart, checkout, single product, account, loops)
- `global-templates/` — Navbar and structural template parts
- `loop-templates/` — Content loop templates
- `inc/editor-color-palette.json` — Block editor color palette (13 Bootstrap colors)

### WordPress/WooCommerce Customisations

- Custom Bootstrap 5 navbar in `global-templates/navbar-collapse-bootstrap5.php`
- Product spec label mapping via `ltwoo_spec_label_from_key()` in `functions.php` — edit the `$map` array there to add/rename spec fields (powered by ACF)
- WooCommerce product tab switching JS (inline, in `functions.php`) — uses `data-tab-target` / `data-tab-panel` attributes and `ltwoo-tab` / `ltwoo-product` CSS classes; also re-initialises the WC product gallery on tab switch
- Block editor button style variants registered via `register_block_style()`
- Font Awesome Pro loaded via kit script (crossorigin)

### WooCommerce Layout Architecture

The default Understrap WooCommerce wrappers are removed and replaced by `quality_woocommerce_wrapper_start/end()` in `functions.php`. The behaviour forks:

- **Shop / archive pages** (`is_shop()`, `is_product_category()`, etc.) — renders the `shop-filters-sidebar` widget area via `global-templates/shop-filter-sidebar.php` as a sidebar column, then a `.col-md` content area
- **All other WC pages** (single product, cart, checkout, account) — falls back to the standard Understrap left/right sidebar check

`woocommerce/archive-product.php` also prepends a category banner (background image, title, description, subcategory tiles) when viewing a product category.

### Key Plugin Dependencies

- **B2BKing** — B2B wholesale functionality (wholesale pricing, customer groups). Located in `wp-content/plugins/b2bking/`.
- **ACF (Advanced Custom Fields)** — used for product spec fields; `ltwoo_spec_label_from_key()` maps ACF field keys to display labels.

### CSS Class Conventions

Custom components use the `ltwoo-` prefix (e.g. `ltwoo-product`, `ltwoo-tab`).

## Coding Standards

PHP code must comply with **WordPress Coding Standards** (`phpcs.xml.dist`). The text domains in use are `understrap` and `woocommerce`. PHPStan runs at `max` level and only covers the `inc/` directory. PHPMD excludes WooCommerce template overrides.
