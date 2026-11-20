/* global Lenis, gsap, ScrollTrigger */

(() => {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {

        if (typeof Lenis === 'undefined') return;

        const lenis = new Lenis({
            duration: 0.8,
            easing: (t) => 1 - Math.pow(1 - t, 3),
            smoothWheel: true,
            wheelMultiplier: 0.9,
            touchMultiplier: 1,
            syncTouch: false,
            anchors: true,
            autoRaf: false
        });

        window.lihiLenis = lenis;

        if (document.getElementById('lihiPreloader')) {
            lenis.stop();
        }

        /* ==========================================
           GSAP INTEGRATION
        ========================================== */

        if (typeof gsap !== 'undefined') {

            if (typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);

                lenis.on('scroll', ScrollTrigger.update);
            }

            gsap.ticker.add((time) => {
                lenis.raf(time * 1000);
            });

            gsap.ticker.lagSmoothing(0);

        } else {

            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }

            requestAnimationFrame(raf);
        }

        window.lihiLenis = lenis;

    });

})();