document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-lp-tabs]').forEach(function (container) {
        const tabs = Array.from(container.querySelectorAll('[role="tab"]'));

        function activateTab(selectedTab, focus = false) {
            tabs.forEach(function (tab) {
                const active = tab === selectedTab;
                const panel = document.getElementById(tab.getAttribute('aria-controls'));

                tab.classList.toggle('is-active', active);
                tab.setAttribute('aria-selected', active ? 'true' : 'false');
                tab.tabIndex = active ? 0 : -1;

                if (panel) {
                    panel.hidden = !active;
                }
            });

            if (focus) {
                selectedTab.focus();
            }
        }

        tabs.forEach(function (tab, index) {
            tab.addEventListener('click', function () {
                activateTab(tab);
            });

            tab.addEventListener('keydown', function (event) {
                let next = null;

                if (event.key === 'ArrowRight') {
                    next = tabs[(index + 1) % tabs.length];
                } else if (event.key === 'ArrowLeft') {
                    next = tabs[(index - 1 + tabs.length) % tabs.length];
                }

                if (next) {
                    event.preventDefault();
                    activateTab(next, true);
                }
            });
        });
    });
});

/* =========================================
   PRODUCT IMAGE LIGHTBOX
========================================= */

document.addEventListener('DOMContentLoaded', function () {

    const lightbox = document.getElementById('lp-lightbox');

    if (!lightbox) {
        return;
    }

    const lightboxImage = lightbox.querySelector('img');
    const closeButton = lightbox.querySelector('.lp-lightbox-close');

    let previousFocus = null;

    function closeLightbox() {
        lightbox.hidden = true;
        lightboxImage.classList.remove('is-zoomed');
        lightboxImage.removeAttribute('src');
        document.body.style.overflow = '';

        if (previousFocus) {
            previousFocus.focus();
        }
    }

    document.querySelectorAll('[data-lp-lightbox]').forEach(function (link) {

        link.addEventListener('click', function (event) {
            event.preventDefault();

            previousFocus = link;
            lightboxImage.src = link.href;
            lightboxImage.alt = link.querySelector('img')?.alt || 'Product image';

            lightbox.hidden = false;
            document.body.style.overflow = 'hidden';
            closeButton.focus();
        });

    });

    closeButton.addEventListener('click', closeLightbox);

    lightbox.addEventListener('click', function (event) {
        if (event.target === lightbox) {
            closeLightbox();
        }
    });

    lightboxImage.addEventListener('click', function () {
        lightboxImage.classList.toggle('is-zoomed');
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !lightbox.hidden) {
            closeLightbox();
        }
    });

});
