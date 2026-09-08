# AGENTS.md

This file provides guidance to Codex (Codex.ai/code) when working with code in this repository.

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
- **DB name:** `wp_ltwoo`, **DB user:** `root`
- **Local URL:** `http://qualitycomponents.local:8890`
- **WP root:** `/Users/fraser/dev-websites/qualitycomp/`

When running WP-CLI or PHP scripts from the CLI, use the MAMP PHP binary and pass `--path=/Users/fraser/dev-websites/qualitycomp` to WP-CLI. WP-CLI phar lives at `wp-content/themes/quality-components/wp-cli.phar`.

Example:
```bash
/Applications/MAMP/bin/php/php8.4.17/bin/php wp-cli.phar --path=/Users/fraser/dev-websites/qualitycomp plugin list
```

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
composer php-lint         # PHP syntax check
composer phpcs            # Check against WordPress Coding Standards
composer phpcs-fix        # Auto-fix coding standards violations
composer phpmd            # PHP Mess Detector
composer phpstan          # Static analysis (level: max), analyzes inc/ only
composer phpstan-baseline # Regenerate PHPStan baseline (suppress new accepted errors)
composer phpmd-baseline   # Regenerate PHPMD baseline
```

## Architecture

### Build Pipeline

**SCSS → CSS:** `src/sass/` → Sass compiler → PostCSS/Autoprefixer → CleanCSS minifier → `css/child-theme.css` + `css/child-theme.min.css`. `src/sass/custom-editor-style.scss` is also compiled in parallel to `css/custom-editor-style.css` (block editor stylesheet).

**JS → JS:** `src/js/` → Rollup bundler → Babel transpiler → Terser minifier → `js/child-theme.js` + `js/child-theme.min.js`

Never edit files in `css/` or `js/` directly — they are compiled output. Always edit source files in `src/`.

### Key Source Files

- `src/sass/child-theme.scss` — Main SCSS entry point; imports Bootstrap 5, Understrap, WooCommerce, Font Awesome, and custom partials
- `src/sass/theme/` — Custom SCSS partials: `_child_theme_variables.scss`, `_child_theme.scss` (main custom rules), `_buttons.scss`, `_footer.scss`, `_single-product.scss`, `_archive-product.scss`, `_shop-filters.scss`, `_cart-filters.scss` (hides block cart/checkout product metadata), `_trust-bar.scss` (trust/benefits bar on home page, uses `.wp-block-group__inner-container:has(.trust-bar__tagline)` and `.trust-bar__item` classes), `_ltwoo.scss` (styling for the L-TWOO B2B landing page template, defines `--ltwoo-*` CSS custom properties)
- `src/js/custom-javascript.js` — Place for custom JS additions
- `src/js/bootstrap.js` — Bootstrap 5 component imports
- `src/build/` — Rollup, PostCSS, Babel, Terser, and BrowserSync configs
- `js/customizer-controls.js` — Hand-written (not compiled); enqueued only in the Customizer via `understrap_child_customize_controls_js()`

### PHP Theme Structure

- `functions.php` — Core theme hooks: dequeues parent styles, enqueues compiled child theme CSS/JS, sets Bootstrap 5 as default, registers block editor button variants, WooCommerce product tab JS, SVG upload support, registers `shop-filters-sidebar` widget area, disables Query Monitor hooks (`QM_DISABLE_HOOKS`), registers the FA Icon ACF block and the L-TWOO landing page ACF fields (see below)
- `woocommerce/` — WooCommerce template overrides: `archive-product.php`, `content-single-product.php`, `single-product.php`, plus subdirectories `cart/`, `checkout/`, `global/`, `loop/`, `my-account/`, `myaccount/`, `single-product/` (note: both `my-account/` and `myaccount/` exist side by side)
- `global-templates/` — Navbar (`navbar-collapse-bootstrap5.php`) and shop filter sidebar (`shop-filter-sidebar.php`)
- `loop-templates/` — Content loop templates (`content-blank.php`, `content-page.php`)
- `page-templates/blank.php` — Blank page template (no header/footer)
- `page-templates/page-ltwoo.php` — B2B landing page template for L-TWOO components (product range/dealer network overview), styled via `_ltwoo.scss`
- `inc/editor-color-palette.json` — Block editor color palette (13 Bootstrap colors)

### WordPress/WooCommerce Customisations

- Custom Bootstrap 5 navbar in `global-templates/navbar-collapse-bootstrap5.php`
- Product spec label mapping via `ltwoo_spec_label_from_key()` in `functions.php` — edit the `$map` array there to add/rename spec fields (powered by ACF)
- WooCommerce product tab switching JS (inline, in `functions.php`) — uses `data-tab-target` / `data-tab-panel` attributes and `ltwoo-tab` / `ltwoo-product` CSS classes; also re-initialises the WC product gallery on tab switch
- `quality_single_product_no_sidebar()` in `functions.php` filters `theme_mod_understrap_sidebar_position` to force no sidebar on single product pages
- `quality_display_loop_sku()` in `functions.php` hooks `woocommerce_after_shop_loop_item_title` (priority 6) to display `SKU: XXXXX` between product title and price on shop/archive loop cards
- Block editor button style variants registered via `register_block_style()`
- Font Awesome Pro loaded via kit script (crossorigin); kit delivery is set to **Web Fonts + CSS** (not SVG + JS) — required so icons render inside the block editor's iframed preview canvas, which mirrors enqueued stylesheets but not scripts. `fa_custom_setup_kit()` enqueues both the kit's `.js` and matching `.css` build
- **FA Icon block** (`acf/fa-icon`) — custom ACF block for inserting Font Awesome icons from the block editor, registered via `ltwoo_register_fa_icon_block()` / rendered via `ltwoo_render_fa_icon_block()` in `functions.php`. Uses ACF's `ServerSideRender` preview (unlike the core Custom HTML block, whose iframed preview can't show icon markup). Fields: **Icon Classes** (e.g. `fa-duotone fa-regular fa-store fa-2x`) and optional **Custom Style** for inline CSS (e.g. duotone `--fa-primary-color`/`--fa-secondary-color`)
- **L-TWOO landing page ACF fields** — `ltwoo_register_landing_page_fields()` in `functions.php` registers a local ACF field group (hero eyebrow/heading/text/CTAs/background image, a `cards` repeater, a CTA section, a contact panel) scoped via `location` rules to `page-templates/page-ltwoo.php`. Fields are defined in PHP (not the ACF admin UI), so add/rename fields there rather than in wp-admin.
- **Category archive layout** — `qc_category_description_under_image()` hooks `woocommerce_archive_description` (priority 20) to render the term description below the category banner; a `woocommerce_before_subcategory_title` callback prints subcategory descriptions; a `wp_head` callback hides `.woocommerce-loop-category__title` via inline CSS. `qc_insert_products_separator_before_first_product()` hooks `woocommerce_shop_loop` to insert a "Products" `<li>` separator between subcategory tiles and products when the loop display mode is `both`; it also resets the WC loop counter and, when the subcategory count is even, inserts a hidden spacer `<li>` so `:nth-child` column pairing in WooCommerce's mobile CSS stays correct (see inline comment for the parity logic)
- **SEO fallback** — `qc_rank_math_empty_content_description()` filters `rank_math/frontend/description`. Rank Math falls back to a raw, unsanitized `get_the_excerpt()` when its own description is empty, and Understrap's `wp_trim_excerpt` filter appends a raw "Read More..." link to every excerpt — so on content-less templated pages (like `page-ltwoo.php`, which has no real `post_content`) that raw HTML was leaking into link previews. This filter supplies a non-empty description first (ACF `hero_text` on the L-TWOO page, otherwise the site tagline) to prevent Rank Math from ever reaching that fallback.
- **Shipping rates** — `qc_hide_shipping_when_free_available()` filters `woocommerce_package_rates`: when free shipping qualifies, it hides every other method; otherwise it reorders rates so flat-rate methods (e.g. Postage) come before others (e.g. Local Pickup).
- **Cart icon in nav** — `quality_add_cart_to_menu()` filters `wp_nav_menu_items` to append a cart icon with a count badge (`.cart-count`) to the `primary` menu location. The count stays live via `quality_cart_count_fragment()` (hooked to `woocommerce_add_to_cart_fragments`, targets `.cart-count`) and `quality_ensure_cart_fragments()` force-enqueues `wc-cart-fragments`. `quality_exclude_cart_js_from_litespeed()` exempts `wc-cart-fragments`/`wc-add-to-cart`/`woocommerce` from LiteSpeed Cache's JS defer/delay optimization, since deferring those scripts breaks AJAX add-to-cart.

### WooCommerce Layout Architecture

The default Understrap WooCommerce wrappers are removed and replaced by `quality_woocommerce_wrapper_start/end()` in `functions.php`. The behaviour forks:

- **Shop / archive pages** (`is_shop()`, `is_product_category()`, etc.) — renders the `shop-filters-sidebar` widget area (registered in `functions.php`) via `global-templates/shop-filter-sidebar.php` as a sidebar column, then a `.col-md` content area
- **All other WC pages** (single product, cart, checkout, account) — falls back to the standard Understrap left/right sidebar check (`left-sidebar-check` / `right-sidebar-check` template parts, defined in the parent theme)

`woocommerce/archive-product.php` also prepends a category banner (background image, title, description, subcategory tiles) when viewing a product category.

### Key Plugin Dependencies

- **B2BKing** — B2B wholesale functionality (wholesale pricing, customer groups). Located in `wp-content/plugins/b2bking/`.
- **ACF (Advanced Custom Fields)** — used for product spec fields; `ltwoo_spec_label_from_key()` maps ACF field keys to display labels.

### CSS Class Conventions

Custom components use the `ltwoo-` prefix (e.g. `ltwoo-product`, `ltwoo-tab`).

## Coding Standards

PHP code must comply with **WordPress Coding Standards** (`phpcs.xml.dist`). The text domains in use are `understrap` and `woocommerce`. PHPStan runs at `max` level; its scope is configured in `phpstan.neon.dist` (currently `inc/` — which is empty; add PHP files there when PHPStan analysis is needed, or expand paths in `phpstan.neon.dist`). PHPStan uses `src/phpstan/autoload.php` as its bootstrap. PHPMD excludes WooCommerce templates, all `*-templates/` directories (global, loop, page), and `src/`. Both PHPStan and PHPMD have baseline files (`phpstan-baseline.neon`, `phpmd.baseline.xml`) for suppressing pre-existing issues.

There are no automated tests. Quality assurance is entirely through the static analysis and linting tools above.
