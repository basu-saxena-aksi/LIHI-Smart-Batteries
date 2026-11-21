<?php
defined('ABSPATH') || exit;

if (is_product_category('charger') || is_product_category('chargers')) {
    wp_safe_redirect(home_url('/chargers-coming-soon/'));
    exit;
}

get_header();

// Current archive URL without filter parameters.
$reset_url = get_post_type_archive_link('product');

if (is_product_taxonomy()) {
    $queried_term = get_queried_object();

    if ($queried_term instanceof WP_Term) {
        $term_url = get_term_link($queried_term);

        if (!is_wp_error($term_url)) {
            $reset_url = $term_url;
        }
    }
}

if (!$reset_url) {
    $reset_url = wc_get_page_permalink('shop');
}
?>

<?php
get_template_part('components/parallax-hero', null, [
    'eyebrow' => 'OUR PRODUCT RANGE',
    'heading' => 'POWER.',
    'highlight' => 'INTELLIGENCE. FUTURE.',
    'description' => 'Explore high-performance battery solutions engineered for demanding applications.',
    'background' => get_template_directory_uri() . '/images/about-us-hero-bg.jpg',
]);
?>

<main class="lihi-product-archive">
    <div class="wrap">

        <?php woocommerce_breadcrumb(); ?>

        <div class="archive-header">
            <?php $category = woocommerce_page_title(false); ?>

            <h1>
                <?php
                if ($category === 'Battery') {
                    echo 'Batteries';
                } elseif ($category === 'Charger') {
                    echo 'Chargers';
                } else {
                    echo esc_html($category);
                }
                ?>
            </h1>
        </div>

        <!-- Mobile and tablet filter trigger -->
        <button type="button" class="archive-filter-open" aria-controls="archive-filter-panel" aria-expanded="false">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M4 6h16M7 12h10M10 18h4" />
            </svg>
            <span>Filters</span>
        </button>

        <div class="archive-layout">

            <aside class="archive-filters" id="archive-filter-panel">

                <div class="archive-filters-header">
                    <h3 class="archive-filters-title">Filters</h3>

                    <div class="archive-filters-actions">
                        <a href="<?php echo esc_url($reset_url); ?>" class="archive-custom-reset">
                            Reset all
                        </a>

                        <button type="button" class="archive-filter-close" aria-label="Close filters">
                            &times;
                        </button>
                    </div>
                </div>

                <?php echo do_shortcode('[fe_widget show_selected="yes"]'); ?>

                <div class="archive-filter-footer">
                    <button type="button" class="archive-filter-apply">
                        Show Results
                    </button>
                </div>

            </aside>

            <div class="archive-filter-backdrop"></div>

            <div class="archive-products">

                <div class="archive-products-toolbar">

                    <div class="badge home-eyebrow archive-home-eyebrow">
                        <span class="badge-marquee-viewport">
                            <span class="badge-marquee">

                                <span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2L14.8 9.2L22 12L14.8 14.8L12 22L9.2 14.8L2 12L9.2 9.2Z" />
                                    </svg>
                                    HAVE CUSTOM BATTERY REQUIREMENTS? CONTACT OUR TEAM
                                </span>

                                <span aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2L14.8 9.2L22 12L14.8 14.8L12 22L9.2 14.8L2 12L9.2 9.2Z" />
                                    </svg>
                                    HAVE CUSTOM BATTERY REQUIREMENTS? CONTACT OUR TEAM
                                </span>

                                <span aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2L14.8 9.2L22 12L14.8 14.8L12 22L9.2 14.8L2 12L9.2 9.2Z" />
                                    </svg>
                                    HAVE CUSTOM BATTERY REQUIREMENTS? CONTACT OUR TEAM
                                </span>

                            </span>
                        </span>
                    </div>

                    <a href="<?php echo esc_url(home_url('/custom-battery-request/')); ?>" class="link-btn">
                        <span>Customise Now</span>
                        <svg width="15" height="10" viewBox="0 0 13 10" aria-hidden="true">
                            <path d="M1,5 L11,5"></path>
                            <polyline points="8 1 12 5 8 9"></polyline>
                        </svg>
                    </a>

                </div>

                <!-- Keep this as the Filter Everything AJAX results container -->
                <div class="archive-results">

                    <?php if (woocommerce_product_loop()): ?>

                        <?php woocommerce_product_loop_start(); ?>

                        <?php
                        if (wc_get_loop_prop('total')) {
                            while (have_posts()) {
                                the_post();

                                do_action('woocommerce_shop_loop');

                                wc_get_template_part('content', 'product');
                            }
                        }
                        ?>

                        <?php woocommerce_product_loop_end(); ?>

                        <?php
                        global $wp_query;

                        $current_page = max(
                            1,
                            (int) get_query_var('paged'),
                            (int) get_query_var('page')
                        );

                        $max_pages = (int) $wp_query->max_num_pages;
                        ?>

                        <?php if ($current_page < $max_pages): ?>

                            <div class="load-more-wrapper">

                                <button type="button" class="link-btn load-more-btn"
                                    data-page="<?php echo esc_attr($current_page); ?>"
                                    data-max-pages="<?php echo esc_attr($max_pages); ?>">
                                    <span class="load-more-text">Load More Products</span>
                                    <svg width="15" height="10" viewBox="0 0 13 10" aria-hidden="true">
                                        <path d="M1,5 L11,5"></path>
                                        <polyline points="8 1 12 5 8 9"></polyline>
                                    </svg>

                                    <span class="loader" aria-hidden="true">
                                        <span class="bar1"></span>
                                        <span class="bar2"></span>
                                        <span class="bar3"></span>
                                        <span class="bar4"></span>
                                        <span class="bar5"></span>
                                        <span class="bar6"></span>
                                    </span>
                                </button>

                            </div>

                        <?php endif; ?>

                    <?php else: ?>

                        <div class="archive-no-products">

                            <h3>No Products Found</h3>

                            <p>
                                No products match your selected filters.
                                Try changing or clearing your filters.
                            </p>

                            <a href="<?php echo esc_url($reset_url); ?>" class="link-btn">
                                <span>Clear Filters</span>
                                <svg width="15" height="10" viewBox="0 0 13 10" aria-hidden="true">
                                    <path d="M1,5 L11,5"></path>
                                    <polyline points="8 1 12 5 8 9"></polyline>
                                </svg>
                            </a>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>
    </div>
</main>

<?php get_footer(); ?>