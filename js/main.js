
/* =========================================================
   LIHI GLOBAL JAVASCRIPT
   Lucide Icons, Back to Top, Mobile Footer
========================================================= */

document.addEventListener("DOMContentLoaded", () => {

    /* =====================================================
       1. LUCIDE ICONS
    ===================================================== */

    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }


    /* =====================================================
       2. BACK TO TOP BUTTON
    ===================================================== */

    const goToTopBtn = document.querySelector(".go-to-top-btn");

    if (goToTopBtn) {

        const toggleGoToTop = () => {
            goToTopBtn.classList.toggle(
                "show",
                window.scrollY > 300
            );
        };

        window.addEventListener(
            "scroll",
            toggleGoToTop,
            { passive: true }
        );

        toggleGoToTop();

        goToTopBtn.addEventListener("click", () => {

            if (window.lihiLenis) {

                window.lihiLenis.scrollTo(0, {
                    duration: 1.2
                });

            } else {

                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });

            }

        });
    }


    /* =====================================================
       3. FOOTER MOBILE DROPDOWN
    ===================================================== */

    document.addEventListener("click", (event) => {

        const button = event.target.closest(
            ".foot-dropdown-btn"
        );

        if (!button || window.innerWidth > 640) {
            return;
        }

        const currentColumn = button.closest(".foot-col");

        if (!currentColumn) return;

        const currentList = currentColumn.querySelector(
            ".foot-dropdown-list"
        );

        if (!currentList) return;

        const wasOpen = currentList.classList.contains(
            "active"
        );

        // Close all footer dropdowns.
        document.querySelectorAll(
            ".foot-dropdown-btn"
        ).forEach((btn) => {

            btn.classList.remove("active");

            btn.setAttribute(
                "aria-expanded",
                "false"
            );

            const column = btn.closest(".foot-col");

            const list = column?.querySelector(
                ".foot-dropdown-list"
            );

            if (list) {
                list.classList.remove("active");
            }

        });

        // Open selected dropdown.
        if (!wasOpen) {

            button.classList.add("active");

            button.setAttribute(
                "aria-expanded",
                "true"
            );

            currentList.classList.add("active");

        }

    });

    /* =========================================================
    3D MODEL FULLSCREEN
    ========================================================= */

    document.addEventListener('click', async (event) => {

        const openButton = event.target.closest('.lp-model-fullscreen-toggle');

        if (openButton) {

            const gallery = openButton.closest('.lp-gallery-main');
            const imageBox = gallery?.querySelector('.lp-image-box');

            if (!imageBox) return;

            try {
                await imageBox.requestFullscreen();
            } catch (error) {
                console.error('Unable to open fullscreen:', error);
            }

            return;
        }

        const closeButton = event.target.closest('.lp-model-fullscreen-close');

        if (closeButton && document.fullscreenElement) {
            await document.exitFullscreen();
        }

    });

    
});

