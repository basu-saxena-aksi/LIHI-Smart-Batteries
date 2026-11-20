/* =========================================================
   LIHI - MISSION, VISION & VALUES
   SCROLL-BASED HEADING CHANGE
========================================================= */

(() => {
    'use strict';

    function init() {

        const section = document.querySelector('.mvv-section');
        if (!section) return;

        const heading     = section.querySelector('[data-mvv-heading]');
        const headingText = heading ? heading.querySelector('span') : null;
        const progress    = section.querySelector('[data-mvv-progress]');
        const counter     = section.querySelector('[data-mvv-counter]');
        const items       = [...section.querySelectorAll('[data-mvv-item]')];

        if (!headingText || !progress || !items.length) return;

        let activeIndex = -1;
        let ticking = false;
        let headingTimer = null;


        /* =================================================
           UPDATE ACTIVE PANEL
        ================================================= */

        function activateItem(index) {

            if (index === activeIndex) return;

            activeIndex = index;

            const activeItem = items[index];

            items.forEach((item, i) => {
                item.classList.toggle('is-active', i === index);
            });

            /* Heading fade swap (cancel any pending swap first) */
            const title = activeItem.dataset.heading;

            clearTimeout(headingTimer);

            headingText.style.opacity = '0';
            headingText.style.transform = 'translateY(8px)';

            headingTimer = setTimeout(() => {
                headingText.textContent = title + '.';
                headingText.style.opacity = '1';
                headingText.style.transform = 'translateY(0)';
            }, 180);

            /* Progress bar */
            progress.style.transform =
                `scaleX(${(index + 1) / items.length})`;

            /* Number indicator */
            if (counter) {
                counter.textContent = String(index + 1).padStart(2, '0');
            }
        }


        /* =================================================
           FIND PANEL CLOSEST TO VIEWPORT CENTRE
        ================================================= */

        function updateActivePanel() {

            ticking = false;

            const viewportCenter = window.innerHeight / 2;

            let closestIndex = 0;
            let closestDistance = Infinity;

            items.forEach((item, index) => {

                const rect = item.getBoundingClientRect();
                const itemCenter = rect.top + rect.height / 2;
                const distance = Math.abs(itemCenter - viewportCenter);

                if (distance < closestDistance) {
                    closestDistance = distance;
                    closestIndex = index;
                }

            });

            activateItem(closestIndex);
        }


        /* =================================================
           SCROLL HANDLER
        ================================================= */

        function onScroll() {

            if (ticking) return;

            ticking = true;
            requestAnimationFrame(updateActivePanel);
        }


        /* =================================================
           EVENT LISTENERS
        ================================================= */

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);


        /* =================================================
           INITIALIZE
        ================================================= */

        updateActivePanel();
    }

    /* Run whether the script loads before or after DOM ready
       (important when WordPress prints it in the footer) */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();