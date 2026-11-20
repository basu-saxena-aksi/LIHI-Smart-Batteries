
/* =========================================================
   LIHI STATS - COUNTERS & SCROLL REVEAL
   Parallax handled by global lihi-parallax.js
========================================================= */

(() => {
    'use strict';

    const sections = document.querySelectorAll('.lihi-stats');

    if (!sections.length) return;

    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    );

    sections.forEach(initStats);

    function initStats(section) {

        const counters = [
            ...section.querySelectorAll('[data-count]')
        ];

        let counted = false;

        /* =====================================================
           STATISTICS COUNT-UP
        ===================================================== */

        function animateCounter(element) {

            const end = Number(element.dataset.count) || 0;
            const suffix = element.dataset.suffix || '';

            const duration = 1400;
            const startTime = performance.now();

            if (reducedMotion.matches) {
                element.textContent = `${end}${suffix}`;
                return;
            }

            function step(now) {

                const progress = Math.min(
                    (now - startTime) / duration,
                    1
                );

                const eased = 1 - Math.pow(
                    1 - progress,
                    3
                );

                const value = Math.round(end * eased);

                element.textContent = `${value}${suffix}`;

                if (progress < 1) {
                    requestAnimationFrame(step);
                }
            }

            requestAnimationFrame(step);
        }

        /* =====================================================
           REVEAL STATISTICS ON SCROLL
        ===================================================== */

        function revealStats() {

            if (counted) return;

            counted = true;

            section.classList.add('is-in');

            counters.forEach(animateCounter);
        }

        /* =====================================================
           INTERSECTION OBSERVER - COUNTERS
        ===================================================== */

        const counterObserver = new IntersectionObserver(
            (entries, observer) => {

                if (!entries[0].isIntersecting) return;

                revealStats();

                observer.disconnect();

            },
            {
                threshold: 0.3
            }
        );

        counterObserver.observe(section);

        /* =====================================================
           REDUCED MOTION CHANGES
        ===================================================== */

        reducedMotion.addEventListener('change', () => {

            if (!reducedMotion.matches || !counted) return;

            counters.forEach(element => {

                const end = Number(element.dataset.count) || 0;
                const suffix = element.dataset.suffix || '';

                element.textContent = `${end}${suffix}`;
            });
        });
    }

})();
