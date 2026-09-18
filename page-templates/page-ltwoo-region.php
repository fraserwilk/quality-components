<?php
/**
 * Template Name: L-TWOO Region Selector
 * Gateway page letting visitors choose between the Australian distributor site
 * (Quality Components) and the official L-TWOO global site (ltwoo.com).
 *
 * @package quality-components
 */

defined( 'ABSPATH' ) || exit;

get_header();

$region_eyebrow = get_field( 'region_eyebrow' ) ?: __( 'L-TWOO', 'quality-components' );
$region_heading = get_field( 'region_heading' ) ?: __( 'Choose your region', 'quality-components' );
$region_text    = get_field( 'region_text' ) ?: __( 'Select the L-TWOO site for your region.', 'quality-components' );

$au_tag    = get_field( 'au_tag' ) ?: __( 'Australia & New Zealand', 'quality-components' );
$au_title   = get_field( 'au_title' ) ?: __( 'Quality Components', 'quality-components' );
$au_text    = get_field( 'au_text' ) ?: __( 'Australian distributor — wholesale accounts, local stock, Australian-backed warranty and technical support for dealers and workshops.', 'quality-components' );
$au_button  = get_field( 'au_button_label' ) ?: __( 'Visit Quality Components', 'quality-components' );
$au_url     = get_field( 'au_url' ) ?: home_url( '/' );

$cn_tag    = get_field( 'cn_tag' ) ?: __( 'Global', 'quality-components' );
$cn_title   = get_field( 'cn_title' ) ?: __( 'L-TWOO Global', 'quality-components' );
$cn_text    = get_field( 'cn_text' ) ?: __( 'Official L-TWOO site — the full global product range from the manufacturer in Zhuhai, China.', 'quality-components' );
$cn_button  = get_field( 'cn_button_label' ) ?: __( 'Visit ltwoo.com', 'quality-components' );
$cn_url     = get_field( 'cn_url' ) ?: 'https://ltwoo.com';
?>

<main id="primary" class="site-main ltwoo-landing-page">

	<style>
		.ltwoo-region-card {
			display: flex;
			flex-direction: column;
		}
		.ltwoo-region-card .ltwoo-region-tag {
			margin-bottom: 0.75rem;
			color: var(--ltwoo-primary);
			font-size: 0.78rem;
			font-weight: 700;
			letter-spacing: 0.14em;
			text-transform: uppercase;
		}
		.ltwoo-region-card .btn {
			align-self: flex-start;
			margin-top: auto;
		}
	</style>

	<section class="ltwoo-hero">
		<div class="container py-5">
			<p class="ltwoo-eyebrow"><?php echo esc_html( $region_eyebrow ); ?></p>
			<h1><?php echo esc_html( $region_heading ); ?></h1>
			<p><?php echo esc_html( $region_text ); ?></p>
		</div>
	</section>

	<section class="ltwoo-light-section">
		<div class="container">
			<div class="row g-4">

				<div class="col-md-6">
					<div class="ltwoo-card ltwoo-region-card">
						<p class="ltwoo-region-tag"><?php echo esc_html( $au_tag ); ?></p>
						<h3><?php echo esc_html( $au_title ); ?></h3>
						<p><?php echo esc_html( $au_text ); ?></p>
						<a href="<?php echo esc_url( $au_url ); ?>" class="btn ltwoo-btn"><?php echo esc_html( $au_button ); ?></a>
					</div>
				</div>

				<div class="col-md-6">
					<div class="ltwoo-card ltwoo-region-card">
						<p class="ltwoo-region-tag"><?php echo esc_html( $cn_tag ); ?></p>
						<h3><?php echo esc_html( $cn_title ); ?></h3>
						<p><?php echo esc_html( $cn_text ); ?></p>
						<a href="<?php echo esc_url( $cn_url ); ?>" class="btn ltwoo-btn-outline"><?php echo esc_html( $cn_button ); ?></a>
					</div>
				</div>

			</div>
		</div>
	</section>

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php
			the_content();

			wp_link_pages(
				[
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'quality-components' ),
					'after'  => '</div>',
				]
			);
			?>

		</article>

	<?php endwhile; ?>

</main>

<?php
// Footer intentionally omitted for this gateway page.
// Close the #page div opened by header.php, then output scripts and close body/html.
?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>
