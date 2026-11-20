
<?php
defined('ABSPATH') || exit;

global $product;

if (!$product || !$product->is_visible()) {
    return;
}

$product_url = get_permalink($product->get_id());
$image_id = $product->get_image_id();
?>

<li <?php wc_product_class('lihi-archive-product-card', $product); ?>>

    <a
        href="<?php echo esc_url($product_url); ?>"
        class="lihi-archive-product-link"
        aria-label="<?php echo esc_attr($product->get_name()); ?>"
    >

        
        <?php
        $image_url = $image_id
            ? wp_get_attachment_url($image_id)
            : '';

        if (!$image_url) {
            $image_url = wc_placeholder_img_src();
        }
        ?>

        <img
            src="<?php echo esc_url($image_url); ?>"
            alt="<?php echo esc_attr($product->get_name()); ?>"
            class="lihi-archive-product-image"
            loading="lazy"
        >


        <h2 class="woocommerce-loop-product__title">
            <?php echo esc_html($product->get_name()); ?>
        </h2>

        <?php if ($product->get_price_html()) : ?>

            <span class="price">
                <?php echo wp_kses_post($product->get_price_html()); ?>
            </span>

        <?php endif; ?>

    </a>

</li>
