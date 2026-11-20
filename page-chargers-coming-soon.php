<?php
/**
 * Template Name: Chargers Coming Soon
 */
defined('ABSPATH') || exit;

get_header();
?>

<main class="lihi-cs">
    
        <div class="lihi-cs__card">

            <span class="lihi-cs__icon">
                <i data-lucide="battery-charging"></i>
            </span>

            <p class="lihi-cs__eyebrow">WE'RE STILL</p>

            <h1 class="lihi-cs__title">Charging Up Our Chargers.</h1>

            <p class="lihi-cs__text">
                Our charger range is launching very soon. Stay tuned,
                or explore our batteries in the meantime.
            </p>

            <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="lihi-cs__notify">
                <span class="lihi-cs__notify-icon">
                    <i data-lucide="mail"></i>
                </span>
                Notify Me
            </a>

            <div class="lihi-cs__social">
                 <a href="#" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="2" y="2" width="20" height="20" rx="6"
                            stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="12" cy="12" r="4"
                                stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="17.5" cy="6.5" r="1.2" fill="currentColor"/>
                    </svg>
                </a>
                <a href="#" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M20.45 2H3.55C2.69 2 2 2.68 2 3.52v16.96c0 .84.69 1.52 1.55 1.52h16.9c.86 0 1.55-.68 1.55-1.52V3.52c0-.84-.69-1.52-1.55-1.52zM7.93 18.75H4.98V9.2h2.95v9.55zM6.45 7.9a1.71 1.71 0 1 1 0-3.42 1.71 1.71 0 0 1 0 3.42zm12.3 10.85H15.8V14.1c0-1.11-.02-2.54-1.55-2.54-1.55 0-1.79 1.21-1.79 2.46v4.73H9.51V9.2h2.83v1.3h.04c.39-.75 1.36-1.55 2.8-1.55 3 0 3.57 1.97 3.57 4.53v5.27z"/>
                    </svg>
                </a>
                <a href="#" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.19 2.23.19v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.77l-.44 2.89h-2.33v6.99A10 10 0 0 0 22 12z"/>
                    </svg>
                </a>
            </div>

    </div>
</main>

<?php get_footer(); ?>
