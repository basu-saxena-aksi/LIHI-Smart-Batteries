
(() => {
    'use strict';

    /* =========================================================
       LIHI IMAGE CAROUSEL - SETTINGS
    ========================================================= */

    const PUSH = 0.5;
    const PARALLAX = 0.5;
    const OMEGA = 13;
    const FLICK_V = 0.6;
    const DRAG_FRAC = 0.28;
    /* Autoplay settings */
    const AUTOPLAY_DELAY = 5000;
    // const AUTOPLAY_RESUME_DELAY = 5000;

    /* =========================================================
       INITIALIZE CAROUSEL
    ========================================================= */

    function initCarousel(root) {

        const slides = [
            ...root.querySelectorAll('.lihi-carousel-slide')
        ];

        const imgs = slides.map(slide =>
            slide.querySelector('.lihi-carousel-slide-img')
        );

        const contents = slides.map(slide =>
            slide.querySelector('.lihi-carousel-content')
        );

        const dotsBox = root.querySelector('.lihi-carousel-dots');
        const prevBtn = root.querySelector('.lihi-carousel-prev');
        const nextBtn = root.querySelector('.lihi-carousel-next');

        const N = slides.length;

        if (
            !N ||
            !dotsBox ||
            !prevBtn ||
            !nextBtn ||
            imgs.some(img => !img)
        ) {
            return;
        }

        /* =====================================================
           PAGINATION DOTS
        ===================================================== */

        const dots = slides.map((_, i) => {

            const button = document.createElement('button');

            button.type = 'button';
            button.className = 'lihi-carousel-dot';

            button.setAttribute(
                'aria-label',
                `Go to slide ${i + 1}`
            );

            button.addEventListener('click', () => goTo(i));

            dotsBox.appendChild(button);

            return button;
        });

        /* =====================================================
           STATE
        ===================================================== */

        const viewport = root.querySelector('.lihi-carousel-viewport');

        let W = Math.max(1, viewport.clientWidth);

        let cur = Math.max(
            0,
            Math.min(N - 1, Number(root.dataset.start) || 0)
        );

        let p = 0;
        let v = 0;
        let target = 0;

        let dragging = false;

        let raf = 0;
        let lastT = 0;

        let shown = [-1, -1];
        let queued = 0;

        const mod = n => ((n % N) + N) % N;

        /* =====================================================
        AUTOPLAY
        ===================================================== */

        let autoplayTimer = null;

        function startAutoplay() {

            if (N < 2 || autoplayTimer !== null) return;

            autoplayTimer = setInterval(() => {

                if (!dragging && target === 0 && Math.abs(p) < 0.001) {
                    step(1);
                }

            }, AUTOPLAY_DELAY);
        }

        /* =====================================================
           RENDER SLIDES
        ===================================================== */

        function render() {

            const dir = p >= 0 ? 1 : -1;
            const a = Math.min(1, Math.abs(p));
            const nb = mod(cur + dir);

            slides.forEach((slide, i) => {
                const isCurrent = i === cur;
                const isIncoming = i === nb && a > 0;

                slide.style.visibility =
                    isCurrent || isIncoming ? 'visible' : 'hidden';

                slide.style.zIndex =
                    isIncoming ? 2 : (isCurrent ? 1 : 0);
            }); 

            slides[cur].style.visibility = 'visible';

            if (nb !== cur) {
                slides[nb].style.visibility =
                    a > 0 ? 'visible' : 'hidden';
            }

            /* =================================================
               CURRENT SLIDE PUSH
            ================================================= */

            slides[cur].style.transform =
                `translate3d(${(-PUSH * p * W).toFixed(2)}px,0,0)`;

            imgs[cur].style.transform =
                'translate3d(0,0,0)';

            /* =================================================
               INCOMING SLIDE OVERLAP & PARALLAX
            ================================================= */

            if (nb !== cur) {

                slides[nb].style.transform =
                    `translate3d(${(dir * (1 - a) * W).toFixed(2)}px,0,0)`;

                imgs[nb].style.transform =
                    `translate3d(${(-dir * (1 - a) * PARALLAX * W).toFixed(2)}px,0,0)`;
            }

            /* =================================================
               TEXT-ONLY FADE DURING SLIDE MOVEMENT
            ================================================= */

            if (contents[cur]) {

                contents[cur].style.opacity =
                    Math.max(0, 1 - a * 2);
            }

            if (nb !== cur && contents[nb]) {

                contents[nb].style.opacity =
                    Math.min(1, a * 2);
            }

            /* Images and overlays never fade */

            slides[cur].style.opacity = 1;

            if (nb !== cur) {
                slides[nb].style.opacity = 1;
            }
        }

        /* =====================================================
           UPDATE DOTS
        ===================================================== */

        function paintDots() {

            dots.forEach((dot, i) => {

                dot.classList.toggle(
                    'is-active',
                    i === cur
                );

                dot.setAttribute(
                    'aria-current',
                    i === cur ? 'true' : 'false'
                );

            });
        }

        /* =====================================================
           SPRING ANIMATION
        ===================================================== */

        function startAnim() {

            if (raf) return;

            lastT = performance.now();

            raf = requestAnimationFrame(tick);
        }

        function tick(now) {

            raf = 0;

            if (dragging) return;

            const dt = Math.min(
                (now - lastT) / 1000,
                1 / 20
            );

            lastT = now;

            const steps = Math.max(
                1,
                Math.ceil(dt / 0.004)
            );

            const h = dt / steps;

            for (let i = 0; i < steps; i++) {

                const acc =
                    -OMEGA * OMEGA * (p - target)
                    - 2 * OMEGA * v;

                v += acc * h;
                p += v * h;
            }

            if (p > 1) {
                p = 1;
                v = 0;
            }

            if (p < -1) {
                p = -1;
                v = 0;
            }

            const settled =
                Math.abs(p - target) < 0.0004 &&
                Math.abs(v) < 0.01;

            if (settled) {

                p = target;
                v = 0;

                if (target !== 0) {
                    commit(target);
                }

                render();

                if (queued) {

                    const direction = Math.sign(queued);

                    queued -= direction;

                    step(direction);
                }

                return;
            }

            render();

            raf = requestAnimationFrame(tick);
        }

        /* =====================================================
           COMMIT SLIDE
        ===================================================== */

        function commit(direction) {

            cur = mod(cur + direction);

            p = 0;
            target = 0;

            /* Reset text opacity for future transitions */

            contents.forEach(content => {

                if (content) {
                    content.style.opacity = 1;
                }
            });

            paintDots();

            shown = [-1, -1];
        }

        /* =====================================================
           NAVIGATION
        ===================================================== */

        function step(direction) {

            if (dragging || N < 2) return;

            if (target !== 0 || Math.abs(p) > 0.001) {

                if (
                    Math.sign(direction) === target ||
                    target === 0
                ) {

                    queued = Math.max(
                        -2,
                        Math.min(2, queued + direction)
                    );

                } else {

                    target = 0;
                    startAnim();
                }

                return;
            }

            p = 0.0001 * direction;

            target = direction;

            startAnim();
        }

        function goTo(index) {

            const forward = mod(index - cur);

            if (forward === 0) return;

            step(
                forward <= N / 2 ? 1 : -1
            );
        }

        prevBtn.addEventListener('click', () => step(-1));
        nextBtn.addEventListener('click', () => step(1));

        /* =====================================================
           KEYBOARD NAVIGATION
        ===================================================== */

        root.addEventListener('keydown', event => {

            if (event.key === 'ArrowRight') {

                event.preventDefault();
                step(1);

            } else if (event.key === 'ArrowLeft') {

                event.preventDefault();
                step(-1);
            }
        });

        /* =====================================================
           POINTER DRAGGING
        ===================================================== */

        let startX = 0;
        let startP = 0;
        let samples = [];

        root.addEventListener('pointerdown', event => {

            if (
                event.button !== undefined &&
                event.button !== 0
            ) {
                return;
            }

            if (
                event.target.closest(
                    '.lihi-carousel-nav, .lihi-carousel-dot'
                )
            ) {
                return;
            }

            if (N < 2) return;

            dragging = true;
            queued = 0;

            cancelAnimationFrame(raf);
            raf = 0;

            startX = event.clientX;
            startP = p;

            samples = [{
                t: event.timeStamp,
                x: event.clientX
            }];

            root.classList.add('is-dragging');

            root.setPointerCapture(event.pointerId);
        });

        root.addEventListener('pointermove', event => {

            if (!dragging) return;

            const events = event.getCoalescedEvents
                ? event.getCoalescedEvents()
                : [event];

            for (const ev of (events.length ? events : [event])) {

                samples.push({
                    t: ev.timeStamp,
                    x: ev.clientX
                });
            }

            while (
                samples.length > 2 &&
                event.timeStamp - samples[0].t > 100
            ) {
                samples.shift();
            }

            p = Math.max(
                -1,
                Math.min(
                    1,
                    startP - (event.clientX - startX) / W
                )
            );

            render();
        });

        /* =====================================================
           END DRAG
        ===================================================== */

        function endDrag(event) {

            if (!dragging) return;

            dragging = false;

            root.classList.remove('is-dragging');

            try {
                root.releasePointerCapture(event.pointerId);
            } catch (_) {}

            const first = samples[0];
            const last = samples[samples.length - 1];

            const dtMs = Math.max(
                1,
                last.t - first.t
            );

            v = -(
                (last.x - first.x) / W
            ) / (dtMs / 1000);

            if (!Number.isFinite(v)) {
                v = 0;
            }

            v = Math.max(-6, Math.min(6, v));

            if (
                Math.abs(v) > FLICK_V &&
                Math.sign(v) !== 0
            ) {

                target = Math.sign(v);

            } else if (Math.abs(p) > DRAG_FRAC) {

                target = Math.sign(p);

            } else {

                target = 0;
            }

            if (
                target !== 0 &&
                Math.sign(p) !== 0 &&
                Math.sign(p) !== target &&
                Math.abs(p) > 0.02
            ) {

                target = 0;
            }

            startAnim();
        }

        root.addEventListener('pointerup', endDrag);
        root.addEventListener('pointercancel', endDrag);

        root.addEventListener('dragstart', event => {
            event.preventDefault();
        });

        /* =====================================================
           RESIZE
        ===================================================== */

        if ('ResizeObserver' in window) {

            new ResizeObserver(() => {

                W = Math.max(1, viewport.clientWidth);
                render();

            }).observe(root);

        } else {

            window.addEventListener('resize', () => {

                W = Math.max(1, viewport.clientWidth);
                render();

            });
        }

        /* =====================================================
           INITIALIZE
        ===================================================== */

        paintDots();
        render();
        startAutoplay();
    }

    /* =========================================================
       INITIALIZE ALL CAROUSELS
    ========================================================= */

    function init() {

        document
            .querySelectorAll('.lihi-image-carousel')
            .forEach(initCarousel);
    }

    if (document.readyState === 'loading') {

        document.addEventListener('DOMContentLoaded', init);

    } else {

        init();
    }

})();
