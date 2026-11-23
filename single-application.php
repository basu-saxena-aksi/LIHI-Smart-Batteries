<?php
/**
 * Single Application Template
 * LiHi Smart Batteries
 */

defined('ABSPATH') || exit;

get_header();

$application_id = get_queried_object_id();
$application_title = get_the_title($application_id);


/* =========================================================
   APPLICATION ACF FIELDS
========================================================= */

$short_description = get_field(
    'application_short_description',
    $application_id
);

$application_description = get_field(
    'application_description',
    $application_id
);

$application_icon = get_field(
    'application_icon',
    $application_id
);

$hero_image = get_field(
    'application_hero_image',
    $application_id
);

$features_heading = get_field(
    'features_heading',
    $application_id
);


/* =========================================================
   REQUIREMENTS FIELDS
========================================================= */

$requirements_eyebrow = get_field(
    'application_requirements_eyebrow',
    $application_id
);

$requirements_heading = get_field(
    'application_requirements_heading',
    $application_id
);

$requirements_description = get_field(
    'application_requirements_description',
    $application_id
);

$requirements_image = get_field(
    'application_requirements_image',
    $application_id
);


/* =========================================================
   RELATED CONTENT
========================================================= */

$related_products = get_field(
    'related_products',
    $application_id
);

$related_applications = get_field(
    'related_applications',
    $application_id
);

$related_industries = get_field(
    'related_industries',
    $application_id
);


/* =========================================================
   IMAGE URL HELPER
   Supports ACF Image Array, ID, or URL
========================================================= */

$get_image_url = function ($image, $size = 'large') {

    if (is_array($image)) {
        return $image['url'] ?? '';
    }

    if (is_numeric($image)) {
        return wp_get_attachment_image_url(
            (int) $image,
            $size
        ) ?: '';
    }

    if (is_string($image)) {
        return $image;
    }

    return '';
};


/* =========================================================
   APPLICATION HERO IMAGE
========================================================= */

$hero_image_url = $get_image_url($hero_image);

if (!$hero_image_url) {

    $hero_image_url = get_the_post_thumbnail_url(
        $application_id,
        'large'
    );
}


/* =========================================================
   REQUIREMENTS IMAGE
========================================================= */

$requirements_image_url = $get_image_url(
    $requirements_image,
    'large'
);


/* =========================================================
   APPLICATION BENEFITS / KEY FEATURES
========================================================= */

$application_benefits = [];

for ($i = 1; $i <= 3; $i++) {

    $feature_icon = get_field(
        "feature_{$i}_icon",
        $application_id
    );

    $feature_title = get_field(
        "feature_{$i}_title",
        $application_id
    );

    $feature_description = get_field(
        "feature_{$i}_description",
        $application_id
    );

    if (empty($feature_title) && empty($feature_description)) {
        continue;
    }

    $application_benefits[] = [
        'icon' => $feature_icon ?: 'battery-charging',
        'title' => $feature_title,
        'description' => $feature_description
    ];
}


/* =========================================================
   WORDPRESS CONTENT FALLBACK
========================================================= */

$application_content = get_post_field(
    'post_content',
    $application_id
);

$overview_content = $application_description
    ?: $application_content;


/* =========================================================
   SECTION VISIBILITY
========================================================= */

$show_requirements = (
    $requirements_heading ||
    $requirements_description ||
    $requirements_image_url
);

?>

<main class="application-page">


    <!-- =====================================
         APPLICATION HERO
    ====================================== -->

    <?php
    get_template_part('components/parallax-hero', null, [
        'eyebrow' => 'APPLICATION',
        'heading' => $application_title,
        'description' => $short_description,
        'background' => $hero_image_url ?: 'about-us-hero-bg.jpg',
    ]);
    ?>


    <!-- =====================================
         APPLICATION OVERVIEW
    ====================================== -->

    <?php if ($overview_content): ?>

        <section class="application-overview" id="application-overview">

            <div class="wrap">

                <div class="application-overview-heading">

                    <span class="application-section-eyebrow">
                        APPLICATION SOLUTIONS
                    </span>

                    <h2 class="application-overview-title">

                        Powering

                        <span>
                            <?php echo esc_html($application_title); ?>
                        </span>

                        Innovation

                    </h2>

                    <div class="application-overview-divider"></div>

                </div>


                <div class="application-overview-content">

                    <?php
                    echo apply_filters(
                        'the_content',
                        $overview_content
                    );
                    ?>

                </div>

            </div>

        </section>

    <?php endif; ?>


    <!-- =====================================
         APPLICATION REQUIREMENTS
    ====================================== -->

    <?php if ($show_requirements): ?>

        <section class="application-requirements" id="application-requirements">

            <div class="wrap">

                <div class="application-requirements-grid">


                    <!-- REQUIREMENTS IMAGE -->

                    <?php if ($requirements_image_url): ?>

                        <div class="application-requirements-image">

                            <img src="<?php echo esc_url($requirements_image_url); ?>" alt="<?php echo esc_attr(
                                   $requirements_heading ?: $application_title
                               ); ?>" loading="lazy">

                        </div>

                    <?php endif; ?>


                    <!-- REQUIREMENTS CONTENT -->

                    <div class="application-requirements-content">

                        <?php if ($requirements_eyebrow): ?>

                            <span class="eyebrow">
                                <?php echo esc_html($requirements_eyebrow); ?>
                            </span>

                        <?php endif; ?>


                        <?php if ($requirements_heading): ?>

                            <h2>
                                <?php echo esc_html($requirements_heading); ?>
                            </h2>

                        <?php endif; ?>


                        <div class="application-requirements-divider"></div>


                        <?php if ($requirements_description): ?>

                            <div class="application-requirements-text">

                                <?php
                                echo apply_filters(
                                    'the_content',
                                    $requirements_description
                                );
                                ?>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </section>

    <?php endif; ?>


    <!-- =====================================
         APPLICATION BENEFITS / WHY LIHI
    ====================================== -->

    <?php if (!empty($application_benefits)): ?>

        <section class="application-benefits">

            <div class="wrap">

                <div class="application-benefits-heading">

                    <span class="application-section-eyebrow">
                        WHY LIHI
                    </span>

                    <h2>
                        <?php echo esc_html(
                            $features_heading
                            ?: 'Power Built for Performance'
                        ); ?>
                    </h2>

                    <p>
                        Supporting specialized applications with reliable
                        and efficient battery solutions.
                    </p>

                </div>


                <div class="application-benefits-grid">

                    <?php foreach ($application_benefits as $benefit): ?>

                        <div class="application-benefit-card">

                            <?php if (!empty($benefit['icon'])): ?>

                                <div class="application-benefit-icon">

                                    <i data-lucide="<?php echo esc_attr($benefit['icon']); ?>"></i>

                                </div>

                            <?php endif; ?>


                            <?php if (!empty($benefit['title'])): ?>

                                <h3>
                                    <?php echo esc_html($benefit['title']); ?>
                                </h3>

                            <?php endif; ?>


                            <?php if (!empty($benefit['description'])): ?>

                                <p>
                                    <?php echo esc_html($benefit['description']); ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>

    <?php endif; ?>


    <!-- =====================================
         RELATED PRODUCTS
    ====================================== -->

    <?php if (!empty($related_products)): ?>

        <?php
        get_template_part('components/related_products');
        ?>

    <?php endif; ?>

    <!-- =====================================
         RELATED INDUSTRIES
    ====================================== -->

    <?php if (!empty($related_industries)): ?>

        <?php
        get_template_part('components/related_industries');
        ?>

    <?php endif; ?>


</main>

<?php get_footer(); ?>