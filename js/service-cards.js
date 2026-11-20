
/* =========================================================
   LIHI SERVICE CARDS - HOVER ANIMATION
========================================================= */

(() => {
    'use strict';

    /* =====================================================
       CONFIGURATION
    ===================================================== */

    const PUSH = {
        x: 5,
        y: 9
    };

    const ICON_ZOOM = 0.2;

    const SPEED = {
        move: 10,
    };

    const clamp = (value, min, max) =>
        Math.min(max, Math.max(min, value));

    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    );

    /* =====================================================
       INITIALIZE
    ===================================================== */

    const grids = document.querySelectorAll(
        '[data-lihi-services]'
    );

    if (!grids.length) return;

    grids.forEach(initServiceCards);

    function initServiceCards(grid) {

        const cards = [
            ...grid.querySelectorAll('.lihi-svc__card')
        ].map(card => ({
            card,
            shadow: card.querySelector('.lihi-svc__shadow'),
            icon: card.querySelector('.lihi-svc__icon'),

            strength: 0,
            targetStrength: 0,

            nx: 0,
            targetNX: 0,

            ny: 0,
            targetNY: 0
        }));

        let raf = 0;
        let lastTime = 0;

        /* =====================================================
           START ANIMATION
        ===================================================== */

        function kick() {

            if (raf || reducedMotion.matches) return;

            lastTime = performance.now();

            raf = requestAnimationFrame(frame);
        }

        /* =====================================================
           ANIMATION FRAME
        ===================================================== */

        function frame(now) {

            const dt = Math.min(
                (now - lastTime) / 1000,
                0.05
            ) || 0.016;

            lastTime = now;

            const ease = rate =>
                1 - Math.exp(-rate * dt);

            let moving = false;

            cards.forEach(item => {

                item.nx += (
                    item.targetNX - item.nx
                ) * ease(SPEED.move);

                item.ny += (
                    item.targetNY - item.ny
                ) * ease(SPEED.move);

                if (
                    Math.abs(item.targetNX - item.nx) > 0.0005 ||
                    Math.abs(item.targetNY - item.ny) > 0.0005
                ) {
                    moving = true;
                }

                /* Whole card moves away from cursor */

                const translateX = -item.nx * PUSH.x;
                const translateY = -item.ny * PUSH.y;

                item.card.style.transform =
                    `translate3d(${translateX.toFixed(2)}px, ` +
                    `${translateY.toFixed(2)}px, 0)`;


            });

            raf = moving
                ? requestAnimationFrame(frame)
                : 0;
        }

        /* =====================================================
           NORMALIZED POINTER POSITION
        ===================================================== */

        function normalizePointer(item, x, y) {

            const gridRect = grid.getBoundingClientRect();
            const card = item.card;

            const width = card.offsetWidth;
            const height = card.offsetHeight;

            const centerX =
                gridRect.left +
                card.offsetLeft +
                width / 2;

            const centerY =
                gridRect.top +
                card.offsetTop +
                height / 2;

            item.targetNX = clamp(
                (x - centerX) / (width / 2),
                -1,
                1
            );

            item.targetNY = clamp(
                (y - centerY) / (height / 2),
                -1,
                1
            );
        }

        /* =====================================================
           POINTER EVENTS
        ===================================================== */

        cards.forEach(item => {

            item.card.addEventListener(
                'pointerenter',
                event => {

                    

                    if (
                        event.pointerType === 'touch' ||
                        reducedMotion.matches
                    ) return;

                    item.targetStrength = 1;
                    if (item.icon) {
                        item.icon.style.transform = `scale(${1 + ICON_ZOOM})`;
                    }
                    if (item.icon) {
                        item.icon.style.transform = `scale(${1 + ICON_ZOOM})`;
                    }

                    normalizePointer(
                        item,
                        event.clientX,
                        event.clientY
                    );

                    kick();
                }
            );

            item.card.addEventListener(
                'pointermove',
                event => {

                    if (
                        event.pointerType === 'touch' ||
                        reducedMotion.matches
                    ) return;

                    normalizePointer(
                        item,
                        event.clientX,
                        event.clientY
                    );

                    kick();
                }
            );

            item.card.addEventListener(
                'pointerleave',
                () => {
                    

                    item.targetStrength = 0;

                    if (item.icon) {
                        item.icon.style.transform = 'scale(1)';
                    }
                    if (item.icon) {
                        item.icon.style.transform = 'scale(1)';
                    }
                    item.targetNX = 0;
                    item.targetNY = 0;

                    kick();
                }
            );
        });

        /* =====================================================
           REDUCED MOTION
        ===================================================== */

        reducedMotion.addEventListener('change', () => {

            if (!reducedMotion.matches) return;

            if (raf) {
                cancelAnimationFrame(raf);
                raf = 0;
            }

            cards.forEach(item => {

                item.strength = 0;
                item.targetStrength = 0;

                item.nx = 0;
                item.targetNX = 0;

                item.ny = 0;
                item.targetNY = 0;

                item.card.style.transform = 'none';

                if (item.shadow) {
                    item.shadow.style.opacity = '0';
                }

                if (item.icon) {
                    item.icon.style.transform = 'none';
                }
            });
        });
    }

})();
