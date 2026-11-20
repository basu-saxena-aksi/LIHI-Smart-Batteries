<?php
$eyebrow = $args['eyebrow'] ?? 'ABOUT LIHI SMART BATTERIES';
$heading = $args['heading'] ?? "POWERING WHAT'S NEXT.";
$highlight = $args['highlight'] ?? '';
$description = $args['description'] ?? '';
$background = $args['background'] ?? 'about-us-hero-bg.jpg';

if (filter_var($background, FILTER_VALIDATE_URL)) {
    $background_url = $background;
} else {
    $background_url = get_template_directory_uri() . '/images/' . ltrim($background, '/');
}
?>

<section class="about-hero parallax-section">

    <div class="about-hero-media">
        <div
            class="about-hero-bg"
            style="background-image: url('<?php echo esc_url($background_url); ?>');"
            
        ></div>
    </div>

    

    <div class="about-hero-overlay"></div>
    <div class="about-hero-overlay-2"></div>

    <div class="about-hero-content wrap">

        <span class="about-hero-eyebrow">
            <span><?php echo esc_html($eyebrow); ?></span>
        </span>

        <h1 class="about-hero-title">
            <?php echo esc_html($heading); ?>
            <?php if ($highlight) : ?>
                <span><?php echo esc_html($highlight); ?></span>
            <?php endif; ?>
        </h1>

        <p class="about-hero-description">
            <?php echo esc_html($description); ?>
        </p>

    </div>

    <div class="about-hero-bottom">

        <div class="scroll-indicator">
            <svg viewBox="0 0 40 72" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="lihiScrollGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#2350DB"/>
                        <stop offset="50%" stop-color="#528DD0"/>
                        <stop offset="100%" stop-color="#59BC44"/>
                    </linearGradient>
                </defs>

                <rect
                    x="4" y="3"
                    width="32" height="66"
                    rx="16"
                    fill="none"
                    stroke="url(#lihiScrollGradient)"
                    stroke-width="3.5"
                />

                <circle
                    class="scroll-dot"
                    cx="20"
                    cy="17"
                    r="3.5"
                    fill="url(#lihiScrollGradient)"
                />
            </svg>
        </div>
    </div>

</section>