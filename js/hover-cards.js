
(() => {
    'use strict';

    /* =====================================================
       1. ANIMATION SETTINGS
    ===================================================== */

    const LIFT_SCALE = 0.10;
    const LIFT_Y = 8;
    const TILT = 7;
    const PUSH = 0.40;

    const SPEED = {
        pos: 12,
        dragPos: 26,
        strength: 9,
        drag: 11,
        tilt: 14
    };

    /* =====================================================
       2. INITIALIZE HOVER CARDS
    ===================================================== */

    const stages = document.querySelectorAll(
        '[data-lihi-hover-cards]'
    );

    stages.forEach(initStage);

    function initStage(stage) {

        const cards = [
            ...stage.querySelectorAll('.lihi-hc__card')
        ];

        if (!cards.length) return;

        const N = cards.length;

        const items = cards.map(card => ({
            c: card,
            shade: card.querySelector('.lihi-hc__shade'),
            sh: card.querySelector('.lihi-hc__shadow'),
            ct: card.querySelector('.lihi-hc__content'),
            rx: 0,
            ry: 0
        }));

        const clamp = (v, a, b) =>
            Math.min(b, Math.max(a, v));

        const sm = t => {
            t = clamp(t, 0, 1);
            return t * t * (3 - 2 * t);
        };

        const reduce = window.matchMedia(
            '(prefers-reduced-motion: reduce)'
        ).matches;

        /* =====================================================
           3. ANIMATION STATE
        ===================================================== */

        const S = {
            pos: 0,
            target: 0,
            s: 0,
            sT: 0,
            drag: 0,
            dragT: 0
        };

        let L = 0;
        let slot = 1;
        let mx = 0;
        let my = 0;

        let dragging = false;
        let x0 = 0;
        let pos0 = 0;
        let raw = 0;
        let lastRaw = 0;
        let lastT = 0;
        let vel = 0;

        let raf = 0;
        let last = 0;

        /* =====================================================
           4. MEASURE CARDS
        ===================================================== */

        const measure = () => {

            const rect = stage.getBoundingClientRect();

            L = rect.left;

            slot = cards[0].getBoundingClientRect().width;
        };

        const idxAt = (x, y) => {

            const hit = document
                .elementFromPoint(x, y)
                ?.closest('.lihi-hc__card');

            if (hit && stage.contains(hit)) {
                return cards.indexOf(hit);
            }

            return clamp(
                Math.floor((x - L) / slot),
                0,
                N - 1
            );
        };

        const kick = () => {

            if (!raf) {

                last = performance.now();

                raf = requestAnimationFrame(frame);
            }
        };

        /* =====================================================
           5. ANIMATION FRAME
        ===================================================== */

        function frame(now) {

            const dt = Math.min(
                (now - last) / 1000,
                0.05
            ) || 0.016;

            last = now;

            const k = rate =>
                reduce
                    ? 1
                    : 1 - Math.exp(-rate * dt);

            S.pos += (
                S.target - S.pos
            ) * k(
                dragging ? SPEED.dragPos : SPEED.pos
            );

            S.s += (
                S.sT - S.s
            ) * k(SPEED.strength);

            S.drag += (
                S.dragT - S.drag
            ) * k(SPEED.drag);

            let moving =
                Math.abs(S.target - S.pos) > 0.0005 ||
                Math.abs(S.sT - S.s) > 0.0005 ||
                Math.abs(S.dragT - S.drag) > 0.0005;

            /* =================================================
               6. CARD LIFT AND SCALE
            ================================================= */

            const p = [];
            const sc = [];
            const off = [];

            for (let i = 0; i < N; i++) {

                const d = S.pos - i;
                const a = Math.abs(d);

                p[i] = S.s * sm(1 - a);

                sc[i] = 1 + LIFT_SCALE * p[i];

                off[i] = 0;
            }

            /* =================================================
               7. PUSH NEIGHBORING CARDS RIGHT
            ================================================= */

            for (let i = 0; i < N - 1; i++) {

                if (
                    cards[i].offsetTop !==
                    cards[i + 1].offsetTop
                ) {
                    continue;
                }

                const edge =
                    (i + 1) * slot +
                    off[i] +
                    (sc[i] - 1) * slot / 2;

                const next =
                    (i + 1) * slot +
                    off[i + 1] -
                    (sc[i + 1] - 1) * slot / 2;

                const overlap = edge - next;

                if (overlap > 0) {

                    off[i + 1] +=
                        overlap *
                        PUSH *
                        sm(
                            0.5 +
                            (p[i] - p[i + 1]) * 2
                        );
                }
            }

            /* =================================================
               8. PUSH NEIGHBORING CARDS LEFT
            ================================================= */

            for (let i = N - 1; i > 0; i--) {

                if (
                    cards[i].offsetTop !==
                    cards[i - 1].offsetTop
                ) {
                    continue;
                }

                const edge =
                    i * slot +
                    off[i] -
                    (sc[i] - 1) * slot / 2;

                const prev =
                    i * slot +
                    off[i - 1] +
                    (sc[i - 1] - 1) * slot / 2;

                const overlap = prev - edge;

                if (overlap > 0) {

                    off[i - 1] -=
                        overlap *
                        PUSH *
                        sm(
                            0.5 +
                            (p[i] - p[i - 1]) * 2
                        );
                }
            }

            /* =================================================
               9. APPLY CARD TRANSFORMS
            ================================================= */

            const lean = clamp(
                S.target - S.pos,
                -1,
                1
            ) * 6 * S.drag;

            for (let i = 0; i < N; i++) {

                const item = items[i];

                const rect =
                    item.c.getBoundingClientRect();

                const cx =
                    rect.left + rect.width / 2;

                const nx = clamp(
                    (mx - cx) / (slot / 2),
                    -1,
                    1
                );

                const ny = clamp(
                    (
                        my -
                        (rect.top + rect.height / 2)
                    ) / (slot / 2),
                    -1,
                    1
                );

                const weight =
                    p[i] > 0.02 ? p[i] : 0;

                const targetRY =
                    nx * TILT * weight -
                    lean * weight;

                const targetRX =
                    -ny * TILT * weight;

                item.ry += (
                    targetRY - item.ry
                ) * k(SPEED.tilt);

                item.rx += (
                    targetRX - item.rx
                ) * k(SPEED.tilt);

                if (
                    Math.abs(targetRY - item.ry) > 0.01 ||
                    Math.abs(targetRX - item.rx) > 0.01
                ) {
                    moving = true;
                }

                item.c.style.transform =
                    `translate3d(
                        ${off[i].toFixed(2)}px,
                        ${(-LIFT_Y * p[i]).toFixed(2)}px,
                        0
                    ) ` +
                    `perspective(900px) ` +
                    `rotateX(${item.rx.toFixed(3)}deg) ` +
                    `rotateY(${item.ry.toFixed(3)}deg) ` +
                    `scale(${sc[i].toFixed(4)})`;

                item.c.style.zIndex =
                    Math.round(p[i] * 100);

                item.shade.style.opacity =
                    (0.58 * (1 - p[i])).toFixed(3);

                item.sh.style.opacity =
                    (0.6 * p[i]).toFixed(3);

                item.ct.style.opacity =
                    (0.65 + 0.35 * p[i]).toFixed(3);
            }

            raf = (moving || dragging)
                ? requestAnimationFrame(frame)
                : 0;
        }

        /* =====================================================
           10. POINTER ENTER
        ===================================================== */

        stage.addEventListener('pointerenter', event => {

            measure();

            if (event.pointerType === 'mouse') {

                S.sT = 1;

                S.target = idxAt(
                    event.clientX,
                    event.clientY
                );
            }

            mx = event.clientX;
            my = event.clientY;

            kick();
        });

        /* =====================================================
           11. POINTER MOVE
        ===================================================== */

        stage.addEventListener('pointermove', event => {

            mx = event.clientX;
            my = event.clientY;

            if (dragging) {

                raw = pos0 + (mx - x0) / slot;

                const over =
                    raw < 0
                        ? raw
                        : raw > N - 1
                            ? raw - (N - 1)
                            : 0;

                const target = over
                    ? (raw - over) + over * 0.3
                    : raw;

                S.target = target;

                const now = performance.now();

                const dtv = (now - lastT) / 1000;

                if (dtv > 0.004) {

                    vel =
                        vel * 0.7 +
                        ((raw - lastRaw) / dtv) * 0.3;

                    lastRaw = raw;
                    lastT = now;
                }

            } else if (event.pointerType === 'mouse') {

                S.sT = 1;

                S.target = idxAt(mx, my);
            }

            kick();
        });

        /* =====================================================
           12. POINTER DOWN / DRAG START
        ===================================================== */

        stage.addEventListener('pointerdown', event => {

            measure();

            dragging = true;

            stage.setPointerCapture(event.pointerId);

            mx = event.clientX;
            my = event.clientY;

            if (event.pointerType !== 'mouse') {

                S.target = idxAt(mx, my);
                S.sT = 1;
            }

            pos0 = S.target;
            x0 = mx;

            raw = pos0;
            lastRaw = pos0;

            lastT = performance.now();

            vel = 0;

            S.dragT = 1;

            stage.classList.add('is-drag');

            kick();
        });

        /* =====================================================
           13. POINTER RELEASE / SNAP
        ===================================================== */

        const release = event => {

            if (!dragging) return;

            dragging = false;

            stage.classList.remove('is-drag');

            S.target = clamp(
                Math.round(raw + vel * 0.15),
                0,
                N - 1
            );

            S.dragT = 0;

            if (event.pointerType === 'mouse') {

                const rect =
                    stage.getBoundingClientRect();

                if (
                    event.clientX < rect.left ||
                    event.clientX > rect.right ||
                    event.clientY < rect.top ||
                    event.clientY > rect.bottom
                ) {
                    S.sT = 0;
                }
            }

            kick();
        };

        stage.addEventListener('pointerup', release);

        stage.addEventListener('pointercancel', release);

        /* =====================================================
           14. POINTER LEAVE
        ===================================================== */

        stage.addEventListener('pointerleave', event => {

            if (
                event.pointerType === 'mouse' &&
                !dragging
            ) {
                S.sT = 0;
                kick();
            }
        });

        /* =====================================================
           15. RESIZE HANDLING
        ===================================================== */

        window.addEventListener('resize', () => {

            measure();
            kick();
        });

        /* =====================================================
           16. INITIALIZE
        ===================================================== */

        measure();
        kick();
    }

})();
