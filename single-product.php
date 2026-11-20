<?php
defined('ABSPATH') || exit;

get_header();

if (have_posts()):
    while (have_posts()):
        the_post();

        $id = get_the_ID();
        $product = function_exists('wc_get_product') ? wc_get_product($id) : false;

        if (!$product) {
            continue;
        }

        /* =========================================
           PRODUCT FIELD HELPER
        ========================================= */

        $field = static function ($name) use ($id) {
            $value = function_exists('get_field')
                ? get_field($name, $id)
                : get_post_meta($id, $name, true);

            return is_scalar($value) ? trim((string) $value) : '';
        };


        /* =========================================
           PRODUCT CONTENT
        ========================================= */

        $description = $product->get_description();
        $excerpt = $product->get_short_description();

        $spec_html = $field('technical_specification');


        /* =========================================
           BUY URL
        ========================================= */

        $buy_url = $field('external_store_url');

        if (!$buy_url) {
            $buy_url = get_post_meta($id, '_lihi_buy_url', true);
        }

        if (!$buy_url && $product->is_type('external')) {
            $buy_url = $product->get_product_url();
        }


        /* =========================================
           PRODUCT DATASHEET
        ========================================= */

        $datasheet = function_exists('get_field')
            ? get_field('product_datasheet', $id)
            : get_post_meta($id, 'product_datasheet', true);

        if (is_array($datasheet)) {
            $datasheet_url = $datasheet['url'] ?? '';
        } elseif (is_numeric($datasheet)) {
            $datasheet_url = wp_get_attachment_url((int) $datasheet);
        } else {
            $datasheet_url = $datasheet;
        }

        $datasheet_url = is_string($datasheet_url) ? trim($datasheet_url) : '';


        /* =========================================
           FEATURED PRODUCT IMAGE
        ========================================= */

        $main_id = $product->get_image_id();
        $main_full_url = $main_id
            ? wp_get_attachment_image_url($main_id, 'full')
            : '';


        /* =========================================
           ADDITIONAL PRODUCT GALLERY IMAGES
           First two images only
        ========================================= */

        $gallery_ids = $product->get_gallery_image_ids();
        $gallery_ids = array_slice($gallery_ids, 0, 2);

        $gallery_images = [];

        foreach ($gallery_ids as $gallery_id) {

            $gallery_full_url = wp_get_attachment_image_url($gallery_id, 'full');

            if (!$gallery_full_url) {
                continue;
            }

            $gallery_alt = get_post_meta(
                $gallery_id,
                '_wp_attachment_image_alt',
                true
            );

            $gallery_images[] = [
                'id' => $gallery_id,
                'url' => $gallery_full_url,
                'alt' => $gallery_alt ?: $product->get_name(),
            ];
        }


        
        /* =========================================
        NORMALIZE WORDPRESS UPLOAD URL
        Supports localhost, live tunnel and production
        ========================================= */

        $lihi_normalize_upload_url = function ($url) {

            if (!is_string($url) || trim($url) === '') {
                return '';
            }

            $url = trim($url);

            $parsed_url = wp_parse_url($url);

            if (
                !empty($parsed_url['path']) &&
                strpos($parsed_url['path'], '/wp-content/uploads/') === 0
            ) {

                $url = $parsed_url['path'];

                if (!empty($parsed_url['query'])) {
                    $url .= '?' . $parsed_url['query'];
                }

                if (!empty($parsed_url['fragment'])) {
                    $url .= '#' . $parsed_url['fragment'];
                }

            }

            return $url;
        };


        /* =========================================
        3D MODEL
        Accepts URL, attachment ID, or ACF array
        ========================================= */

        $model_value = function_exists('get_field')
            ? get_field('product_3d_model', $id)
            : get_post_meta($id, 'product_3d_model', true);

        if (is_array($model_value)) {

            $model_url = $model_value['url'] ?? '';

        } elseif (is_numeric($model_value)) {

            $model_url = wp_get_attachment_url((int) $model_value);

        } else {

            $model_url = $model_value;

        }

        $model_url = $lihi_normalize_upload_url($model_url);


        /* =========================================
        3D MODEL POSTER
        ========================================= */

        $poster_value = function_exists('get_field')
            ? get_field('product_3d_poster', $id)
            : get_post_meta($id, 'product_3d_poster', true);

        if (is_array($poster_value)) {

            $poster_url = $poster_value['url'] ?? '';

        } elseif (is_numeric($poster_value)) {

            $poster_url = wp_get_attachment_image_url(
                (int) $poster_value,
                'large'
            );

        } else {

            $poster_url = $poster_value;

        }

        $poster_url = $lihi_normalize_upload_url($poster_url);


        // Fallback to featured product image.
        if (!$poster_url) {
            $poster_url = $lihi_normalize_upload_url($main_full_url);
        }



        /* =========================================
           QUICK SPECIFICATIONS
        ========================================= */

        $quick_specs = [];

        for ($n = 1; $n <= 5; $n++) {

            $value = $field('key_counter_' . $n);
            $label = $field('key_counter_' . $n . '_text');

            if ($value !== '') {
                $quick_specs[] = [
                    'label' => $label ?: 'Specification ' . $n,
                    'value' => $value,
                ];
            }
        }


        /* =========================================
           LEGACY SPECIFICATIONS
        ========================================= */

        $legacy_specs = [];

        if (trim(wp_strip_all_tags($spec_html)) === '') {

            for ($n = 1; $n <= 3; $n++) {

                $value = $field('product_spec_' . $n);

                if ($value === '') {
                    continue;
                }

                $parts = array_map('trim', explode('|', $value, 2));

                $legacy_specs[] = count($parts) === 2
                    ? $parts
                    : ['Specification ' . $n, $value];
            }
        }


        /* =========================================
           CONTENT AVAILABILITY
        ========================================= */

        $has_specs = (
            trim(wp_strip_all_tags($spec_html)) !== ''
            || !empty($legacy_specs)
        );

        $has_description = trim(wp_strip_all_tags($description)) !== '';

        ?>

        <main class="lp-page">

            <!-- =========================================
         PRODUCT HERO
    ========================================== -->

            <section class="lp-hero">

                <div class="wrap">

                    <div class="lp-hero-grid">


                        <!-- =================================
                     LEFT: PRODUCT GALLERY
                ================================== -->

                        <div class="lp-image-column">

                            <div class="lp-sticky-media <?php echo empty($gallery_images) ? 'has-no-gallery' : ''; ?>">

                                <!-- MAIN FEATURED MEDIA -->

                                <div class="lp-gallery-main">

                                    <div class="lp-image-box" data-lenis-prevent>

                                        <?php if ($model_url): ?>

                                                <!-- 3D MODEL -->
                                                
                                                <model-viewer
                                                    class="lp-model-viewer"
                                                    src="<?php echo esc_url($model_url); ?>"
                                                    <?php if ($poster_url): ?>
                                                            poster="<?php echo esc_url($poster_url); ?>"
                                                    <?php endif; ?>
                                                    alt="Interactive 3D model of <?php echo esc_attr($product->get_name()); ?>"
                                                    camera-controls
                                                    touch-action="none"
                                                    shadow-intensity="0.7"
                                                    exposure="1"
                                                    interaction-prompt="auto"
                                                    loading="eager" data-lenis-prevent>
                                                    

                                                    <div class="lp-model-error" slot="error">
                                                        3D model is loading !
                                                    </div>

                                                </model-viewer>

                                                 <button
                                                    type="button"
                                                    class="lp-model-fullscreen-close"
                                                    aria-label="Close fullscreen">
                                                    <i data-lucide="x"></i>
                                                </button>

                                        <?php elseif ($main_id && $main_full_url): ?>

                                                <!-- FEATURED IMAGE -->

                                                <a
                                                    href="<?php echo esc_url($main_full_url); ?>"
                                                    class="lp-image-link"
                                                    data-lp-lightbox
                                                    aria-label="Enlarge product image">

                                                    <img
                                                        src="<?php echo esc_url($main_full_url); ?>"
                                                        alt="<?php echo esc_attr($product->get_name()); ?>"
                                                        class="lp-product-image"
                                                        loading="eager">

                                                </a>

                                        <?php else: ?>

                                                <div class="lp-image-placeholder">
                                                    No product media available
                                                </div>

                                        <?php endif; ?>

                                    </div>


                                    <!-- 3D MODEL TOOLBAR -->

                                    <?php if ($model_url): ?>

                                        <div class="lp-model-toolbar">

                                            <span>
                                                <i data-lucide="rotate-3d"></i>
                                                Drag to rotate · Scroll to zoom
                                            </span>

                                            <button
                                                type="button"
                                                class="lp-model-fullscreen-toggle"
                                                aria-label="View model in full screen">

                                                View model
                                                <i data-lucide="expand"></i>

                                            </button>

                                        </div>

                                    <?php endif; ?>

                                    </div>

                                <!-- TWO ADDITIONAL GALLERY IMAGES -->

                                <?php if (!empty($gallery_images)): ?>

                                        <div class="lp-gallery-bottom <?php echo count($gallery_images) === 1 ? 'has-one-image' : ''; ?>">

                                            <?php foreach ($gallery_images as $gallery_image): ?>

                                                    <a
                                                        href="<?php echo esc_url($gallery_image['url']); ?>"
                                                        class="lp-gallery-thumb"
                                                        data-lp-lightbox
                                                        aria-label="Enlarge <?php echo esc_attr($gallery_image['alt']); ?>">

                                                        <img
                                                            src="<?php echo esc_url(
                                                                wp_get_attachment_image_url($gallery_image['id'], 'large')
                                                                ?: $gallery_image['url']
                                                            ); ?>"
                                                            alt="<?php echo esc_attr($gallery_image['alt']); ?>"
                                                            class="lp-gallery-thumb-image"
                                                            loading="eager"
                                                            decoding="async">

                                                    </a>

                                            <?php endforeach; ?>

                                        </div>

                                <?php endif; ?>

                            </div>

                        </div>


                        <!-- =================================
                     RIGHT: PRODUCT INFORMATION
                ================================== -->

                        <div class="lp-info">

                            <span class="lp-eyebrow">
                                LIHI SMART BATTERIES
                            </span>


                            <h1 class="lp-title">
                                <?php the_title(); ?>
                            </h1>


                            <?php if ($excerpt): ?>

                                    <div class="lp-short-description">
                                        <?php echo wp_kses_post(wpautop($excerpt)); ?>
                                    </div>

                            <?php endif; ?>


                            <!-- QUICK SPECS -->

                            <?php if ($quick_specs): ?>

                                    <div class="lp-hero-specs">

                                        <?php foreach ($quick_specs as $spec): ?>

                                                <div class="lp-hero-spec">

                                                    <span>
                                                        <?php echo esc_html($spec['label']); ?>
                                                    </span>

                                                    <strong>
                                                        <?php echo esc_html($spec['value']); ?>
                                                    </strong>

                                                </div>

                                        <?php endforeach; ?>

                                    </div>

                            <?php endif; ?>


                            <!-- PRICE -->

                            <?php if ($product->get_price() !== ''): ?>

                                    <div class="lp-price">
                                        <?php echo wp_kses_post($product->get_price_html()); ?>
                                    </div>

                            <?php endif; ?>


                            <!-- STOCK STATUS -->

                            <div class="lp-stock">

                                <?php if ($product->is_in_stock()): ?>

                                        <span class="in-stock">
                                            In Stock
                                        </span>

                                <?php else: ?>

                                        <span class="out-of-stock">
                                            Sold Out
                                        </span>

                                <?php endif; ?>

                            </div>


                            <!-- BUY NOW -->

                            <?php if ($buy_url): ?>

                                    <div class="lp-actions">

                                        <a
                                            class="btn btn-primary lp-btn"
                                            href="<?php echo esc_url($buy_url); ?>"
                                            target="_blank"
                                            rel="noopener noreferrer">

                                            Buy Now
                                            <i data-lucide="external-link"></i>

                                        </a>

                                    </div>

                            <?php endif; ?>


                            <!-- TECHNICAL SPECIFICATIONS -->

                            <?php if ($has_specs): ?>

                                    <section
                                        class="lp-detail-section"
                                        id="lp-technical-specifications">

                                        <h2>Technical Specifications</h2>

                                        <?php if (trim(wp_strip_all_tags($spec_html)) !== ''): ?>

                                                <div class="lp-specification-content">
                                                    <?php
                                                    echo wp_kses_post(
                                                        apply_filters('the_content', $spec_html)
                                                    );
                                                    ?>
                                                </div>

                                        <?php else: ?>

                                                <div class="lp-specs-grid">

                                                    <?php foreach ($legacy_specs as $spec): ?>

                                                            <div class="lp-spec-row">

                                                                <span>
                                                                    <?php echo esc_html($spec[0]); ?>
                                                                </span>

                                                                <strong>
                                                                    <?php echo esc_html($spec[1]); ?>
                                                                </strong>

                                                            </div>

                                                    <?php endforeach; ?>

                                                </div>

                                        <?php endif; ?>

                                    </section>

                            <?php endif; ?>


                            <!-- DESCRIPTION -->

                            <?php if ($has_description): ?>

                                    <section
                                        class="lp-detail-section"
                                        id="lp-description">

                                        <h2>Description</h2>

                                        <div class="lp-description-content">
                                            <?php
                                            echo apply_filters('the_content', $description);
                                            ?>
                                        </div>

                                    </section>

                            <?php endif; ?>


                            <!-- DATASHEET DOWNLOAD -->

                            <?php if ($datasheet_url): ?>

                                    <section
                                        class="lp-detail-section"
                                        id="lp-downloads">

                                        <h2>Downloads</h2>

                                        <a
                                            class="link-btn lp-btn"
                                            href="<?php echo esc_url($datasheet_url); ?>"
                                            download>
                                            <span>Download Product Datasheet</span>
                                            <svg width="15" height="10" viewBox="0 0 13 10" aria-hidden="true">
                                                <path d="M1,5 L11,5"></path>
                                                <polyline points="8 1 12 5 8 9"></polyline>
                                            </svg>
                                        
                                        </a>

                                    </section>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </section>


            <!-- =========================================
         RELATED SECTIONS
    ========================================== -->

            <?php get_template_part('components/related_products'); ?>

            <?php get_template_part('components/related_applications'); ?>

            <?php get_template_part('components/related_industries'); ?>


            <!-- =========================================
         IMAGE LIGHTBOX
    ========================================== -->

            <div class="lp-lightbox" id="lp-lightbox" hidden>

                <button
                    type="button"
                    class="lp-lightbox-close"
                    aria-label="Close image viewer">

                    &times;

                </button>

                <img src="" alt="Enlarged product image">

            </div>

        </main>

        <?php
    endwhile;
endif;

get_footer();
?>