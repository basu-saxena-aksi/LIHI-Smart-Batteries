
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
       HELPERS
    ===================================================== */

    // Detect the WordPress homepage.
    const isHomePage =
        document.body.classList.contains('home') ||
        document.body.classList.contains('front-page');


    // Get the homepage URL from WordPress/theme markup.
    const getHomeUrl = () => {

        const homeLink =
            document.querySelector('link[rel="home"]') ||
            document.querySelector('.custom-logo-link');

        return homeLink
            ? new URL(homeLink.href, window.location.href)
            : new URL('/', window.location.origin);

    };


    // Check whether the destination is the homepage.
    const isHomeDestination = (destination) => {

        const homeUrl = getHomeUrl();

        const normalizePath = (path) =>
            path.replace(/\/+$/, '') || '/';

        return (
            destination.origin === homeUrl.origin &&
            normalizePath(destination.pathname) ===
            normalizePath(homeUrl.pathname)
        );

    };


    // Clear transition states and reset the curtain.
    const resetTransition = () => {

        html.classList.remove(
            'is-transition-entering',
            'is-transition-ready',
            'is-transition-complete',
            'is-transition-preparing',
            'is-transition-leaving'
        );

        overlay.style.setProperty('transition', 'none');

        overlay.style.setProperty(
            'transform',
            'translate3d(0, 100%, 0)'
        );

        overlay.offsetHeight;

        overlay.style.removeProperty('transition');
        overlay.style.removeProperty('transform');

    };


    // Clear stored transition information.
    const clearTransitionStorage = () => {

        try {

            sessionStorage.removeItem('lihi-page-transition');
            sessionStorage.removeItem('lihi-page-title');

        } catch (error) {}

    };


    /* =====================================================
       HOMEPAGE — SKIP PAGE ENTERING CURTAIN
    ===================================================== */

    if (isHomePage) {

        resetTransition();
        clearTransitionStorage();

        if (transitionTitle) {
            transitionTitle.textContent = '';
        }

    } else {


        /* =================================================
           PAGE ENTERING
        ================================================= */

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


        // Skip the curtain when navigating TO the homepage.
        // The homepage has its own preloader animation.
        if (isHomeDestination(destination)) {
            return;
        }


        navigating = true;

        event.preventDefault();


        /* ================================================
           GET DESTINATION PAGE TITLE
        ================================================ */

        let nextPageTitle = '';

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


        /* ================================================
           STORE TRANSITION INFORMATION
        ================================================ */

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


        /* ================================================
           START LEAVING ANIMATION
        ================================================ */

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


    // Remove previous transition states.
    html.classList.remove(
        'is-transition-entering',
        'is-transition-ready',
        'is-transition-complete',
        'is-transition-preparing',
        'is-transition-leaving'
    );


    // Clear previous navigation data.
    try {

        sessionStorage.removeItem('lihi-page-transition');
        sessionStorage.removeItem('lihi-page-title');

    } catch (error) {}


    // Reset the curtain.
    overlay.style.setProperty('transition', 'none');

    overlay.style.setProperty(
        'transform',
        'translate3d(0, 100%, 0)'
    );

    overlay.offsetHeight;

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
