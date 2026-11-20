
function initMobileMenu() {
    const menuBar = document.querySelector(".menu-bar");
    const menuToggle = document.querySelector(".menu-toggle");
    const menuClose = document.querySelector(".menu-close");

    if (!menuBar || !menuToggle || !menuClose) {
        console.warn("Mobile menu elements not found.");
        return;
    }

    menuBar.setAttribute("inert", "");

    function openMenu() {
        menuBar.removeAttribute("inert");
        menuBar.classList.add("is-open");

        menuToggle.setAttribute("aria-expanded", "true");
        document.body.style.overflow = "hidden";

        menuClose.focus();
    }

    function closeMenu(restoreFocus = true) {
        menuBar.classList.remove("is-open");
        menuBar.setAttribute("inert", "");

        menuToggle.setAttribute("aria-expanded", "false");
        document.body.style.overflow = "";

        if (restoreFocus && menuToggle.offsetParent !== null) {
            menuToggle.focus();
        }
    }

    menuToggle.addEventListener("click", openMenu);

    menuClose.addEventListener("click", () => {
        closeMenu();
    });


    const dropdownButtons = menuBar.querySelectorAll(
        ".mobile-dropdown-toggle"
    );

    dropdownButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const currentItem = button.closest(".mobile-menu-item");

            if (!currentItem) return;

            const isOpen = currentItem.classList.contains("is-active");

            menuBar.querySelectorAll(".mobile-menu-item").forEach((item) => {
                item.classList.remove("is-active");

                const toggle = item.querySelector(".mobile-dropdown-toggle");
                const submenu = item.querySelector(".mobile-submenu");

                if (toggle) {
                    toggle.setAttribute("aria-expanded", "false");
                }

                if (submenu) {
                    submenu.setAttribute("inert", "");
                }
            });

            if (!isOpen) {
                currentItem.classList.add("is-active");
                button.setAttribute("aria-expanded", "true");

                const submenu = currentItem.querySelector(".mobile-submenu");

                if (submenu) {
                    submenu.removeAttribute("inert");
                }
            }
        });
    });

    document.addEventListener("keydown", (event) => {
        if (
            event.key === "Escape" &&
            menuBar.classList.contains("is-open")
        ) {
            closeMenu();
        }
    });


    menuBar.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", () => {
            closeMenu(false);
        });
    });



    const mobileBreakpoint = window.matchMedia("(max-width: 480px)");

    mobileBreakpoint.addEventListener("change", (event) => {
        if (!event.matches && menuBar.classList.contains("is-open")) {
            closeMenu(false);
        }
    });

    document.addEventListener("click", (event) => {
        if (!menuBar.classList.contains("is-open")) return;

        const clickedOutside = !menuBar.contains(event.target);
        const clickedToggle = menuToggle.contains(event.target);

        if (clickedOutside && !clickedToggle) {
            closeMenu(false);
        }
    });
}

/* Initialize when DOM is ready */
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initMobileMenu);
} else {
    initMobileMenu();
}
