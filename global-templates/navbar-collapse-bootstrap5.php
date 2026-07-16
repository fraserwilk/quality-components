<?php
/**
 * Header Navbar (bootstrap5)
 *
 * @package Understrap
 * @since 1.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<nav id="main-nav" class="navbar navbar-expand-md navbar-dark bg-primary" aria-labelledby="main-nav-label">

	<h2 id="main-nav-label" class="screen-reader-text">
		<?php esc_html_e( 'Main Navigation', 'understrap' ); ?>
	</h2>


	<div class="<?php echo esc_attr( $container ); ?>">

		<!-- Your site branding in the menu -->
		<?php get_template_part( 'global-templates/navbar-branding' ); ?>

		<button
			class="navbar-toggler"
			type="button"
			data-bs-toggle="collapse"
			data-bs-target="#navbarNavDropdown"
			aria-controls="navbarNavDropdown"
			aria-expanded="false"
			aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>"
		>
			<span class="navbar-toggler-icon"></span>
		</button>

		<!-- The WordPress Menu goes here -->
		<?php
		wp_nav_menu(
			array(
				'theme_location'  => 'primary',
				'container_class' => 'collapse navbar-collapse',
				'container_id'    => 'navbarNavDropdown',
				'menu_class'      => 'navbar-nav ms-5',
				'fallback_cb'     => '',
				'menu_id'         => 'main-menu',
				'depth'           => 2,
				'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
			)
		);
		?>

		<div class="header-buttons d-flex align-items-center gap-2 ms-auto">
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-outline-light btn-sm position-relative cart-icon-header d-md-none" title="View cart">
					<i class="fa-duotone fa-light fa-cart-shopping" style="--fa-primary-color: rgb(245, 131, 0); --fa-secondary-color: rgb(255, 255, 255);"></i>
					<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark cart-count" style="font-size:0.6rem; <?php echo WC()->cart->get_cart_contents_count() > 0 ? '' : 'display:none;'; ?>">
						<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
					</span>
				</a>
			<?php endif; ?>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="btn btn-outline-light btn-sm custom-btn-signin">
					Sign Out
				</a>
			<?php else : ?>
				<a href="/my-account/" class="btn btn-light btn-sm custom-btn-apply">
					Sign In / Apply
				</a>
			<?php endif; ?>
		</div>

	</div><!-- .container(-fluid) -->

</nav><!-- #main-nav -->
