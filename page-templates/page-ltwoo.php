<?php
/**
 * Template Name: L-TWOO Landing Page
 * B2B landing page for LTWOO components, designed to provide a clear overview of the product range, dealer network, and support services offered by Quality Components in Australia and New Zealand.
 */

defined('ABSPATH') || exit;

get_header();

$hero_eyebrow        = get_field('hero_eyebrow') ?: __('L-TWOO Components', 'understrap-child');
$hero_heading        = get_field('hero_heading') ?: __('Precision drivetrain components for Australia', 'understrap-child');
$hero_text           = get_field('hero_text') ?: __('L-TWOO delivers reliable, affordable groupsets and components backed by Australian-backed warranty, a growing dealer network and dedicated local support.', 'understrap-child');
$hero_primary_label  = get_field('hero_primary_label') ?: __('Shop the range', 'understrap-child');
$hero_primary_url    = get_field('hero_primary_url') ?: wc_get_page_permalink('shop');
$hero_secondary_label = get_field('hero_secondary_label') ?: __('Become a dealer', 'understrap-child');
$hero_secondary_url  = get_field('hero_secondary_url') ?: '/my-account';
$hero_bg_image       = get_field('hero_bg_image');

$cards = [];
if (have_rows('cards')) {
    while (have_rows('cards')) {
        the_row();
        $cards[] = [
            'title' => get_sub_field('title'),
            'text'  => get_sub_field('text'),
        ];
    }
} else {
    $cards = [
        [
            'title' => __('Product Range', 'understrap-child'),
            'text'  => __('A full lineup of Road, Gravel & MTB components for drivetrain, brake and cockpit - all engineered for performance and value.', 'understrap-child'),
        ],
        [
            'title' => __('Dealer Network', 'understrap-child'),
            'text'  => __('An expanding network of stocking dealers across Australia and New Zealand, backed by local warranty & warehousing.', 'understrap-child'),
        ],
        [
            'title' => __('Support Services', 'understrap-child'),
            'text'  => __('Technical support, warranty handling, and marketing assets to help dealers sell with confidence.', 'understrap-child'),
        ],
    ];
}

$cta_eyebrow       = get_field('cta_eyebrow') ?: __('Why L-TWOO', 'understrap-child');
$cta_heading       = get_field('cta_heading') ?: __('Built for riders, priced for dealers', 'understrap-child');
$cta_text          = get_field('cta_text') ?: __('L-TWOO components are designed to give riders confident performance while giving dealers healthy margins and dependable stock availability.', 'understrap-child');
$cta_button_label  = get_field('cta_button_label') ?: __('Browse products', 'understrap-child');
$cta_button_url    = get_field('cta_button_url') ?: wc_get_page_permalink('shop');

$contact_heading      = get_field('contact_heading') ?: __('Talk to our team', 'understrap-child');
$contact_text         = get_field('contact_text') ?: __('Get in touch for pricing, dealer enquiries, or product support.', 'understrap-child');
$contact_button_label = get_field('contact_button_label') ?: __('Contact us', 'understrap-child');
$contact_button_url   = get_field('contact_button_url') ?: home_url('/my-account');
?>

<main id="primary" class="site-main ltwoo-landing-page">

    <section class="ltwoo-hero"<?php echo $hero_bg_image ? ' style="background-image:url(\'' . esc_url($hero_bg_image) . '\');"' : ''; ?>>
        <div class="container py-5">
            <p class="ltwoo-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>
            <h1><?php echo esc_html($hero_heading); ?></h1>
            <p><?php echo esc_html($hero_text); ?></p>
            <a href="<?php echo esc_url($hero_primary_url); ?>" class="btn ltwoo-btn"><?php echo esc_html($hero_primary_label); ?></a>
            <a href="<?php echo esc_url($hero_secondary_url); ?>" class="btn ltwoo-btn-outline"><?php echo esc_html($hero_secondary_label); ?></a>
        </div>
    </section>

    <section class="ltwoo-light-section">
        <div class="container">
            <div class="row g-4">
                <?php foreach ($cards as $card) : ?>
                    <div class="col-md-4">
                        <div class="ltwoo-card">
                            <h3><?php echo esc_html($card['title']); ?></h3>
                            <p><?php echo esc_html($card['text']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="ltwoo-dark-section">
        <div class="container">
            <p class="ltwoo-eyebrow"><?php echo esc_html($cta_eyebrow); ?></p>
            <h2><?php echo esc_html($cta_heading); ?></h2>
            <p><?php echo esc_html($cta_text); ?></p>
            <a href="<?php echo esc_url($cta_button_url); ?>" class="btn ltwoo-btn-outline"><?php echo esc_html($cta_button_label); ?></a>
        </div>
    </section>

    <section id="contact" class="ltwoo-light-section">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-8">
                    <div class="ltwoo-contact-panel">
                        <h3><?php echo esc_html($contact_heading); ?></h3>
                        <p><?php echo esc_html($contact_text); ?></p>
                        <a href="<?php echo esc_url($contact_button_url); ?>" class="btn ltwoo-btn"><?php echo esc_html($contact_button_label); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    while (have_posts()) :
        the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php
            the_content();

            wp_link_pages(
                [
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'understrap-child'),
                    'after'  => '</div>',
                ]
            );
            ?>

        </article>

    <?php endwhile; ?>

</main>

<?php
get_footer();
