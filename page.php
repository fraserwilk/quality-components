<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );

// Optional page sidebar: shows the "Right Sidebar" widget area next to the
// content, but only when that widget area actually has widgets.
$has_sidebar = is_active_sidebar( 'right-sidebar' );

?>

<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<main class="site-main <?php echo $has_sidebar ? 'col-lg-8' : 'col-12'; ?>" id="main">

				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'loop-templates/content', 'page' );

				}
				?>

			</main>

			<?php if ( $has_sidebar ) : ?>
				<aside class="col-lg-4 widget-area" id="page-sidebar" role="complementary">
					<?php dynamic_sidebar( 'right-sidebar' ); ?>
				</aside>
			<?php endif; ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #page-wrapper -->

<?php
get_footer();
