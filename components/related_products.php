<?php
/**
 * Related Products Component
 *
 * Industry pages: ACF selected products.
 * Application pages: ACF selected products.
 * Product pages: WooCommerce related products.
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_post_id = get_queried_object_id();

$query_args = array(
    'post_type'      => 'product',
    'posts_per_page' => 8,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
);

if (is_singular(['industry', 'application'])) {

    // Products selected in the Industry ACF field.
    $selected_products = get_field(
        'related_products',
        $current_post_id
    );

    if (empty($selected_products)) {
        return;
    }

    $product_ids = array();

    foreach ($selected_products as $selected_product) {
        $product_ids[] = is_object($selected_product)
            ? (int) $selected_product->ID
            : (int) $selected_product;
    }

    $product_ids = array_values(
        array_filter(array_unique($product_ids))
    );

    if (empty($product_ids)) {
        return;
    }

    $query_args['post__in'] = $product_ids;
    $query_args['posts_per_page'] = count($product_ids);
    $query_args['orderby'] = 'post__in';

} else {

    // Existing behavior for WooCommerce product pages.
    $query_args['post__not_in'] = array($current_post_id);

}

$related_products = new WP_Query($query_args);

if ($related_products->have_posts()) :
?>

<section class="section related-products">

    <div class="wrap">

        <div class="section-head">

            <div>
                <span class="eyebrow">YOU MAY ALSO LIKE</span>

                <h2>
                    RELATED <span class="g">PRODUCTS</span>
                </h2>
            </div>

            <div class="related-products-controls">

                <button
                    type="button"
                    class="related-products-btn related-products-prev"
                    aria-label="Previous products"
                >
                    ←
                </button>

                <button
                    type="button"
                    class="related-products-btn related-products-next"
                    aria-label="Next products"
                >
                    →
                </button>

            </div>

        </div>


        <div class="related-products-carousel">

            <div class="related-products-track">

                <?php while ($related_products->have_posts()) : ?>

                    <?php
                    $related_products->the_post();

                    $product_id = get_the_ID();

                    $image_id = get_post_thumbnail_id($product_id);

                    $image_url = $image_id
                        ? wp_get_attachment_image_url($image_id, 'medium')
                        : '';

                    $product_type = get_post_meta(
                        $product_id,
                        '_lihi_product_type',
                        true
                    );

                    $spec_1 = get_post_meta(
                        $product_id,
                        '_lihi_spec_1',
                        true
                    );

                    $spec_2 = get_post_meta(
                        $product_id,
                        '_lihi_spec_2',
                        true
                    );
                    ?>

                    <a
                        href="<?php echo esc_url(get_permalink($product_id)); ?>"
                        class="p-card related-product-card"
                    >

                        <div class="p-thumb">

                            <?php if ($image_url) : ?>

                                <img
                                    src="<?php echo esc_url($image_url); ?>"
                                    alt="<?php echo esc_attr(get_the_title($product_id)); ?>"
                                >

                            <?php else : ?>

                                <div class="related-product-placeholder">
                                    <i data-lucide="battery-charging"></i>
                                </div>

                            <?php endif; ?>

                        </div>


                        <div class="p-body">

                            <?php if ($product_type) : ?>

                                <span class="product-type">
                                    <?php echo esc_html($product_type); ?>
                                </span>

                            <?php endif; ?>


                            <h4>
                                <?php echo esc_html(get_the_title($product_id)); ?>
                            </h4>


                            <?php if ($spec_1 || $spec_2) : ?>

                                <ul>

                                    <?php if ($spec_1) : ?>
                                        <li>
                                            <?php echo esc_html($spec_1); ?>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($spec_2) : ?>
                                        <li>
                                            <?php echo esc_html($spec_2); ?>
                                        </li>
                                    <?php endif; ?>

                                </ul>

                            <?php endif; ?>


                            <span class="related-product-link">
                                View Details
                                <span>→</span>
                            </span>

                        </div>

                    </a>

                <?php endwhile; ?>

            </div>

        </div>

    </div>

</section>

<?php
wp_reset_postdata();
endif;
?>