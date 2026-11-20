
document.addEventListener('DOMContentLoaded', () => {

    const button = document.querySelector('.archive-products .load-more-btn');
    const grid = document.querySelector('.archive-products ul.products');
    const wrapper = document.querySelector('.archive-products .load-more-wrapper');
    const status = document.querySelector('.archive-products .load-more-status');

    if (!button || !grid || !wrapper) return;

    let currentPage = Number(button.dataset.page);
    const maxPages = Number(button.dataset.maxPages);
    let loading = false;

    button.addEventListener('click', async () => {

        if (loading || currentPage >= maxPages) return;

        loading = true;
        button.disabled = true;
        button.classList.add('loading');

        if (status) status.textContent = '';

        const nextPage = currentPage + 1;

        const url = new URL(window.location.href);
        url.searchParams.set('paged', nextPage);

        try {
            const response = await fetch(url.toString(), {
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Unable to load next page');
            }

            const html = await response.text();
            const parsedPage = new DOMParser().parseFromString(html, 'text/html');

            const nextGrid = parsedPage.querySelector('.archive-products ul.products');

            if (!nextGrid) {
                throw new Error('Product grid not found');
            }

            const cards = Array.from(nextGrid.children).filter(
                element => element.matches('li.product')
            );

            if (!cards.length) {
                throw new Error('No products returned');
            }

            cards.forEach(card => {
                grid.appendChild(document.importNode(card, true));
            });

            currentPage = nextPage;
            button.dataset.page = String(currentPage);

            if (currentPage >= maxPages) {
                wrapper.remove();
            } else {
                button.disabled = false;
                button.classList.remove('loading');
            }

        } catch (error) {
            console.error('Load More Error:', error);

            button.disabled = false;
            button.classList.remove('loading');
            button.querySelector('.load-more-text').textContent = 'Try Again';

            if (status) {
                status.textContent = 'Unable to load products. Please try again.';
            }
        } finally {
            loading = false;
        }
    });
});
