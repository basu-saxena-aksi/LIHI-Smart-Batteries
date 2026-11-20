<?php
/*
 * Template Name: Industries
 * Template Post Type: page
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="industries-page">

    <!-- =====================================================
         INDUSTRIES LIST
    ====================================================== -->

    <section class="section industries-list">
        <?php
        get_template_part('components/parallax-hero', null, [
            'eyebrow' => 'OUR EXPERTISE',
            'heading' => 'INDUSTRIES WE',
            'highlight' => 'SERVE',
            'description' => 'Discover how LiHi Smart Batteries delivers reliable
                        power solutions across agriculture, defense, surveying,
                        and aerial logistics industries.',
            'background' => get_template_directory_uri() . '/images/solar-wind.png',
        ]);
        ?>

        <div class="wrap">

            <div class="section-head industries-list-head">

                <div>

                    <span class="eyebrow">

                    </span>

                    <h2>

                        <span class="g"></span>
                    </h2>

                    <p class="industries-intro">

                    </p>

                </div>

            </div>




            <!-- INDUSTRIES GRID -->

            <div class="industries-grid">

                <?php

                /* ============================================
                   FETCH PUBLISHED INDUSTRIES
                ============================================= */

                $industries = new WP_Query([
                    'post_type' => 'industry',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'menu_order title',
                    'order' => 'ASC',
                    'no_found_rows' => true,
                ]);

                if ($industries->have_posts()):

                    $industry_number = 1;

                    while ($industries->have_posts()):

                        $industries->the_post();

                        $industry_id = get_the_ID();

                        /* ====================================
                           INDUSTRY DATA
                        ===================================== */

                        $industry_title = get_the_title(
                            $industry_id
                        );

                        $industry_url = get_permalink(
                            $industry_id
                        );

                        /* ====================================
                           SHORT DESCRIPTION
                        ===================================== */

                        $industry_description = function_exists('get_field')
                            ? get_field(
                                'industry_short_description',
                                $industry_id
                            )
                            : '';

                        // Fallback to WordPress excerpt.
                        if (!$industry_description) {

                            $industry_description = get_the_excerpt(
                                $industry_id
                            );
                        }

                        $industry_description = wp_strip_all_tags(
                            (string) $industry_description
                        );

                        /* ====================================
                           INDUSTRY ICON
                        ===================================== */

                        $industry_icon = function_exists('get_field')
                            ? get_field(
                                'industry_icon',
                                $industry_id
                            )
                            : '';

                        /* ====================================
                           FEATURED IMAGE

                           Uses WordPress Featured Image,
                           NOT the ACF background image.
                        ===================================== */

                        $industry_image = get_the_post_thumbnail_url(
                            $industry_id,
                            'large'
                        );

                        ?>


                        <!-- INDUSTRY CARD -->

                        <a href="<?php echo esc_url($industry_url); ?>" class="industry-card">

                            <!-- INDUSTRY IMAGE -->

                            <div class="industry-card-image">

                                <?php if ($industry_image): ?>

                                    <img src="<?php echo esc_url($industry_image); ?>"
                                        alt="<?php echo esc_attr($industry_title); ?>" loading="lazy" decoding="async">

                                <?php else: ?>

                                    <div class="industry-card-placeholder">
                                        NO IMAGE AVAILABLE
                                    </div>

                                <?php endif; ?>

                            </div>

                            <!-- INDUSTRY BODY -->

                            <div class="industry-card-body">

                                <h3>
                                    <?php echo esc_html($industry_title); ?>
                                </h3>

                                <?php if ($industry_description): ?>

                                    <p>
                                        <?php echo esc_html($industry_description); ?>
                                    </p>

                                <?php endif; ?>

                                <!-- EXPLORE LINK -->

                                <div class="industry-card-footer">

                                    <span class="industry-card-link">
                                        Explore Industry

                                        <span class="industry-card-arrow">→</span>
                                    </span>

                                </div>

                            </div>

                        </a>



                        <?php

                        $industry_number++;

                    endwhile;

                else:

                    ?>

                    <!-- NO INDUSTRIES -->

                    <div class="industries-empty">

                        <p>
                            No industries found.
                        </p>

                    </div>

                    <?php

                endif;

                wp_reset_postdata();

                ?>

            </div>

        </div>

    </section>

</main>

<?php get_footer(); ?>