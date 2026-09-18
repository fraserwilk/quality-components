<?php
/**
 * Template part: Dealer Economics panel
 * Usage: get_template_part( 'template-parts/dealer-economics' );
 */

$margin_min      = get_field( 'margin_min', 'option' );
$margin_max      = get_field( 'margin_max', 'option' );
$stock_percent   = get_field( 'stock_percent', 'option' );
$dispatch_cutoff = get_field( 'dispatch_cutoff_time', 'option' );
$dispatch_speed  = get_field( 'dispatch_speed', 'option' ); // 'same-day' or 'next-day'
$response_hours  = get_field( 'support_response_hours', 'option' );
$margin_sample   = get_field( 'margin_sample', 'option' ); // WYSIWYG or image field

$dispatch_label = ( $dispatch_speed === 'next-day' ) ? 'next' : 'same';
?>

<section class="qc-dealer-economics">
	<div class="container">
		<div class="row qc-econ-grid">

			<div class="col-md-6 col-lg-3 mb-4">
				<div class="ltwoo-card">
					<span class="ltwoo-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
					</span>
					<h3>More room in the sale</h3>
					<p>
						Dealer pricing built for profit - typically
						<strong><?php echo esc_html( $margin_min ); ?>-<?php echo esc_html( $margin_max ); ?>%</strong>
						gross margin - while riders still get a lower-cost drivetrain option.
					</p>
				</div>
			</div>

			<div class="col-md-6 col-lg-3 mb-4">
				<div class="ltwoo-card">
					<span class="ltwoo-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="1"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
					</span>
					<h3>Stock held in Australia</h3>
					<p>
						<strong><?php echo esc_html( $stock_percent ); ?>%</strong>
						of top selling dealer lines in stock now — see live status before you order, no overseas shipping wait.
					</p>
				</div>
			</div>

			<div class="col-md-6 col-lg-3 mb-4">
				<div class="ltwoo-card">
					<span class="ltwoo-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
					</span>
					<h3>Fast, predictable delivery</h3>
					<p>
						Order before <strong><?php echo esc_html( $dispatch_cutoff ); ?></strong> AEST,
						dispatch <strong><?php echo esc_html( $dispatch_label ); ?></strong> business day.
						Shipping capped at $25.
					</p>
				</div>
			</div>

			<div class="col-md-6 col-lg-3 mb-4">
				<div class="ltwoo-card">
					<span class="ltwoo-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
					</span>
					<h3>Local technical and warranty support</h3>
					<p>
						An Australian contact for setup, compatibility, parts and warranty claims.
						Typical response time: <strong><?php echo esc_html( $response_hours ); ?> hours</strong>.
					</p>
				</div>
			</div>

		</div>

<!-- 		<div class="qc-margin-gate">
			<div class="qc-margin-gate-prompt">
				<p>Enter your work email to view a sample dealer-margin breakdown</p>
				<php echo do_shortcode( '[wpforms id="REPLACE_WITH_FORM_ID"]' ); ?>
			</div>
			<div class="qc-margin-sample" style="display:none;">
				<php echo $margin_sample; ?>
			</div>
		</div>
	</div>
 --></section>