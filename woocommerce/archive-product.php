<?php
/**
 * The Template for displaying product archives, including the main shop page.
 *
 * This overrides the WooCommerce default to add a category banner (image,
 * name, description, subcategory tiles) above the sidebar + product grid.
 *
 * @package UnderstrapChild
 * @version 8.6.0 (based on WooCommerce template 8.6.0)
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

// ── Category banner ──────────────────────────────────────────────────────────
if ( is_product_category() ) {
	$term         = get_queried_object();
	$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
	$image_url    = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'full' ) : '';
	$description  = term_description();
	$children     = get_terms(
		[
			'taxonomy'   => 'product_cat',
			'parent'     => $term->term_id,
			'hide_empty' => true,
		]
	);

	$container = get_theme_mod( 'understrap_container_type' ) ?: 'container';
	?>
	<div class="category-banner<?php echo $image_url ? ' category-banner--has-image' : ''; ?>"
	     <?php if ( $image_url ) : ?>
	     style="background-image: url('<?php echo esc_url( $image_url ); ?>');"
	     <?php endif; ?>>
		<div class="<?php echo esc_attr( $container ); ?>">
			<h1 class="category-banner__title"><?php echo esc_html( $term->name ); ?></h1>
			<?php if ( $description ) : ?>
				<div class="category-banner__desc"><?php echo wp_kses_post( $description ); ?></div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( ! empty( $children ) && ! is_wp_error( $children ) ) : ?>
	<div class="category-subcats">
		<div class="<?php echo esc_attr( $container ); ?>">
			<div class="category-subcats__grid">
				<?php foreach ( $children as $child ) : ?>
					<?php
					$child_thumb_id  = get_term_meta( $child->term_id, 'thumbnail_id', true );
					$child_image_url = $child_thumb_id ? wp_get_attachment_image_url( $child_thumb_id, 'medium' ) : '';
					?>
					<a href="<?php echo esc_url( get_term_link( $child ) ); ?>" class="category-subcats__item">
						<?php if ( $child_image_url ) : ?>
							<img src="<?php echo esc_url( $child_image_url ); ?>"
							     alt="<?php echo esc_attr( $child->name ); ?>"
							     class="category-subcats__img"
							     loading="lazy">
						<?php endif; ?>
						<span class="category-subcats__label"><?php echo esc_html( $child->name ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

<?php } ?>

<?php
// ── Main content (breadcrumb + sidebar + product grid) ────────────────────────
/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked quality_woocommerce_wrapper_start - 10 (opens container, row, sidebar col, main col)
 * @hooked woocommerce_breadcrumb             - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @hooked woocommerce_product_taxonomy_archive_header - 10
 *         (outputs category image/description from WC — suppressed here via CSS
 *          since we render our own banner above)
 */
do_action( 'woocommerce_shop_loop_header' );

if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count       - 20
	 * @hooked woocommerce_catalog_ordering   - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();
			do_action( 'woocommerce_shop_loop' );
			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );

} else {
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked quality_woocommerce_wrapper_end - 10 (closes columns, row, container)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
