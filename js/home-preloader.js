/* =========================================================
   LIHI HOMEPAGE PRELOADER
   Rolling Digits + Charging Fill + Smooth Exit
========================================================= */

(() => {
    'use strict';

    const loader = document.getElementById('lihiPreloader');
    if (!loader) return;

    const counter = document.getElementById('lihiPreloaderCounter');
    const outline = document.getElementById('lihiPreloaderOutline');
    const fill = document.getElementById('lihiPreloaderFill');

    if (!counter || !outline || !fill) return;

    /* =====================================================
       CONFIGURATION
    ===================================================== */

    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches;

    const duration = reducedMotion ? 300 : 2000;
    const holdDuration = reducedMotion ? 0 : 100;

    const digitCount = 3;
    const reelItems = 11; // 0 through 9, then 0

    let startTime = null;
    let counterFinished = false;
    let pageLoaded = document.readyState === 'complete';
    let closing = false;

    let digitHeight = 0;

    const columns = [];

    /* =====================================================
       BUILD DIGIT REELS
    ===================================================== */

    for (let c = 0; c < digitCount; c++) {

        const pair = [];

        [outline, fill].forEach(layer => {

            const col = document.createElement('div');
            col.className = 'lihi-preloader__col';

            const reel = document.createElement('div');
            reel.className = 'lihi-preloader__reel';

            for (let i = 0; i < reelItems; i++) {

                const item = document.createElement('div');
                item.className = 'lihi-preloader__item';

                item.textContent = String(i % 10);

                reel.appendChild(item);
            }

            col.appendChild(reel);
            layer.appendChild(col);

            pair.push(reel);
        });

        columns.push(pair);
    }

    /* =====================================================
       MEASURE REEL HEIGHT
    ===================================================== */

    function measure() {

        const item = outline.querySelector(
            '.lihi-preloader__item'
        );

        if (!item) return;

        digitHeight = item.getBoundingClientRect().height;

        const style = getComputedStyle(counter);
        const fontSize = parseFloat(style.fontSize);

        const ctx = document.createElement('canvas').getContext('2d');
        ctx.font = `${style.fontWeight} ${fontSize}px ${style.fontFamily}`;

        const m = ctx.measureText('0');

        const contentHeight =
            m.fontBoundingBoxAscent + m.fontBoundingBoxDescent;

        // Baseline position inside the 1em line box
        const baseline =
            (digitHeight - contentHeight) / 2 + m.fontBoundingBoxAscent;

        const glyphTop = baseline - m.actualBoundingBoxAscent;
        const glyphBottom = baseline + m.actualBoundingBoxDescent;

        // Small safety margin so digits like 1 or 7 never get clipped
        const pad = 2;

        const clip = `inset(${Math.max(0, glyphTop - pad)}px 0 ${Math.max(0, digitHeight - glyphBottom - pad)}px 0)`;

        document.querySelectorAll('.lihi-preloader__col').forEach(col => {
            col.style.clipPath = clip;
        });
    }

    /* =====================================================
       EASING
    ===================================================== */

    function ease(t) {

        return t < 0.5
            ? 2 * t * t
            : 1 - Math.pow(-2 * t + 2, 2) / 2;
    }

    /* =====================================================
       UPDATE ROLLING DIGITS
    ===================================================== */

    function updateCounter(value) {

        value = Math.max(1, Math.min(100, value));

        // The final roll happens only from 099 to 100.
        const finalProgress = Math.max(
            0,
            Math.min(1, value - 99)
        );

        for (let c = 0; c < digitCount; c++) {

            let position;

            if (c === 0) {

                // Hundreds digit stays at 0 until 099.
                // Then rolls upward from 0 to 1.
                position = finalProgress;

            } else if (value > 99) {

                // Tens and ones roll upward from 9 to 0.
                position = 9 + finalProgress;

            } else {

                const divisor = Math.pow(
                    10,
                    digitCount - c - 1
                );

                position = (value / divisor) % 10;
            }

            const offset = -position * digitHeight;

            columns[c].forEach(reel => {
                reel.style.transform =
                    `translate3d(0, ${offset}px, 0)`;
            });
        }

        fill.style.clipPath =
            `inset(${100 - value}% 0 0 0)`;
    }
    /* =====================================================
   FINISH PRELOADER
===================================================== */

function finish() {

    if (
        closing ||
        !counterFinished ||
        !pageLoaded
    ) {
        return;
    }

    closing = true;

    updateCounter(100);

    window.setTimeout(() => {

        loader.addEventListener(
            'transitionend',
            event => {

                if (
                    event.target !== loader ||
                    event.propertyName !== 'transform'
                ) {
                    return;
                }

                // Remove preloader after slide-up completes
                loader.remove();

                // Start GSAP hero animation
                window.dispatchEvent(
                    new Event('lihi:preloader-complete')
                );

            }
        );

        loader.classList.add('is-hidden');

        document.documentElement.classList.remove(
            'lihi-is-loading'
        );

        if (window.lihiLenis) {
            window.lihiLenis.start();
        }

    }, holdDuration);
}
    /* =====================================================
       COUNTER ANIMATION
    ===================================================== */

    function frame(timestamp) {

        if (startTime === null) {
            startTime = timestamp;
        }

        const progress = Math.min(
            (timestamp - startTime) / duration,
            1
        );

        const value = 1 + ease(progress) * 99;

        updateCounter(value);

        if (progress < 1) {

            requestAnimationFrame(frame);

        } else {

            counterFinished = true;
            finish();
        }
    }

    /* =====================================================
       PAGE LOADING
    ===================================================== */

    if (!pageLoaded) {

        window.addEventListener(
            'load',
            () => {
                pageLoaded = true;
                finish();
            },
            { once: true }
        );
    }

    /* =====================================================
       INITIALIZE
    ===================================================== */

    document.documentElement.classList.add(
        'lihi-is-loading'
    );

    if (window.lihiLenis) {
        window.lihiLenis.stop();
    }

    measure();
    updateCounter(1);

    window.addEventListener('resize', () => {
        measure();
    });

    // Wait for web fonts so the reels use correct dimensions.
    const fontsReady = document.fonts
        ? document.fonts.ready
        : Promise.resolve();

    fontsReady.then(() => {

        measure();

        requestAnimationFrame(frame);
    });

})();