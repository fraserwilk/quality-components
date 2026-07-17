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
	
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array( 'jquery' ), $js_version, true );
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
 * Font Awesome Kit Setup
 *
 * This will add your Font Awesome Kit to the front-end, the admin back-end,
 * and the login screen area.
 */
if (! function_exists('fa_custom_setup_kit') ) {
  function fa_custom_setup_kit($kit_url = '') {
    // The CSS build of the same kit is what actually renders inside the block
    // editor's iframed canvas (only stylesheets get mirrored in there, not scripts).
    $kit_css_url = preg_replace('/\.js$/', '.css', $kit_url);

    foreach ( [ 'wp_enqueue_scripts', 'admin_enqueue_scripts', 'login_enqueue_scripts', 'enqueue_block_assets' ] as $action ) {
      add_action(
        $action,
        function () use ( $kit_url, $kit_css_url ) {
          wp_enqueue_script( 'font-awesome-kit', $kit_url, [], null );
          wp_enqueue_style( 'font-awesome-kit-css', $kit_css_url, [], null );
        }
      );
    }
  }
}
fa_custom_setup_kit('https://kit.fontawesome.com/f84f17191f.js');

add_filter( 'script_loader_tag', function( $tag, $handle ) {
	if ( 'font-awesome-kit' === $handle ) {
		return str_replace( '<script ', '<script crossorigin="anonymous" ', $tag );
	}
	return $tag;
}, 10, 2 );


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


/**
 * Display SKU on shop/archive product loop cards.
 * Shows "SKU: XXXXX" between the product title and price.
 */
add_action( 'woocommerce_after_shop_loop_item_title', 'quality_display_loop_sku', 6 );
function quality_display_loop_sku() {
	global $product;
	if ( ! $product || ! wc_product_sku_enabled() ) {
		return;
	}
	$sku = $product->get_sku();
	if ( $sku ) {
		echo '<div class="product-loop-sku">SKU: ' . esc_html( $sku ) . '</div>';
	}
}


/**
 * Remove right sidebar on single product pages.
 */
add_filter( 'theme_mod_understrap_sidebar_position', 'quality_single_product_no_sidebar' );
function quality_single_product_no_sidebar( $position ) {
	if ( is_product() ) {
		return 'none';
	}
	return $position;
}

add_action( 'woocommerce_archive_description', 'qc_category_description_under_image', 20 );

function qc_category_description_under_image() {
    if ( is_product_category() ) {
        $term = get_queried_object();

        if ( ! empty( $term->description ) ) {
            echo '<div class="qc-category-description">';
            echo wpautop( wp_kses_post( $term->description ) );
            echo '</div>';
        }
    }
}

add_action( 'woocommerce_before_subcategory_title', function( $category ) {
    if ( ! empty( $category->description ) ) {
        echo '<div class="woocommerce-loop-category__description">' . esc_html( $category->description ) . '</div>';
    }
} );

/**
 * Remove category title on Cat main pages.
 */
add_action( 'wp_head', function() {
    ?>
    <style>
    .woocommerce-loop-category__title { display: none; }
    </style>
    <?php
} );

/**
 * Add a separator between product categories and products
 * on WooCommerce archive/category pages.
 */
add_action( 'woocommerce_shop_loop', 'qc_insert_products_separator_before_first_product', 1 );

function qc_insert_products_separator_before_first_product() {
    static $separator_shown = false;

    if ( $separator_shown ) {
        return;
    }

    $separator_shown = true;

    if ( ! is_product_category() && ! is_shop() ) {
        return;
    }

    if ( 'both' !== woocommerce_get_loop_display_mode() ) {
        return;
    }

    $parent_id     = is_shop() ? 0 : get_queried_object_id();
    $subcategories = woocommerce_get_product_subcategories( $parent_id );
    $subcat_count  = is_array( $subcategories ) ? count( $subcategories ) : 0;

    if ( 0 === $subcat_count ) {
        return;
    }

    // Subcategory tiles increment the WC loop counter via wc_get_loop_class().
    // Reset it so products start fresh with correct first/last column classes.
    wc_set_loop_prop( 'loop', 0 );

    // WooCommerce's mobile CSS uses :nth-child(2n) to pair products into 2-column rows,
    // counting ALL <li> siblings including subcategory tiles and this separator.
    // When subcat count is even, the separator lands at an odd position making Product 1
    // land at an even position (float:right, appearing alone). A display:none spacer shifts
    // the count by 1 so Product 1 is always at an odd position (float:left, correctly paired).
    if ( 0 === $subcat_count % 2 ) {
        echo '<li class="qc-products-separator-spacer" aria-hidden="true"></li>';
    }

    echo '<li class="qc-products-separator"><span>Products</span></li>';
}

/**
 * Register ACF fields for the L-TWOO landing page template.
 */
add_action( 'acf/init', 'ltwoo_register_landing_page_fields' );
function ltwoo_register_landing_page_fields() {

    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group(
        [
            'key'      => 'group_ltwoo_landing_page',
            'title'    => 'L-TWOO Landing Page',
            'fields'   => [
                [
                    'key'   => 'field_ltwoo_hero_eyebrow',
                    'label' => 'Hero Eyebrow',
                    'name'  => 'hero_eyebrow',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_hero_heading',
                    'label' => 'Hero Heading',
                    'name'  => 'hero_heading',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_hero_text',
                    'label' => 'Hero Text',
                    'name'  => 'hero_text',
                    'type'  => 'textarea',
                ],
                [
                    'key'   => 'field_ltwoo_hero_primary_label',
                    'label' => 'Hero Primary Button Label',
                    'name'  => 'hero_primary_label',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_hero_primary_url',
                    'label' => 'Hero Primary Button URL',
                    'name'  => 'hero_primary_url',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_hero_secondary_label',
                    'label' => 'Hero Secondary Button Label',
                    'name'  => 'hero_secondary_label',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_hero_secondary_url',
                    'label' => 'Hero Secondary Button URL',
                    'name'  => 'hero_secondary_url',
                    'type'  => 'text',
                ],
                [
                    'key'           => 'field_ltwoo_hero_bg_image',
                    'label'         => 'Hero Background Image',
                    'name'          => 'hero_bg_image',
                    'type'          => 'image',
                    'return_format' => 'url',
                ],
                [
                    'key'          => 'field_ltwoo_cards',
                    'label'        => 'Feature Cards',
                    'name'         => 'cards',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add Card',
                    'sub_fields'   => [
                        [
                            'key'   => 'field_ltwoo_card_title',
                            'label' => 'Title',
                            'name'  => 'title',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_ltwoo_card_text',
                            'label' => 'Text',
                            'name'  => 'text',
                            'type'  => 'textarea',
                        ],
                    ],
                ],
                [
                    'key'   => 'field_ltwoo_cta_eyebrow',
                    'label' => 'CTA Section Eyebrow',
                    'name'  => 'cta_eyebrow',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_cta_heading',
                    'label' => 'CTA Section Heading',
                    'name'  => 'cta_heading',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_cta_text',
                    'label' => 'CTA Section Text',
                    'name'  => 'cta_text',
                    'type'  => 'textarea',
                ],
                [
                    'key'   => 'field_ltwoo_cta_button_label',
                    'label' => 'CTA Button Label',
                    'name'  => 'cta_button_label',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_cta_button_url',
                    'label' => 'CTA Button URL',
                    'name'  => 'cta_button_url',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_contact_heading',
                    'label' => 'Contact Panel Heading',
                    'name'  => 'contact_heading',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_contact_text',
                    'label' => 'Contact Panel Text',
                    'name'  => 'contact_text',
                    'type'  => 'textarea',
                ],
                [
                    'key'   => 'field_ltwoo_contact_button_label',
                    'label' => 'Contact Button Label',
                    'name'  => 'contact_button_label',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_ltwoo_contact_button_url',
                    'label' => 'Contact Button URL',
                    'name'  => 'contact_button_url',
                    'type'  => 'text',
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'page_template',
                        'operator' => '==',
                        'value'    => 'page-templates/page-ltwoo.php',
                    ],
                ],
            ],
        ]
    );
}

/**
 * Rank Math falls back to a raw, unsanitized get_the_excerpt() for the og/twitter
 * description whenever its own description resolves empty (no manual SEO description,
 * no excerpt, no post content — the case for any hardcoded-template page with no real
 * post_content, not just this one). The Understrap parent theme's `wp_trim_excerpt`
 * filter unconditionally appends a raw "Read More..." link to every excerpt, so that
 * raw HTML was leaking into link previews. Supplying a non-empty description here for
 * any content-less page keeps Rank Math from ever reaching that fallback.
 */
add_filter( 'rank_math/frontend/description', 'qc_rank_math_empty_content_description' );
function qc_rank_math_empty_content_description( $description ) {
    // L-TWOO landing page: prefer the hero copy over a generic fallback.
    if ( is_page_template( 'page-templates/page-ltwoo.php' ) ) {
        $hero_text = get_field( 'hero_text' );
        if ( $hero_text ) {
            return wp_strip_all_tags( $hero_text );
        }
    }

    if ( '' !== trim( wp_strip_all_tags( $description ) ) ) {
        return $description;
    }

    $post_id = get_the_ID();
    if ( ! $post_id || '' !== trim( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) ) ) {
        return $description;
    }

    return get_bloginfo( 'description' ) ?: get_the_title( $post_id );
}

/**
 * "FA Icon" ACF block. ACF blocks preview via ServerSideRender, which injects real
 * rendered markup directly into the main block editor canvas (unlike the Custom HTML
 * block's isolated sandbox), so the icon actually shows while editing.
 */
add_action( 'acf/init', 'ltwoo_register_fa_icon_block' );
function ltwoo_register_fa_icon_block() {

    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    acf_register_block_type(
        [
            'name'            => 'fa-icon',
            'title'           => __( 'FA Icon', 'understrap-child' ),
            'description'     => __( 'A single Font Awesome icon.', 'understrap-child' ),
            'category'        => 'widgets',
            'icon'            => 'star-filled',
            'keywords'        => [ 'icon', 'fontawesome', 'fa' ],
            'render_callback' => 'ltwoo_render_fa_icon_block',
            'supports'        => [ 'align' => false ],
        ]
    );

    acf_add_local_field_group(
        [
            'key'      => 'group_ltwoo_fa_icon_block',
            'title'    => 'FA Icon Block',
            'fields'   => [
                [
                    'key'         => 'field_ltwoo_fa_icon_class',
                    'label'       => 'Icon Classes',
                    'name'        => 'icon_class',
                    'type'        => 'text',
                    'instructions' => 'e.g. fa-duotone fa-regular fa-store fa-2x',
                ],
                [
                    'key'         => 'field_ltwoo_fa_icon_style',
                    'label'       => 'Custom Style (optional)',
                    'name'        => 'icon_style',
                    'type'        => 'text',
                    'instructions' => 'e.g. --fa-primary-color: rgb(245, 131, 0); --fa-secondary-color: rgb(241, 241, 241);',
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'block',
                        'operator' => '==',
                        'value'    => 'acf/fa-icon',
                    ],
                ],
            ],
        ]
    );
}

function ltwoo_render_fa_icon_block() {
    $icon_class = get_field( 'icon_class' );
    if ( ! $icon_class ) {
        return;
    }
    $icon_style = get_field( 'icon_style' );
    $style_attr = $icon_style ? ' style="' . esc_attr( $icon_style ) . '"' : '';
    echo '<i class="' . esc_attr( $icon_class ) . '"' . $style_attr . '></i>';
}

/**
 * Hide other shipping methods when Free Shipping is available.
 * Ensure Postage ($25 flat rate) is the default shipping method.
 */

/**
 * Hide other shipping methods when Free Shipping is available.
 * Ensure Postage ($25) is the default shipping method.
 */
add_filter( 'woocommerce_package_rates', 'qc_hide_shipping_when_free_available', 10, 2 );
function qc_hide_shipping_when_free_available( $rates, $package ) {
	$has_free = false;
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$has_free = true;
			break;
		}
	}

	// If free shipping is available, show ONLY free shipping.
	if ( $has_free ) {
		$new_rates = [];
		foreach ( $rates as $rate_id => $rate ) {
			if ( 'free_shipping' === $rate->method_id ) {
				$new_rates[ $rate_id ] = $rate;
			}
		}
		return $new_rates;
	}

	// Otherwise, ensure flat rate methods (e.g. Postage) come before everything
	// else (e.g. Local Pickup), keeping all flat rate methods, not just the first.
	$sorted_rates = [];
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'flat_rate' === $rate->method_id ) {
			$sorted_rates[ $rate_id ] = $rate;
		}
	}
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'flat_rate' !== $rate->method_id ) {
			$sorted_rates[ $rate_id ] = $rate;
		}
	}

	return $sorted_rates;
}

/**
 * Append cart icon to the end of the primary nav menu.
 */
add_filter( 'wp_nav_menu_items', 'quality_add_cart_to_menu', 10, 2 );

/**
 * Return the current cart item count when the WooCommerce cart is available.
 *
 * @return int
 */
function quality_get_cart_count() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return 0;
	}

	return WC()->cart->get_cart_contents_count();
}

function quality_add_cart_to_menu( $items, $args ) {
	if ( 'primary' !== $args->theme_location || ! class_exists( 'WooCommerce' ) ) {
		return $items;
	}

	$count = quality_get_cart_count();
	$hide  = $count > 0 ? '' : 'display:none;';

	$cart_item = '<li class="menu-item menu-item-type-custom d-none d-md-block">';
	$cart_item .= '<a href="' . esc_url( wc_get_cart_url() ) . '" class="nav-link cart-icon-header" title="View cart">';
	$cart_item .= '<span class="d-inline-block position-relative">';
	$cart_item .= '<i class="fa-duotone fa-light fa-cart-shopping fa-xl" style="--fa-primary-color: rgb(245, 131, 0); --fa-secondary-color: rgb(255, 255, 255);"></i>';
	$cart_item .= '<span class="position-absolute badge rounded-pill bg-warning text-dark cart-count" style="font-size:0.55rem; top:0; right:0; transform:translate(50%,-50%); ' . $hide . '">';
	$cart_item .= esc_html( $count );
	$cart_item .= '</span>';
	$cart_item .= '</span>';
	$cart_item .= '</a>';
	$cart_item .= '</li>';

	return $items . $cart_item;
}

/**
 * Ensure cart fragments script loads — this makes AJAX add-to-cart updates work.
 */
add_action( 'wp_enqueue_scripts', 'quality_ensure_cart_fragments' );
function quality_ensure_cart_fragments() {
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}

/**
 * Cart fragment — serve updated badge HTML to the fragments system.
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'quality_cart_count_fragment' );
function quality_cart_count_fragment( $fragments ) {
	ob_start();
	$count = quality_get_cart_count();
	?>
	<span class="position-absolute badge rounded-pill bg-warning text-dark cart-count" style="font-size:0.55rem; top:0; right:0; transform:translate(50%,-50%); <?php echo $count > 0 ? '' : 'display:none;'; ?>">
		<?php echo esc_html( $count ); ?>
	</span>
	<?php
	$fragments['.cart-count'] = ob_get_clean();
	return $fragments;
}

/**
 * Exclude cart scripts from LiteSpeed deferral.
 */
add_filter( 'litespeed_optm_js_defer_exc', 'quality_exclude_cart_js_from_litespeed' );
add_filter( 'litespeed_optm_js_delay_exc', 'quality_exclude_cart_js_from_litespeed' );
function quality_exclude_cart_js_from_litespeed( $excludes ) {
	$excludes[] = 'wc-cart-fragments';
	$excludes[] = 'wc-add-to-cart';
	$excludes[] = 'woocommerce';
	return $excludes;
}
