
<?php
/**
 * Related Applications Carousel
 *
 * Displays applications selected through the
 * ACF related_applications Relationship field.
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_post_id = get_queried_object_id();

$selected_applications = function_exists('get_field')
    ? get_field('related_applications', $current_post_id)
    : [];

if (empty($selected_applications) || !is_array($selected_applications)) {
    return;
}

/* =========================================================
   NORMALIZE SELECTED APPLICATION IDS
========================================================= */

$application_ids = [];

foreach ($selected_applications as $application) {

    $application_id = is_object($application)
        ? (int) $application->ID
        : (int) $application;

    if ($application_id > 0) {
        $application_ids[] = $application_id;
    }
}

$application_ids = array_values(
    array_unique($application_ids)
);

if (empty($application_ids)) {
    return;
}

/* =========================================================
   FETCH SELECTED APPLICATIONS
========================================================= */

$related_applications = new WP_Query([
    'post_type'      => 'application',
    'post_status'    => 'publish',
    'posts_per_page' => count($application_ids),
    'post__in'       => $application_ids,
    'orderby'        => 'post__in',
    'post__not_in' => is_singular('application')
    ? [$current_post_id]
    : [],
]);

if (!$related_applications->have_posts()) {
    return;
}
?>

<section class="section related-applications">

    <div class="wrap">

        <div class="section-head">

            <div>
                <span class="eyebrow">
                    APPLICATION AREAS
                </span>

                <h2>
                    RELATED
                    <span class="g">APPLICATIONS</span>
                </h2>
            </div>

            <div class="related-applications-controls">

                <button
                    type="button"
                    class="related-applications-btn related-applications-prev"
                    aria-label="Previous applications"
                >
                    ←
                </button>

                <button
                    type="button"
                    class="related-applications-btn related-applications-next"
                    aria-label="Next applications"
                >
                    →
                </button>

            </div>

        </div>

        <div class="related-applications-carousel">

            <div class="related-applications-track">

                <?php while ($related_applications->have_posts()) : ?>

                    <?php
                    $related_applications->the_post();

                    $application_id = get_the_ID();
                    $application_title = get_the_title();
                    $application_url = get_permalink();


                    $hero_image = get_field(
                        'application_hero_image',
                        $application_id
                    );

                    $application_image = '';

                    if (is_array($hero_image)) {

                        if (!empty($hero_image['url'])) {
                            $application_image = $hero_image['url'];
                        }

                    } elseif (is_numeric($hero_image)) {

                        $application_image = wp_get_attachment_image_url(
                            (int) $hero_image,
                            'large'
                        );

                    } elseif (is_string($hero_image)) {

                        $application_image = $hero_image;
                    }

                    // Fallback to WordPress Featured Image.
                    if (!$application_image) {

                        $application_image = get_the_post_thumbnail_url(
                            $application_id,
                            'large'
                        );
                    }

                    $application_description = get_field(
                        'application_short_description',
                        $application_id
                    );

                    if (!$application_description) {
                        $application_description = get_the_excerpt($application_id);
                    }

                    if (!$application_description) {
                        $application_description = get_post_field(
                            'post_content',
                            $application_id
                        );
                    }

                    $application_description = wp_trim_words(
                        wp_strip_all_tags((string) $application_description),
                        20,
                        '…'
                    );

                    $application_labels = [
                        'agricultural-spraying'       => 'AGRICULTURE',
                        'mapping-surveying'           => 'MAPPING',
                        'surveillance-reconnaissance' => 'SURVEILLANCE',
                        'delivery-drones'            => 'LOGISTICS',
                        'defense-uavs'               => 'DEFENSE',
                    ];

                    $application_slug = get_post_field(
                        'post_name',
                        $application_id
                    );

                    $application_label = $application_labels[$application_slug]
                        ?? 'APPLICATION';
                    
                    ?>

                    <article class="related-application-card">

                        <a
                            href="<?php echo esc_url($application_url); ?>"
                            class="related-application-card-image"
                        >

                            <?php if ($application_image) : ?>

                                <img
                                    src="<?php echo esc_url($application_image); ?>"
                                    alt="<?php echo esc_attr($application_title); ?>"
                                    loading="lazy"
                                >

                            <?php endif; ?>

                        </a>

                        <div class="related-application-card-body">

                            <span class="related-application-card-label">
                                <?php echo esc_html(
                                    strtoupper($application_label)
                                ); ?>
                            </span>

                            <h4>
                                <?php echo esc_html($application_title); ?>
                            </h4>

                            <?php if ($application_description) : ?>

                                <p>
                                    <?php echo esc_html(
                                        $application_description
                                    ); ?>
                                </p>

                            <?php endif; ?>

                            <a
                                href="<?php echo esc_url($application_url); ?>"
                                class="related-application-card-link"
                            >
                                Explore Application
                                <span>→</span>
                            </a>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

        </div>

    </div>

</section>

<?php wp_reset_postdata(); ?>
