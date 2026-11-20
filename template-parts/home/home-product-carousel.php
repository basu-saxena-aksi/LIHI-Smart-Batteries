<?php
/**
 * LiHi homepage product carousel.
 * Include from your homepage template:
 * get_template_part('components/home-product-carousel');
 */
if (!defined('ABSPATH')) { exit; }

$lihi_carousel_products = [];
if (function_exists('wc_get_products')) {
    $lihi_carousel_products = wc_get_products([
        'status'   => 'publish',
        'limit'    => 12,
        'orderby'  => 'date',
        'order'    => 'DESC',
        'category' => ['battery'], // Remove this line to include all product categories.
    ]);
}

if (empty($lihi_carousel_products)) { return; }
$lihi_carousel_id = wp_unique_id('lihi-home-carousel-');
?>
<section class="lihi-home-carousel" aria-labelledby="<?php echo esc_attr($lihi_carousel_id); ?>-heading" data-lihi-carousel>
    <div class="lihi-home-carousel__layout wrap">
        <div class="lihi-home-carousel__intro">
            <span class="lihi-home-carousel__eyebrow">OUR PRODUCT RANGE</span>
            <h2 id="<?php echo esc_attr($lihi_carousel_id); ?>-heading">Power for Every Mission.</h2>
            <p>Explore high-performance smart battery solutions engineered for drones, UAVs, and demanding industrial applications.</p>
            <a class="link-btn" href="<?php echo esc_url(home_url('/product-category/battery/')); ?>">
                <span>
                    Explore All Products
                </span>
                <svg width="15" height="10" viewBox="0 0 13 10" aria-hidden="true">
              <path d="M1,5 L11,5"></path>
              <polyline points="8 1 12 5 8 9"></polyline>
          </svg>
            </a>

            
        </div>

        <div class="lihi-home-carousel__right">
            <div class="lihi-home-carousel__viewport" tabindex="0" role="region" aria-roledescription="carousel" aria-label="LiHi battery products">
                <div class="lihi-home-carousel__track">
                    <?php foreach ($lihi_carousel_products as $lihi_item) :
                        $lihi_id = $lihi_item->get_id();
                        $lihi_image = get_the_post_thumbnail_url($lihi_id, 'large');
                        if (!$lihi_image && function_exists('wc_placeholder_img_src')) {
                            $lihi_image = wc_placeholder_img_src('large');
                        }
                    ?>
                        <article class="lihi-home-carousel__card" role="group" aria-roledescription="slide">
                            <a href="<?php echo esc_url(get_permalink($lihi_id)); ?>" class="lihi-home-carousel__card-link" draggable="false">
                                <div class="lihi-home-carousel__image">
                                    
                                        <img src="http://localhost:10010/wp-content/uploads/2026/08/product2-removebg-preview-2.png" loading="lazy" draggable="false">
                                    
                                    
                                </div>
                                <div class="lihi-home-carousel__card-content">
                                    <h3><?php echo esc_html($lihi_item->get_name()); ?></h3>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="lihi-home-carousel__arrows">
                <button type="button" class="lihi-home-carousel__prev" aria-label="Previous product">←</button>
                <button type="button" class="lihi-home-carousel__next" aria-label="Next product">→</button>
            </div>
        </div>
    </div>
</section>