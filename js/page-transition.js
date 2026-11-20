
/* =========================================================
   LIHI - GLOBAL PAGE TRANSITION
========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    const html = document.documentElement;

    const overlay = document.querySelector(
        '.page-transition-overlay'
    );

    if (!overlay) return;

    const transitionTitle = overlay.querySelector(
        '.page-transition-title'
    );

    const transitionDuration = 850;

    let navigating = false;


    /* =====================================================
       PAGE ENTERING
    ===================================================== */

    if (html.classList.contains('is-transition-entering')) {

        


        let revealed = false;

        const finishEntering = () => {

            if (revealed) return;

            revealed = true;

            requestAnimationFrame(() => {

                requestAnimationFrame(() => {

                    html.classList.add('is-transition-ready');

                    window.setTimeout(() => {

                        html.classList.remove(
                            'is-transition-entering',
                            'is-transition-ready'
                        );

                        html.classList.add(
                            'is-transition-complete'
                        );

                    }, transitionDuration + 100);

                });

            });

        };


        if (document.readyState === 'complete') {

            finishEntering();

        } else {

            window.addEventListener(
                'load',
                finishEntering,
                { once: true }
            );

            window.setTimeout(finishEntering, 2500);

        }

    }


    /* =====================================================
       PAGE LEAVING
    ===================================================== */

    document.addEventListener('click', (event) => {

        const link = event.target.closest('a');

        if (!link) return;

        if (
            event.button !== 0 ||
            event.ctrlKey ||
            event.metaKey ||
            event.shiftKey ||
            event.altKey
        ) {
            return;
        }

        if (
            link.target === '_blank' ||
            link.hasAttribute('download') ||
            link.getAttribute('rel')?.includes('external') ||
            link.hasAttribute('data-no-transition') ||
            link.hasAttribute('data-lp-lightbox')
        ) {
            return;
        }

        const href = link.getAttribute('href');

        if (
            !href ||
            href.startsWith('#') ||
            href.startsWith('mailto:') ||
            href.startsWith('tel:') ||
            href.startsWith('javascript:')
        ) {
            return;
        }


        let destination;

        try {

            destination = new URL(
                href,
                window.location.href
            );

        } catch (error) {

            return;

        }


        if (destination.origin !== window.location.origin) {
            return;
        }


        if (
            destination.pathname.includes('/wp-admin') ||
            destination.pathname.includes('/wp-login')
        ) {
            return;
        }


        if (
            destination.pathname === window.location.pathname &&
            destination.search === window.location.search &&
            destination.hash
        ) {
            return;
        }


        if (navigating) return;

        navigating = true;

        event.preventDefault();


        let nextPageTitle = '';

        /* INDUSTRY & APPLICATION CARDS */

        const cardTitle = link.querySelector(
            '.industry-card h3, .application-card h3'
        );

        if (cardTitle) {

            nextPageTitle = cardTitle.textContent.trim();

        } else {

            nextPageTitle =
                link.getAttribute('aria-label') ||
                link.getAttribute('title') ||
                link.textContent.trim();

        }

        nextPageTitle = nextPageTitle.replace(/\s+/g, ' ').trim();


        if (!nextPageTitle) {

            const slug = destination.pathname
                .split('/')
                .filter(Boolean)
                .pop() || 'Home';

            nextPageTitle = slug
                .replace(/[-_]/g, ' ')
                .replace(/\b\w/g, char => char.toUpperCase());

        }


        if (transitionTitle) {
            transitionTitle.textContent = nextPageTitle;
        }


        try {

            sessionStorage.setItem(
                'lihi-page-title',
                nextPageTitle
            );

            sessionStorage.setItem(
                'lihi-page-transition',
                '1'
            );

        } catch (error) {}


        html.classList.remove(
            'is-transition-complete',
            'is-transition-entering',
            'is-transition-ready'
        );

        html.classList.add(
            'is-transition-preparing'
        );


        overlay.offsetHeight;


        html.classList.remove(
            'is-transition-preparing'
        );

        html.classList.add(
            'is-transition-leaving'
        );


        window.setTimeout(() => {

            window.location.assign(destination.href);

        }, transitionDuration);

    }, true);
    
});


/* =========================================================
   FIX BROWSER BACK / FORWARD NAVIGATION
========================================================= */

window.addEventListener('pageshow', function (event) {

    const navigation = performance.getEntriesByType('navigation')[0];

    const isHistoryNavigation =
        event.persisted ||
        (navigation && navigation.type === 'back_forward');

    if (!isHistoryNavigation) return;


    const html = document.documentElement;

    const overlay = document.querySelector(
        '.page-transition-overlay'
    );

    if (!overlay) return;


    // Remove all previous transition states.
    html.classList.remove(
        'is-transition-entering',
        'is-transition-ready',
        'is-transition-complete',
        'is-transition-preparing',
        'is-transition-leaving'
    );


    // Clear any previous navigation data.
    try {

        sessionStorage.removeItem('lihi-page-transition');
        sessionStorage.removeItem('lihi-page-title');

    } catch (error) {}


    // Immediately reset the curtain below the screen.
    overlay.style.setProperty('transition', 'none');
    overlay.style.setProperty(
        'transform',
        'translate3d(0, 100%, 0)'
    );

    // Force the browser to apply the reset.
    overlay.offsetHeight;

    // Return control to the original stylesheet.
    overlay.style.removeProperty('transition');
    overlay.style.removeProperty('transform');


    // Clear the previous page title.
    const title = overlay.querySelector(
        '.page-transition-title'
    );

    if (title) {
        title.textContent = '';
    }

});

