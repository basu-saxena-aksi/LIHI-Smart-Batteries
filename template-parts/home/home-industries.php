
<?php
/**
 * LiHi Homepage Industries
 *
 * Dynamic industry hover rows.
 *
 * Include:
 * get_template_part('components/home-industries');
 */

if (!defined('ABSPATH')) {
    exit;
}

/* =========================================================
   FETCH INDUSTRIES
========================================================= */

$lihi_industries = new WP_Query([
    'post_type'      => 'industry',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
    'no_found_rows'  => true,
]);

if (!$lihi_industries->have_posts()) {
    return;
}

$lihi_ind_id = wp_unique_id('lihi-industries-');
?>

<section
    class="lihi-industries"
    aria-labelledby="<?php echo esc_attr($lihi_ind_id); ?>-heading"
>

    <div class="lihi-industries__inner wrap">

        <!-- SECTION HEADING -->

        <div class="lihi-industries__head">

            <h2 id="<?php echo esc_attr($lihi_ind_id); ?>-heading">
                OUR <span>INDUSTRIES</span>
            </h2>

            <a
                class="link-btn"
                href="<?php echo esc_url(home_url('/industries/')); ?>"
            >
                <span>View All Industries</span>

                <svg
                    width="15"
                    height="10"
                    viewBox="0 0 13 10"
                    aria-hidden="true"
                >
                    <path d="M1,5 L11,5"></path>
                    <polyline points="8 1 12 5 8 9"></polyline>
                </svg>

            </a>

        </div>

        <!-- INDUSTRIES LIST -->

        <ul class="lihi-industries__list">

            <?php
            $lihi_i = 1;

            while ($lihi_industries->have_posts()) :

                $lihi_industries->the_post();

                $industry_id = get_the_ID();

                /* =========================================
                   INDUSTRY TITLE & LINK
                ========================================= */

                $industry_title = get_the_title($industry_id);
                $industry_url   = get_permalink($industry_id);

                /* =========================================
                   SHORT DESCRIPTION
                ========================================= */

                $industry_tag = function_exists('get_field')
                    ? get_field(
                        'industry_short_description',
                        $industry_id
                    )
                    : '';

                if (!$industry_tag) {
                    $industry_tag = get_the_excerpt($industry_id);
                }

                $industry_tag = wp_strip_all_tags(
                    (string) $industry_tag
                );

                /* =========================================
                   BACKGROUND IMAGE

                ================== */

                $background_image = function_exists('get_field')
                    ? get_field(
                        'industry_background_image',
                        $industry_id
                    )
                    : null;

                $industry_image_url = '';

                // ACF Image Array.
                if (is_array($background_image)) {

                    if (!empty($background_image['ID'])) {

                        $industry_image_url = wp_get_attachment_image_url(
                            (int) $background_image['ID'],
                            'full'
                        );

                    } elseif (!empty($background_image['url'])) {

                        $industry_image_url = $background_image['url'];
                    }

      
                } elseif (is_numeric($background_image)) {

                    $industry_image_url = wp_get_attachment_image_url(
                        (int) $background_image,
                        'full'
                    );

         
                } elseif (is_string($background_image)) {

                    $industry_image_url = $background_image;
                }

                if (!$industry_image_url) {

                    $industry_image_url = get_the_post_thumbnail_url(
                        $industry_id,
                        'full'
                    );
                }

                $industry_style = '';

                if ($industry_image_url) {

                    $industry_style = '--img:url("' .
                        esc_url($industry_image_url) .
                        '");';
                }

                ?>

                <li>

                    <a
                        class="lihi-industries__row"
                        href="<?php echo esc_url($industry_url); ?>"
                        <?php if ($industry_style) : ?>
                            style="<?php echo esc_attr($industry_style); ?>"
                        <?php endif; ?>
                    >

                        <!-- NUMBER -->

                        <span
                            class="lihi-industries__num"
                            aria-hidden="true"
                        >
                            <?php echo esc_html($lihi_i); ?>
                        </span>

                        <!-- SHORT DESCRIPTION -->

                        <span class="lihi-industries__tag">
                            <?php echo esc_html($industry_tag); ?>
                        </span>

                        <!-- INDUSTRY TITLE -->

                        <span class="lihi-industries__title">
                            <?php echo esc_html($industry_title); ?>
                        </span>

                        <!-- EXPLORE BUTTON -->

                        <span class="btn industries-btn">

                            <span>Explore</span>

                            <svg
                                width="15"
                                height="10"
                                viewBox="0 0 13 10"
                                aria-hidden="true"
                            >
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>

                        </span>

                    </a>

                </li>

                <?php

                $lihi_i++;

            endwhile;

            wp_reset_postdata();
            ?>

        </ul>

    </div>

</section>
