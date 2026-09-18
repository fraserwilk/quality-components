# Quality Components Child Theme

WordPress child theme built on the [Understrap](https://github.com/understrap/understrap) framework (Bootstrap 5 + Underscores), with WooCommerce support. Developed by TruWeb.

- Parent theme: Understrap
- CSS framework: Bootstrap 5.2.2
- PHP: >=5.6, Node: >=14

## Build Commands

```bash
npm run css     # Compile, post-process, and minify SCSS
npm run js      # Bundle, transpile, and minify JS
npm run dist    # Build both CSS and JS
npm run watch   # Watch src/ and auto-rebuild on changes
```

Never edit files in `css/` or `js/` directly — they're compiled output. Edit source files in `src/`.

```bash
composer phpcs     # WordPress Coding Standards
composer phpstan   # Static analysis (analyzes inc/ only)
composer phpmd     # PHP Mess Detector
```

## Custom Features

### FA Icon block

An ACF block (`acf/fa-icon`) for inserting Font Awesome icons from the block editor, registered in `functions.php` (`ltwoo_register_fa_icon_block()` / `ltwoo_render_fa_icon_block()`).

It exists because the core Custom HTML block renders its editor preview inside a fully isolated iframe with no theme scripts or styles loaded, so icon markup pasted there never showed up while editing. The FA Icon block instead uses ACF's `ServerSideRender`-based preview, which injects real rendered markup into the main editor canvas.

Getting an icon to actually render in that canvas also required switching the site's Font Awesome kit (fontawesome.com kit settings) from **SVG + JavaScript** to **Web Fonts + CSS** delivery — the editor canvas mirrors enqueued stylesheets but not scripts, so JS-driven inline-SVG icons can never paint there, while CSS/webfont-based icons can. `fa_custom_setup_kit()` enqueues both the kit's `.js` and matching `.css` build accordingly.

**Fields:**
- **Icon Classes** — e.g. `fa-duotone fa-regular fa-store fa-2x`
- **Custom Style** (optional) — inline CSS, e.g. `--fa-primary-color: rgb(245, 131, 0); --fa-secondary-color: rgb(241, 241, 241);` for duotone coloring

**Tradeoff:** icons now render via CSS webfont glyphs (duotone uses stacked pseudo-elements) instead of inline SVG, sitewide — visually close to the old SVG rendering but not pixel-identical.
