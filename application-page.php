<?php
/*
 * Template Name: Applications
 * Template Post Type: page
 */

get_header();
?>

<main class="applications-page">

    <?php
    get_template_part('components/parallax-hero', null, [
        'eyebrow' => 'APPLICATION AREAS',
        'heading' => 'OUR',
        'highlight' => 'APPLICATIONS',
        'description' => 'Explore how LiHi Smart Batteries power advanced
                        drone and UAV applications across multiple industries.',
        'background' => get_template_directory_uri() . '/images/mapping&surveying.jfif',
    ]);
    ?>

    <section class="section applications-hero">
        <div class="wrap">

            <!-- Applications Grid -->
            <div class="application-grid">

                <?php
                $current_page = max(
                    1,
                    (int) get_query_var('paged'),
                    (int) get_query_var('page')
                );


                $applications = new WP_Query([
                    'post_type' => 'application',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'menu_order title',
                    'order' => 'ASC',
                ]);

                if ($applications->have_posts()):

                    $application_number = 1;

                    while ($applications->have_posts()):
                        $applications->the_post();

                        $application_image = get_field('application_background_image');
                        $application_description = get_field('application_short_description');

                        $application_image_url = '';

                        if (is_array($application_image) && !empty($application_image['url'])) {

                            $application_image_url = $application_image['url'];

                        } elseif (is_numeric($application_image)) {

                            $application_image_url = wp_get_attachment_image_url(
                                $application_image,
                                'large'
                            );

                        } elseif (is_string($application_image)) {

                            $application_image_url = $application_image;
                        }
                        ?>


                        <!-- APPLICATION CARD -->

                        <a href="<?php echo esc_url(get_permalink()); ?>" class="application-card">

                            <!-- APPLICATION IMAGE -->

                            <div class="application-card-image">

                                <?php if ($application_image_url): ?>

                                    <img src="<?php echo esc_url($application_image_url); ?>"
                                        alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" decoding="async">

                                <?php else: ?>

                                    <div class="application-card-placeholder">
                                        NO IMAGE AVAILABLE
                                    </div>

                                <?php endif; ?>

                            </div>

                            <!-- APPLICATION BODY -->

                            <div class="application-card-body">

                                <h3>
                                    <?php the_title(); ?>
                                </h3>

                                <?php if ($application_description): ?>

                                    <p>
                                        <?php echo esc_html($application_description); ?>
                                    </p>

                                <?php endif; ?>

                                <!-- EXPLORE LINK -->

                                <div class="application-card-footer">

                                    <span class="application-card-link">
                                        Explore Application

                                        <span class="application-card-arrow">→</span>
                                    </span>

                                </div>

                            </div>

                        </a>


                        <?php
                        $application_number++;

                    endwhile;

                    wp_reset_postdata();

                else:
                    ?>

                    <p class="applications-empty">
                        No applications available yet.
                    </p>

                <?php endif; ?>

            </div>

        </div>
    </section>

</main>

<?php get_template_part('components/cta'); ?>

<?php get_footer(); ?>