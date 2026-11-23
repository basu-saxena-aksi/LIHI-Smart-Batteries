<?php
$upload_dir = wp_get_upload_dir();
$video_url = $upload_dir['baseurl'] . '/2026/10/sample_vid.mp4';
?>

<section class="hero-top">
    <div class="hero-top-container">
        <video class="hero-vid" autoplay muted loop playsinline preload="auto">
            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
        </video>
    </div>
    <div class="overlay1"></div>
    <div class="overlay2"></div>
    <div class="overlay3"></div>
    <div class="hero-top-info wrap">
        <div class="hero-top-info-container">
            <div class="badge home-eyebrow">
                <span class="badge-marquee-viewport">
                    <span class="badge-marquee">
                        <span><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 2L14.8 9.2L22 12L14.8 14.8L12 22L9.2 14.8L2 12L9.2 9.2Z" />
                            </svg>POWERING TOMORROW'S INNOVATION</span>
                        <span aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L14.8 9.2L22 12L14.8 14.8L12 22L9.2 14.8L2 12L9.2 9.2Z" />
                            </svg>POWERING TOMORROW'S INNOVATION</span>
                        <span aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L14.8 9.2L22 12L14.8 14.8L12 22L9.2 14.8L2 12L9.2 9.2Z" />
                            </svg>POWERING TOMORROW'S INNOVATION</span>
                        <span aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L14.8 9.2L22 12L14.8 14.8L12 22L9.2 14.8L2 12L9.2 9.2Z" />
                            </svg>POWERING TOMORROW'S INNOVATION</span>
                    </span>
                </span>
            </div>

            <!-- <h1 class="home-hero-title">POWER.<br>INTELLIGENCE.<br><span class="accent">FUTURE.</span></h1> -->
            <h1 class="home-hero-title">POWER. INTELLIGENCE. <span class="accent">FUTURE.</span></h1>
            <p class="home-lead">LiHi Smart Batteries deliver high-performance power solutions for <br> drones, defense,
                energy, telecom and industrial applications.</p>
            <a href="<?php echo esc_url(home_url('/product-category/battery/')); ?>" class="link-btn ">
                <span>Explore Products</span>
                <svg width="15" height="10" viewBox="0 0 13 10" aria-hidden="true">
                    <path d="M1,5 L11,5"></path>
                    <polyline points="8 1 12 5 8 9"></polyline>
                </svg>
            </a>
        </div>
        <!-- <div class="hero-ctas">
                <a href="<?php echo esc_url(home_url('/products')); ?>" class="btn btn-primary">Explore Products
                    <i data-lucide="arrow-down"></i>
                </a>
                <a href="<?php echo esc_url(home_url('/applications/')); ?>"
                    class="link-btn">
                        <span>Explore Applications</span>
                        <svg width="15" height="10" viewBox="0 0 13 10" aria-hidden="true">
                            <path d="M1,5 L11,5"></path>
                            <polyline points="8 1 12 5 8 9"></polyline>
                        </svg>
                </a>
            </div> -->


    </div>


    </div>

    <div class="hero">
        <div class="hero-features">
            <div class="hf-item">
                <img class="hf-icon"
                    src="<?php echo esc_url(get_template_directory_uri() . '/images/high-energy-density.png'); ?>"
                    alt="High Energy Density">
                <strong>HIGH ENERGY DENSITY</strong><span>More power in less weight</span>
            </div>
            <div class="hf-item">
                <img class="hf-icon" src="<?php echo esc_url(get_template_directory_uri() . '/images/bim.png'); ?>"
                    alt="High Energy Density">
                <strong>SMART BMS</strong><span>Intelligent protection and monitoring</span>
            </div>
            <div class="hf-item">
                <img class="hf-icon"
                    src="<?php echo esc_url(get_template_directory_uri() . '/images/fast-charging.png'); ?>"
                    alt="High Energy Density">
                <strong>FAST CHARGING</strong><span>Less downtime, more performance</span>
            </div>
            <div class="hf-item">
                <img class="hf-icon"
                    src="<?php echo esc_url(get_template_directory_uri() . '/images/safe-&-reliable.png'); ?>"
                    alt="High Energy Density">
                <strong>SAFE & RELIABLE</strong><span>Built to last in extreme conditions</span>
            </div>
            <div class="hf-item">
                <img class="hf-icon"
                    src="<?php echo esc_url(get_template_directory_uri() . '/images/global-compliance.png'); ?>"
                    alt="High Energy Density">
                <strong>GLOBAL COMPLIANCE</strong><span>Tested to meet international standards</span>
            </div>
        </div>
    </div>
</section>