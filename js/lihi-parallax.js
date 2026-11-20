(() => {
    'use strict';

    const elements = [...document.querySelectorAll('[data-lihi-parallax]')];

    if (!elements.length) return;

    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    );

    const items = elements.map(element => {

        const speed = parseFloat(
            element.dataset.lihiParallax || '0.2'
        );

        const container =
            element.closest('[data-lihi-parallax-container]') ||
            element.parentElement;

        return {
            element,
            container,
            speed: Number.isFinite(speed) ? speed : 0.2
        };

    }).filter(item => item.container);

    let ticking = false;

    function resize() {

        items.forEach(item => {

            const height = item.container.offsetHeight;

            const padding = Math.ceil(
                (window.innerHeight + height) *
                Math.abs(item.speed) / 2
            ) + 4;

            item.element.style.setProperty(
                '--lihi-parallax-padding',
                `${padding}px`
            );

        });

    }

    function update() {

        ticking = false;

        items.forEach(item => {

            if (reducedMotion.matches) {
                item.element.style.transform = 'none';
                return;
            }

            const rect = item.container.getBoundingClientRect();

            const offset =
                rect.top +
                rect.height / 2 -
                window.innerHeight / 2;

            const movement = -offset * item.speed;

            item.element.style.transform =
                `translate3d(0, ${movement.toFixed(2)}px, 0)`;

        });

    }

    function requestUpdate() {

        if (ticking) return;

        ticking = true;

        requestAnimationFrame(update);

    }

    window.addEventListener(
        'scroll',
        requestUpdate,
        { passive: true }
    );

    window.addEventListener('resize', () => {
        resize();
        requestUpdate();
    });

    reducedMotion.addEventListener('change', requestUpdate);

    resize();
    update();

})();

document.addEventListener("DOMContentLoaded", () => {

    if (
        typeof gsap === "undefined" ||
        typeof ScrollTrigger === "undefined"
    ) return;

    const hero = document.querySelector(".about-hero");
    const media = hero?.querySelector(".about-hero-media");

    if (!hero || !media) return;

    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        return;
    }

    gsap.registerPlugin(ScrollTrigger);

    gsap.fromTo(
        media,
        { yPercent: -8 },
        {
            yPercent: 8,
            ease: "none",
            scrollTrigger: {
                trigger: hero,
                start: "top top",
                end: "bottom top",
                scrub: 1.5
            }
        }
    );

});