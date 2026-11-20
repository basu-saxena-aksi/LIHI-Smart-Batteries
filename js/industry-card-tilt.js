
/* =========================================================
   LIHI - INDUSTRY CARD 3D TILT
========================================================= */

document.addEventListener('DOMContentLoaded', () => {

  const cards = document.querySelectorAll(
    '.industry-card, .application-card , .article-card'
);

    if (!cards.length) return;

    const threshold = 4;

    // Disable tilt for touch devices and reduced-motion preferences.
    if (
        window.matchMedia('(hover: none)').matches ||
        window.matchMedia('(prefers-reduced-motion: reduce)').matches
    ) {
        return;
    }

    cards.forEach((card) => {

        card.addEventListener('pointermove', (event) => {

            const rect = card.getBoundingClientRect();

            const x = (event.clientX - rect.left) / rect.width - 0.5;
            const y = (event.clientY - rect.top) / rect.height - 0.5;

            const rotateX = y * -threshold;
            const rotateY = x * threshold;

            card.style.transform = `
                perspective(1000px)
                rotateX(${rotateX}deg)
                rotateY(${rotateY}deg)
            `;

        });

        card.addEventListener('pointerleave', () => {

            card.style.transform =
                'perspective(1000px) rotateX(0deg) rotateY(0deg)';

        });

    });

});
