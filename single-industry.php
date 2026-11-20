<?php
/**
 * Single Industry Template
 * LiHi Smart Batteries
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$industry_id = get_the_ID();
$industry_title = get_the_title($industry_id);

/* =========================================================
   EXISTING ACF FIELDS
========================================================= */

$industry_description = get_field('description', $industry_id);
$industry_icon = get_field('industry_icon', $industry_id);
$industry_icon_image = get_field('industry_icon_image', $industry_id);

$background_image = get_field('industry_background_image', $industry_id);

$industry_short_description = get_field(
    'industry_short_description',
    $industry_id
);

$features_heading = get_field(
    'features_heading',
    $industry_id
);


/* =========================================================
   NEW ACF FIELDS - INDUSTRY REQUIREMENTS
========================================================= */

$requirements_eyebrow = get_field(
    'industry_requirements_eyebrow',
    $industry_id
);

$requirements_heading = get_field(
    'industry_requirements_heading',
    $industry_id
);

$requirements_description = get_field(
    'industry_requirements_description',
    $industry_id
);

$requirements_image = get_field(
    'industry_requirements_image',
    $industry_id
);

/* =========================================================
   IMAGE URL HELPER
   Supports ACF Image Array, ID, or URL
========================================================= */

if (!function_exists('lihi_industry_image_url')) {

    function lihi_industry_image_url($image, $size = 'large')
    {

        if (is_array($image)) {

            if (!empty($image['ID'])) {
                return wp_get_attachment_image_url(
                    (int) $image['ID'],
                    $size
                ) ?: '';
            }

            return !empty($image['url'])
                ? $image['url']
                : '';
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
    }
}

/* =========================================================
   IMAGE URLS
========================================================= */

$background_image_url = lihi_industry_image_url(
    $background_image,
    'full'
);

$industry_icon_image_url = lihi_industry_image_url(
    $industry_icon_image,
    'thumbnail'
);


$requirements_image_url = lihi_industry_image_url(
    $requirements_image,
    'large'
);

/* =========================================================
   KEY FEATURES
========================================================= */

$industry_benefits = [];

for ($i = 1; $i <= 3; $i++) {

    $icon = get_field("feature_{$i}_icon", $industry_id);

    $title = get_field("feature_{$i}_title", $industry_id);

    $description = get_field(
        "feature_{$i}_description",
        $industry_id
    );

    if (!$title && !$description) {
        continue;
    }

    $industry_benefits[] = [
        'icon' => $icon ?: 'check-circle',
        'title' => $title,
        'description' => $description,
    ];
}

/* =========================================================
   RELATED FIELDS
========================================================= */

$related_products = get_field(
    'related_products',
    $industry_id
);

$related_applications = get_field(
    'related_applications',
    $industry_id
);

/* =========================================================
   SECTION VISIBILITY
========================================================= */

$show_requirements = (
    $requirements_heading ||
    $requirements_description ||
    $requirements_image_url
);

?>

<main class="industry-page">

    <!-- =====================================================
         INDUSTRY HERO
    ====================================================== -->

    <?php
    get_template_part('components/parallax-hero', null, [
        'eyebrow' => 'INDUSTRIES WE SERVE',
        'heading' => $industry_title,
        'highlight' => '',
        'description' => $industry_short_description,
        'background' => $background_image_url,
    ]);
    ?>


    <!-- =====================================================
         INDUSTRY OVERVIEW
    ====================================================== -->

    <section class="industry-intro" id="industry-overview">

        <div class="wrap">

            <div class="industry-intro-grid">

                <div class="industry-intro-heading">

                    <span class="eyebrow">
                        INDUSTRY SOLUTIONS
                    </span>

                    <h2 class="industry-intro-title">
                        Powering
                        <span><?php echo esc_html($industry_title); ?></span>
                        Innovation
                    </h2>

                    <div class="industry-divider"></div>

                </div>

                <div class="industry-intro-content">

                    <?php if ($industry_description): ?>

                        <div class="industry-intro-text">

                            <?php
                            echo wp_kses_post(
                                apply_filters(
                                    'the_content',
                                    $industry_description
                                )
                            );
                            ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


        </div>

    </section>


    <!-- =====================================================
         INDUSTRY REQUIREMENTS
    ====================================================== -->

    <?php if ($show_requirements): ?>

        <section class="industry-requirements">

            <div class="wrap">

                <div class="industry-requirements-grid">

                    <?php if ($requirements_image_url): ?>

                        <div class="industry-requirements-image">

                            <img src="<?php echo esc_url($requirements_image_url); ?>"
                                alt="<?php echo esc_attr($industry_title . ' industry requirements'); ?>" loading="lazy"
                                decoding="async">

                        </div>

                    <?php endif; ?>


                    <div class="industry-requirements-content">

                        <?php if ($requirements_eyebrow): ?>

                            <span class="eyebrow">
                                <?php echo esc_html($requirements_eyebrow); ?>
                            </span>

                        <?php endif; ?>


                        <?php if ($requirements_heading): ?>

                            <h2>
                                <?php echo esc_html($requirements_heading); ?>
                            </h2>

                            <div class="industry-divider"></div>

                        <?php endif; ?>


                        <?php if ($requirements_description): ?>

                            <div class="industry-requirements-text">

                                <?php
                                echo wp_kses_post(
                                    apply_filters(
                                        'the_content',
                                        $requirements_description
                                    )
                                );
                                ?>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </section>

    <?php endif; ?>


    <!-- =====================================================
         INDUSTRY KEY FEATURES / WHY LIHI
    ====================================================== -->

    <?php if (!empty($industry_benefits)): ?>

        <section class="industry-benefits">

            <div class="wrap">

                <div class="industry-benefits-heading">

                    <span class="eyebrow">
                        WHY LIHI
                    </span>

                    <h2>
                        <?php
                        echo esc_html(
                            $features_heading
                            ?: 'Power Built for Performance'
                        );
                        ?>
                    </h2>

                    <p>
                        Supporting industry-specific applications with
                        reliable and efficient energy solutions.
                    </p>

                </div>


                <div class="industry-benefits-grid">

                    <?php foreach ($industry_benefits as $benefit): ?>

                        <div class="industry-benefit-card">

                            <div class="industry-benefit-icon">

                                <i data-lucide="<?php echo esc_attr($benefit['icon']); ?>"></i>

                            </div>

                            <?php if ($benefit['title']): ?>

                                <h3>
                                    <?php echo esc_html($benefit['title']); ?>
                                </h3>

                            <?php endif; ?>


                            <?php if ($benefit['description']): ?>

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


    <!-- =====================================================
         RELATED PRODUCTS
    ====================================================== -->

    <?php

    if (!empty($related_products)) {
        get_template_part('components/related_products');
    }

    ?>


    <!-- =====================================================
         RELATED APPLICATIONS
    ====================================================== -->

    <?php

    if (!empty($related_applications)) {
        get_template_part('components/related_applications');
    }

    ?>


    <!-- =====================================================
         RELATED INDUSTRIES
    ====================================================== -->

    <?php
    get_template_part('components/related_industries');
    ?>

</main>

<?php get_footer(); ?>