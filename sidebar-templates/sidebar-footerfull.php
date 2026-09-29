<?php
/**
 * Sidebar setup for footer full
 *
 * Child theme override of Understrap's sidebar-footerfull.php: unlike the
 * parent, this doesn't bail out when the 'footerfull' widget area is empty,
 * since the footer social links below should always render regardless of
 * whether that widget is configured.
 *
 * @package Understrap Child
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<!-- ******************* The Footer Full-width Widget Area ******************* -->

<div class="wrapper" id="wrapper-footer-full" role="complementary">

	<div class="<?php echo esc_attr( $container ); ?>" id="footer-full-content" tabindex="-1">

		<div class="row">

			<?php if ( is_active_sidebar( 'footerfull' ) ) : ?>
				<?php dynamic_sidebar( 'footerfull' ); ?>
			<?php endif; ?>

			<ul class="footer-social">
				<li>
					<a href="https://www.facebook.com/QualityComponentsAU" target="_blank" rel="noopener noreferrer">
						<i class="fa-brands fa-facebook-f"></i>
						<span class="visually-hidden">Facebook</span>
					</a>
				</li>
				<li>
					<a href="https://www.instagram.com/ltwooanz/" target="_blank" rel="noopener noreferrer">
						<i class="fa-brands fa-instagram"></i>
						<span class="visually-hidden">Instagram</span>
					</a>
				</li>
			</ul>

		</div>

	</div>

</div><!-- #wrapper-footer-full -->
