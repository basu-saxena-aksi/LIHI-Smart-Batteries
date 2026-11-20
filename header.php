<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-white text-slate-800 antialiased selection:bg-blue'); ?>>
    <?php wp_body_open(); ?>


    <header>
        <div class="nav">
            <div class="logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url(
                        content_url('/uploads/2026/10/LiHi-Smart-Batteries-Logo.png')
                    ); ?>" alt="LiHi Smart Batteries" class="logo-image">
                </a>
            </div>


            <nav class="nav-links">

                <!-- =========================================
                    ABOUT US - REGULAR DROPDOWN
                ========================================== -->

                <div class="nav-links-container has-dropdown">

                    <a href="<?php echo esc_url(home_url('/about-us/')); ?>">
                        About Us
                    </a>

                </div>

                <div class="nav-links-container has-dropdown">

                    <a href="<?php echo esc_url(home_url('/research-development/')); ?>">
                        R&D
                    </a>

                </div>


                <!-- =========================================
                    PRODUCTS - STACKED IMAGE DROPDOWN
                ========================================== -->

                <div class="nav-links-container has-dropdown nav-mega-item">

                    <a href="<?php echo esc_url(home_url('/product-category/battery/')); ?>" class="products-nav-link">
                        Products

                        <svg viewBox="0 0 10 6" fill="none" aria-hidden="true">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </a>

                    <div class="nav-mega-menu nav-product-dropdown">

                        <!-- Batteries -->

                        <a href="<?php echo esc_url(home_url('/product-category/battery/')); ?>"
                            class="nav-product-card">

                            <img src="<?php echo esc_url(content_url('/uploads/2026/10/nav-batteries.png')); ?>"
                                alt="LiHi smart batteries" loading="lazy">

                            <span class="nav-product-glass"></span>

                            <span class="nav-product-card-content">

                                <span class="nav-product-card-title">
                                    Batteries
                                </span>

                            </span>

                        </a>


                        <!-- Chargers -->

                        <a href="<?php echo esc_url(home_url('/product-category/charger/')); ?>"
                            class="nav-product-card">

                            <img src="<?php echo esc_url(content_url('/uploads/2026/10/nav-chargers.png')); ?>"
                                alt="LiHi battery chargers" loading="lazy">

                            <span class="nav-product-glass"></span>

                            <span class="nav-product-card-content">

                                <span class="nav-product-card-title">
                                    Chargers
                                </span>

                            </span>

                        </a>

                    </div>

                </div>



                <!-- =========================================
                    INDUSTRIES - TWO-COLUMN IMAGE DROPDOWN
                ========================================== -->

                <div class="nav-links-container has-dropdown nav-mega-item">

                    <a href="<?php echo esc_url(home_url('/industries/')); ?>">
                        Industries

                        <svg viewBox="0 0 10 6" fill="none" aria-hidden="true">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </a>

                    <div class="nav-mega-menu nav-image-grid-dropdown">

                        <div class="nav-image-grid">

                            <?php

                            $nav_industries = new WP_Query([
                                'post_type' => 'industry',
                                'posts_per_page' => 4,
                                'post_status' => 'publish',
                                'orderby' => 'menu_order title',
                                'order' => 'ASC',
                                'no_found_rows' => true,
                            ]);

                            if ($nav_industries->have_posts()):

                                while ($nav_industries->have_posts()):

                                    $nav_industries->the_post();

                                    $industry_id = get_the_ID();


                                    $industry_title = get_the_title($industry_id);

                                    $industry_url = get_permalink($industry_id);


                                    $nav_image_url = get_the_post_thumbnail_url(
                                        $industry_id,
                                        'medium_large'
                                    );
                                    ?>

                                    <a href="<?php echo esc_url($industry_url); ?>" class="nav-product-card">

                                        <?php if ($nav_image_url): ?>

                                            <img src="<?php echo esc_url($nav_image_url); ?>"
                                                alt="<?php echo esc_attr($industry_title); ?>" loading="lazy" decoding="async">

                                        <?php else: ?>

                                            <span class="nav-mega-image-placeholder"></span>

                                        <?php endif; ?>

                                        <span class="nav-product-glass"></span>

                                        <span class="nav-product-card-content">

                                            <span class="nav-product-card-title">
                                                <?php echo esc_html($industry_title); ?>
                                            </span>

                                        </span>

                                    </a>

                                    <?php

                                endwhile;

                                wp_reset_postdata();

                            else:
                                ?>
                                <p class="nav-mega-empty">
                                    Industries will be available here soon.
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>




                <!-- =========================================
                    APPLICATIONS - TWO-COLUMN IMAGE DROPDOWN
                ========================================== -->

                <div class="nav-links-container has-dropdown nav-mega-item">

                    <a href="<?php echo esc_url(home_url('/applications/')); ?>">
                        Applications

                        <svg viewBox="0 0 10 6" fill="none" aria-hidden="true">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </a>

                    <div class="nav-mega-menu nav-image-grid-dropdown">

                        <div class="nav-image-grid">

                            <?php

                            /* =========================================
                            FETCH APPLICATIONS
                            ========================================= */

                            $nav_applications = new WP_Query([
                                'post_type' => 'application',
                                'posts_per_page' => 4,
                                'post_status' => 'publish',
                                'orderby' => 'menu_order title',
                                'order' => 'ASC',
                                'no_found_rows' => true,
                            ]);

                            if ($nav_applications->have_posts()):

                                while ($nav_applications->have_posts()):

                                    $nav_applications->the_post();

                                    $application_id = get_the_ID();

                                    $application_title = get_the_title(
                                        $application_id
                                    );

                                    $application_url = get_permalink(
                                        $application_id
                                    );

                                    /* =================================
                                    APPLICATION IMAGE

                                    ACF: application_hero_image

                                    Supports:
                                    - Image Array
                                    - Attachment ID
                                    - Image URL
                                    ================================= */

                                    $application_image = function_exists('get_field')
                                        ? get_field(
                                            'application_hero_image',
                                            $application_id
                                        )
                                        : null;

                                    $nav_image_url = '';

                                    // ACF Image Array.
                                    if (is_array($application_image)) {

                                        if (!empty($application_image['ID'])) {

                                            $nav_image_url = wp_get_attachment_image_url(
                                                (int) $application_image['ID'],
                                                'medium_large'
                                            );

                                        } elseif (!empty($application_image['url'])) {

                                            $nav_image_url = $application_image['url'];
                                        }

                                        // ACF Image ID.
                                    } elseif (is_numeric($application_image)) {

                                        $nav_image_url = wp_get_attachment_image_url(
                                            (int) $application_image,
                                            'medium_large'
                                        );

                                        // ACF Image URL.
                                    } elseif (is_string($application_image)) {

                                        $nav_image_url = $application_image;
                                    }

                                    /* =================================
                                    FEATURED IMAGE FALLBACK
                                    ================================= */

                                    if (!$nav_image_url) {

                                        $nav_image_url = get_the_post_thumbnail_url(
                                            $application_id,
                                            'medium_large'
                                        );
                                    }

                                    ?>

                                    <a href="<?php echo esc_url($application_url); ?>" class="nav-product-card">

                                        <?php if ($nav_image_url): ?>

                                            <img src="<?php echo esc_url($nav_image_url); ?>"
                                                alt="<?php echo esc_attr($application_title); ?>" loading="lazy" decoding="async">

                                        <?php else: ?>

                                            <span class="nav-mega-image-placeholder"></span>

                                        <?php endif; ?>

                                        <span class="nav-product-glass"></span>

                                        <span class="nav-product-card-content">

                                            <span class="nav-product-card-title">
                                                <?php echo esc_html($application_title); ?>
                                            </span>

                                        </span>

                                    </a>

                                    <?php

                                endwhile;

                                wp_reset_postdata();

                            else:

                                ?>

                                <p class="nav-mega-empty">
                                    Applications will be available here soon.
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>



                <!-- =========================================
                    MEDIA - REGULAR DROPDOWN
                ========================================== -->

                <div class="nav-links-container has-dropdown">

                    <a href="<?php echo esc_url(home_url('/media/')); ?>">
                        Media

                        <svg viewBox="0 0 10 6" fill="none" aria-hidden="true">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </a>

                    <div class="nav-dropdown">

                        <a href="<?php echo esc_url(home_url('/news/')); ?>">
                            News
                        </a>

                        <a href="<?php echo esc_url(home_url('/blog/')); ?>">
                            Blogs
                        </a>

                        <a href="<?php echo esc_url(home_url('/image-gallery/')); ?>">
                            Image Gallery
                        </a>

                        <a href="<?php echo esc_url(home_url('/video-gallery/')); ?>">
                            Video Gallery
                        </a>

                    </div>

                </div>

            </nav>

            <div class="nav-actions">
                <!-- Desktop Contact Button -->
                <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="link-btn  nav-contact">
                    <span>Contact Us</span>
                    <svg width="15" height="10" viewBox="0 0 13 10" aria-hidden="true">
                        <path d="M1,5 L11,5"></path>
                        <polyline points="8 1 12 5 8 9"></polyline>
                    </svg>
                </a>

                <!-- Mobile Menu Button -->
                <button type="button" class="menu-toggle" aria-label="Open navigation menu" aria-controls="mobile-menu"
                    aria-expanded="false">
                    <i data-lucide="menu"></i>
                </button>
            </div>
        </div>
    </header>

    <?php get_template_part('components/mobile-menu'); ?>