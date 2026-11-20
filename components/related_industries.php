
<?php
/**
 * Related Industries Component
 *
 * CPT: industry
 * ACF Field: related_industries
 *
 * Supports ACF Post Object / Relationship fields
 * returning either Post Objects or Post IDs.
 */

if (!function_exists('get_field')) {
    return;
}

$current_page_id = get_queried_object_id();

if (!$current_page_id) {
    return;
}

/* =========================================================
   GET MAPPED INDUSTRIES
========================================================= */

$related_industries = get_field(
    'related_industries',
    $current_page_id
);

if (empty($related_industries)) {
    return;
}

/* Normalize single and multiple selections */

if (!is_array($related_industries)) {
    $related_industries = [$related_industries];
}

$industry_ids = [];

foreach ($related_industries as $industry) {

    $industry_id = is_object($industry)
        ? (int) $industry->ID
        : (int) $industry;

    if (
        $industry_id &&
        get_post_type($industry_id) === 'industry'
    ) {
        $industry_ids[] = $industry_id;
    }
}

$industry_ids = array_values(array_unique($industry_ids));

if (empty($industry_ids)) {
    return;
}

/* =========================================================
   FETCH INDUSTRIES
========================================================= */

$industries = get_posts([
    'post_type'      => 'industry',
    'post_status'    => 'publish',
    'post__in'       => $industry_ids,
    'orderby'        => 'post__in',
    'posts_per_page' => -1,
]);

if (empty($industries)) {
    return;
}
?>

<section class="section related-industries">

    <div class="wrap">

        <div class="section-head">

            <div>
                <span class="eyebrow">INDUSTRIES WE SERVE</span>

                <h2>
                    RELATED
                    <span class="g">INDUSTRIES</span>
                </h2>
            </div>

        </div>

        <div class="related-industries-grid">

            <?php foreach ($industries as $industry) :

                $industry_id = $industry->ID;

                $industry_title = get_the_title($industry_id);
                $industry_url   = get_permalink($industry_id);

                /* ACF industry background image */
                $image = get_field(
                    'industry_background_image',
                    $industry_id
                );

                $image_url = '';

                if (is_array($image)) {

                    $image_url = $image['url'] ?? '';

                } elseif (is_numeric($image)) {

                    $image_url = wp_get_attachment_image_url(
                        (int) $image,
                        'large'
                    );

                } elseif (is_string($image)) {

                    $image_url = $image;
                }

                /* Fallback to existing ACF image */
                if (!$image_url) {

                    $fallback_image = get_field(
                        'industry_image',
                        $industry_id
                    );

                    if (is_array($fallback_image)) {

                        $image_url = $fallback_image['url'] ?? '';

                    } elseif (is_numeric($fallback_image)) {

                        $image_url = wp_get_attachment_image_url(
                            (int) $fallback_image,
                            'large'
                        );

                    } elseif (is_string($fallback_image)) {

                        $image_url = $fallback_image;
                    }
                }

                /* Fallback to featured image */
                if (!$image_url) {

                    $image_url = get_the_post_thumbnail_url(
                        $industry_id,
                        'large'
                    );
                }

                /* Industry description */
                $description = get_field(
                    'industry_description',
                    $industry_id
                );

                if (!$description) {
                    $description = get_the_excerpt($industry_id);
                }

                $description = wp_trim_words(
                    wp_strip_all_tags((string) $description),
                    20,
                    '...'
                );
            ?>

                <article class="industry-card">

                    <a
                        href="<?php echo esc_url($industry_url); ?>"
                        class="industry-card-image"
                        aria-label="<?php echo esc_attr($industry_title); ?>"
                    >

                        <?php if ($image_url) : ?>

                            <img
                                src="<?php echo esc_url($image_url); ?>"
                                alt="<?php echo esc_attr($industry_title); ?>"
                                loading="lazy"
                            >

                        <?php endif; ?>

                    </a>

                    <div class="industry-card-body">

                        <h4>
                            <?php echo esc_html($industry_title); ?>
                        </h4>

                        <?php if ($description) : ?>

                            <p>
                                <?php echo esc_html($description); ?>
                            </p>

                        <?php endif; ?>

                        <a
                            href="<?php echo esc_url($industry_url); ?>"
                            class="industry-card-link"
                        >
                            Explore Industry
                            <span aria-hidden="true">→</span>
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>
