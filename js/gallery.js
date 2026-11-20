document.addEventListener('DOMContentLoaded', () => {

    const gallery = document.querySelector('.company-gallery-section');

    if (!gallery) return;

    const grid = gallery.querySelector('.company-gallery-grid');
    const button = gallery.querySelector('[data-gallery-load]');
    const wrapper = gallery.querySelector('.load-more-wrapper');

    if (!grid || !button || !wrapper) return;

    const items = Array.from(
        grid.querySelectorAll('.company-gallery-item')
    );

    const batchSize = 6;

    let visibleCount = items.filter(
        item => !item.hidden
    ).length;

    let loading = false;

    button.addEventListener('click', () => {

        if (loading || visibleCount >= items.length) return;

        loading = true;

        button.disabled = true;
        button.classList.add('loading');

        const nextItems = items.slice(
            visibleCount,
            visibleCount + batchSize
        );

        window.setTimeout(() => {

            nextItems.forEach(item => {
                item.hidden = false;
            });

            visibleCount += nextItems.length;

            loading = false;

            if (visibleCount >= items.length) {
                wrapper.remove();
            } else {
                button.disabled = false;
                button.classList.remove('loading');
            }

        }, 350);

    });

});