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


/* 1. Kill the Heartbeat (The #1 cause of local hangs) */
add_action( 'init', function() {
    wp_deregister_script('heartbeat');
}, 1 );
