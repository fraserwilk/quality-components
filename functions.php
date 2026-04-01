<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";
	
	$css_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_styles );

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $css_version );
	wp_enqueue_script( 'jquery' );
	
	$js_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_scripts );
	
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $js_version, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );

// Allow SVG
function allow_svg_uploads($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_uploads');

// Show button variants in the block “Styles” panel
add_action( 'init', function () {

    if ( ! function_exists( 'register_block_style' ) ) return;

    register_block_style( 'core/button', [ 'name' => 'outline',        'label' => __( 'Outline',        'understrap-child' ) ] );
    register_block_style( 'core/button', [ 'name' => 'outline-dark',   'label' => __( 'Outline Dark',   'understrap-child' ) ] );
    register_block_style( 'core/button', [ 'name' => 'outline-primary','label' => __( 'Outline Primary','understrap-child' ) ] );
    register_block_style( 'core/button', [ 'name' => 'outline-accent', 'label' => __( 'Outline Accent', 'understrap-child' ) ] );
    register_block_style( 'core/button', [ 'name' => 'outline-light',  'label' => __( 'Outline Light',  'understrap-child' ) ] );

    register_block_style( 'core/button', [ 'name' => 'dark',           'label' => __( 'Dark',           'understrap-child' ) ] );
    register_block_style( 'core/button', [ 'name' => 'accent',         'label' => __( 'Accent',         'understrap-child' ) ] );
    register_block_style( 'core/button', [ 'name' => 'light',          'label' => __( 'Light',          'understrap-child' ) ] );

    register_block_style( 'core/button', [ 'name' => 'ghost-dark',     'label' => __( 'Ghost Dark',     'understrap-child' ) ] );
    register_block_style( 'core/button', [ 'name' => 'ghost-accent',   'label' => __( 'Ghost Accent',   'understrap-child' ) ] );

    register_block_style( 'core/button', [ 'name' => 'sm',             'label' => __( 'Small',          'understrap-child' ) ] );
    register_block_style( 'core/button', [ 'name' => 'lg',             'label' => __( 'Large',          'understrap-child' ) ] );

} );

// disable hooks for Query Monitor
define( 'QM_DISABLE_HOOKS', true );

// Inline script for product tabs functionality
add_action( 'wp_enqueue_scripts', function () {
    wp_add_inline_script(
        'wc-single-product',
        "
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-tab-target]');
            if (!btn) return;

            var product = btn.closest('.ltwoo-product');
            var target = btn.getAttribute('data-tab-target');

            // Toggle tabs
            product.querySelectorAll('.ltwoo-tab').forEach(function (el) {
                el.classList.toggle('is-active', el === btn);
            });

            // Toggle panels
            product.querySelectorAll('[data-tab-panel]').forEach(function (panel) {
                panel.classList.toggle(
                    'is-active',
                    panel.getAttribute('data-tab-panel') === target
                );
            });

            // Rebuild WooCommerce slider completely (strong fix)
            setTimeout(function () {
                if (jQuery && jQuery.fn.wc_product_gallery) {
                    jQuery('.woocommerce-product-gallery').each(function(){
                        jQuery(this).wc_product_gallery();
                    });
                }
            }, 80);

            // Optional smooth scroll to top of product
            product.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
        "
    );
});

// Helper function to get spec label from key using ACF
// Edit this map when you want new dropdown choices for products.
if ( ! function_exists( 'ltwoo_spec_label_from_key' ) ) {
    function ltwoo_spec_label_from_key( $key ) {
        $map = [
            'code'               => 'Code',
            'groupset'           => 'Groupset',
            'speed'              => 'Speed',
            'min_sprocket'       => 'Minimum sprocket',
            'max_sprocket'       => 'Maximum sprocket',
            'main_structure'     => 'Main structure',
            'barrel_adjuster'    => 'Barrel adjuster',
            'total_capacity'     => 'Total capacity',
            'guide_length'       => 'Length',
            'guide_inner_material' => 'Inner guide plate material',
            'guide_outer_material' => 'Material of outer guide plate',

            // Group names
            'compatible_teeth'   => 'Compatible teeth',
            'guide_plate'        => 'Guide plate',
            'battery'            => 'Battery',
        ];

        return $map[ $key ] ?? '';
    }
}


/**
 * Load Font Awesome Pro via kit
 */
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script( 'font-awesome-pro', 'https://kit.fontawesome.com/2e83392d18.js', [], null, false );
	add_filter( 'script_loader_tag', function( $tag, $handle ) {
		if ( 'font-awesome-pro' === $handle ) {
			return str_replace( '<script ', '<script crossorigin="anonymous" ', $tag );
		}
		return $tag;
	}, 10, 2 );
} );

/* 1. Kill the Heartbeat (The #1 cause of local hangs) */
add_action( 'init', function() {
    wp_deregister_script('heartbeat');
}, 1 );



/**
 * Register a dedicated widget area for shop/archive page filters.
 */
add_action( 'widgets_init', 'quality_register_shop_filter_sidebar' );
function quality_register_shop_filter_sidebar() {
	register_sidebar( [
		'name'          => __( 'Shop Filters', 'understrap' ),
		'id'            => 'shop-filters-sidebar',
		'description'   => __( 'Filter widgets shown on the shop and product archive pages.', 'understrap' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	] );
}

/**
 * Replace Understrap's WooCommerce wrappers so that shop/archive pages get
 * a dedicated filter sidebar instead of the general sidebar-position setting.
 * Single product, cart, checkout, and account pages fall back to the original behaviour.
 */
add_action( 'after_setup_theme', 'quality_override_woo_wrappers', 20 );
function quality_override_woo_wrappers() {
	remove_action( 'woocommerce_before_main_content', 'understrap_woocommerce_wrapper_start', 10 );
	remove_action( 'woocommerce_after_main_content',  'understrap_woocommerce_wrapper_end',   10 );
	add_action( 'woocommerce_before_main_content', 'quality_woocommerce_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content',  'quality_woocommerce_wrapper_end',   10 );
}

function quality_woocommerce_wrapper_start() {
	$container  = get_theme_mod( 'understrap_container_type' ) ?: '';
	$is_archive = is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy();

	echo '<div class="wrapper" id="woocommerce-wrapper">';
	echo '<div class="' . esc_attr( $container ) . '" id="content" tabindex="-1">';
	echo '<div class="row">';

	if ( $is_archive && is_active_sidebar( 'shop-filters-sidebar' ) ) {
		get_template_part( 'global-templates/shop-filter-sidebar' );
		echo '<div class="col-md content-area" id="primary">';
	} else {
		get_template_part( 'global-templates/left-sidebar-check' );
		echo '<main class="site-main" id="main">';
	}
}

function quality_woocommerce_wrapper_end() {
	$is_archive = is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy();

	if ( $is_archive && is_active_sidebar( 'shop-filters-sidebar' ) ) {
		echo '</div><!-- #primary -->';
	} else {
		echo '</main>';
		get_template_part( 'global-templates/right-sidebar-check' );
	}

	echo '</div><!-- .row -->';
	echo '</div><!-- .container -->';
	echo '</div><!-- #woocommerce-wrapper -->';
}
