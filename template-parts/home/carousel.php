
<?php
/**
 * LiHi Technology Image Carousel
 */

if (!defined('ABSPATH')) {
    exit;
}

$carousel_images = [
    [
        'image' => get_template_directory_uri() . '/images/adv_cell.png',
        'alt' => 'Advanced Battery Cells',
        'title' => 'ADVANCED CELLS',
        'description' => 'High-energy-density cells engineered for dependable power and extended flight performance.',
    ],
    [
        'image' => get_template_directory_uri() . '/images/BMS.png',
        'alt' => 'Intelligent Battery Management System',
        'title' => 'INTELLIGENT BMS',
        'description' => 'Smart battery management for precise monitoring, protection, and optimized performance.',
    ],
    [
        'image' => get_template_directory_uri() . '/images/battery_expload.png',
        'alt' => 'Battery Thermal Management',
        'title' => 'THERMAL MANAGEMENT',
        'description' => 'Efficient thermal design helps maintain stable performance in demanding conditions.',
    ],
    [
        'image' => get_template_directory_uri() . '/images/rugged.png',
        'alt' => 'Rugged Battery Design',
        'title' => 'RUGGED DESIGN',
        'description' => 'Durable battery construction designed for challenging operating environments.',
    ],
];
?>


<section
    class="lihi-image-carousel lihi-parallax"
    id="lihi-image-carousel"
    data-start="0"
    aria-roledescription="carousel"
    aria-label="LiHi Advanced Technology"
>

    <!-- PARALLAX BACKGROUND -->
    <div
        class="lihi-parallax__clip lihi-parallax-container"
        data-lihi-parallax-container
        aria-hidden="true">

        <div
            class="lihi-parallax__bg lihi-parallax-layer"
            data-lihi-parallax="0.28">
        </div>

    </div>

    <div class="wrap">

        <!-- SECTION HEADING -->
        <div class="lihi-carousel-header">

            <h2 class="home-section-title">
                ADVANCED <span class="g">TECHNOLOGY</span>.<br>
                SUPERIOR PERFORMANCE.
            </h2>

            <p class="home-body-text">
                Cutting-edge cells, intelligent BMS and robust design work
                together to deliver unmatched performance and safety.
            </p>

        </div>

        <!-- EXISTING CAROUSEL -->
        <div class="lihi-carousel-stage">

            <div class="lihi-carousel-viewport">

                <?php foreach ($carousel_images as $index => $image) : ?>

                    <div
                        class="lihi-carousel-slide"
                        aria-roledescription="slide"
                        aria-label="<?php echo esc_attr(($index + 1) . ' of ' . count($carousel_images)); ?>"
                    >

                        <div class="lihi-carousel-slide-img">
                            <img
                                src="<?php echo esc_url($image['image']); ?>"
                                alt="<?php echo esc_attr($image['alt']); ?>"
                                draggable="false"
                                loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                            >
                        </div>

                        <div
                            class="lihi-carousel-overlay"
                            aria-hidden="true"
                        ></div>

                        <div class="lihi-carousel-content">

                            <h2 class="home-section-title lihi-carousel-title">
                                <?php echo esc_html($image['title']); ?>
                            </h2>

                            <p class="home-body-text lihi-carousel-description">
                                <?php echo esc_html($image['description']); ?>
                            </p>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <!-- PREVIOUS -->
            <button
                class="lihi-carousel-nav lihi-carousel-prev"
                type="button"
                aria-label="Previous slide"
            >
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 12H5M11 6l-6 6 6 6"></path>
                </svg>
            </button>

            <!-- NEXT -->
            <button
                class="lihi-carousel-nav lihi-carousel-next"
                type="button"
                aria-label="Next slide"
            >
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 12h15M13 6l6 6-6 6"></path>
                </svg>
            </button>

            <!-- PAGINATION -->
            <div
                class="lihi-carousel-dots"
                role="group"
                aria-label="Carousel pagination"
            ></div>

        </div>

    </div>

</section>


