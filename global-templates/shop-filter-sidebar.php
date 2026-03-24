<?php
/**
 * Shop filter sidebar
 *
 * Rendered on shop/archive pages in place of the general left-sidebar-check.
 *
 * @package UnderstrapChild
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="col-md-3 shop-filter-sidebar" id="shop-filters">

	<button class="shop-filter-toggle d-md-none btn btn-outline-secondary w-100 mb-3"
	        type="button"
	        data-bs-toggle="collapse"
	        data-bs-target="#shop-filter-widgets"
	        aria-expanded="false"
	        aria-controls="shop-filter-widgets">
		<?php esc_html_e( 'Filters', 'understrap' ); ?>
	</button>

	<div class="shop-filter-widgets collapse" id="shop-filter-widgets">
		<?php dynamic_sidebar( 'shop-filters-sidebar' ); ?>
	</div>

</div><!-- .shop-filter-sidebar -->
