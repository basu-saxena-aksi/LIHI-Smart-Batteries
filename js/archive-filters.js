
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.archive-filters');
    if (!sidebar) return;

    const openButton = document.querySelector('.archive-filter-open');
    const closeButton = sidebar.querySelector('.archive-filter-close');
    const applyButton = sidebar.querySelector('.archive-filter-apply');
    const backdrop = document.querySelector('.archive-filter-backdrop');

    const fixedOpenFilters = new Set(['application']);

    const normalize = text =>
        text.replace(/\s+/g, ' ').trim().toLowerCase();

    const getSections = () =>
        Array.from(sidebar.querySelectorAll('.wpc-filters-section'));

    function getContent(section) {
        return section.querySelector(
            '.wpc-filter-content, .wpc-filter-content-wrapper'
        );
    }

    function getFilterName(section) {
        const title = section.querySelector('.wpc-filter-title');
        if (!title) return '';

        const button = title.querySelector('.archive-filter-toggle');

        return normalize(
            button ? button.textContent : title.textContent
        );
    }

    function isFixedOpen(section) {
        return fixedOpenFilters.has(getFilterName(section));
    }

    function setExpanded(section, expanded) {
        const button = section.querySelector('.archive-filter-toggle');
        const content = getContent(section);

        if (!button || !content) return;

        if (isFixedOpen(section)) {
            expanded = true;
        }

        button.setAttribute('aria-expanded', String(expanded));

        section.classList.toggle(
            'lihi-filter-collapsed',
            !expanded
        );

        content.hidden = false;
        content.removeAttribute('hidden');
        content.style.removeProperty('display');
    }

    function initializeFilters() {
        getSections().forEach(section => {
            const title = section.querySelector('.wpc-filter-title');
            const content = getContent(section);

            if (!title || !content) return;

            const existingButton = title.querySelector(
                '.archive-filter-toggle'
            );

            const heading = normalize(
                existingButton
                    ? existingButton.textContent
                    : title.textContent
            );

            const alwaysOpen = fixedOpenFilters.has(heading);

            if (!existingButton) {
                const button = document.createElement('button');

                button.type = 'button';
                button.className = 'archive-filter-toggle';

                const label = document.createElement('span');
                label.textContent = title.textContent.trim();

                const arrow = document.createElement('span');
                arrow.className = 'archive-filter-arrow';
                arrow.setAttribute('aria-hidden', 'true');

                button.append(label, arrow);
                title.replaceChildren(button);
            }

            section.classList.toggle(
                'lihi-filter-always-open',
                alwaysOpen
            );

            if (section.dataset.lihiDropdown !== 'ready') {
                const hasSelection = !!section.querySelector(
                    '.wpc-term-selected, input:checked'
                );

                setExpanded(
                    section,
                    alwaysOpen || hasSelection
                );

                section.dataset.lihiDropdown = 'ready';
            } else if (alwaysOpen) {
                setExpanded(section, true);
            }
        });

        // Move Application to the first position.
        const applicationSection = getSections().find(section =>
            isFixedOpen(section)
        );

        if (applicationSection) {
            const firstSection = getSections()[0];

            if (
                firstSection &&
                firstSection !== applicationSection &&
                firstSection.parentElement === applicationSection.parentElement
            ) {
                firstSection.parentElement.insertBefore(
                    applicationSection,
                    firstSection
                );
            }
        }
    }

    // Collapsible filter sections.
    sidebar.addEventListener('click', event => {
        const button = event.target.closest('.archive-filter-toggle');

        if (!button || !sidebar.contains(button)) return;

        event.preventDefault();

        const section = button.closest('.wpc-filters-section');
        if (!section) return;

        if (isFixedOpen(section)) return;

        const expanded =
            button.getAttribute('aria-expanded') === 'true';

        setExpanded(section, !expanded);
    });

    initializeFilters();

    // Reinitialize custom headings after Filter Everything AJAX updates.
    let scheduled = false;

    const filterObserver = new MutationObserver(() => {
        if (scheduled) return;

        scheduled = true;

        requestAnimationFrame(() => {
            scheduled = false;
            initializeFilters();
        });
    });

    filterObserver.observe(sidebar, {
        childList: true,
        subtree: true
    });

    // Drawer open and close.
    function openDrawer() {
        if (!openButton || !backdrop) return;

        sidebar.classList.add('is-open');
        backdrop.classList.add('is-visible');
        document.body.classList.add('lihi-filter-drawer-open');

        openButton.setAttribute('aria-expanded', 'true');

        if (closeButton) {
            closeButton.focus();
        }
    }

    function closeDrawer() {
        if (!openButton || !backdrop) return;

        sidebar.classList.remove('is-open');
        backdrop.classList.remove('is-visible');
        document.body.classList.remove('lihi-filter-drawer-open');

        openButton.setAttribute('aria-expanded', 'false');
        openButton.focus();
    }

    if (openButton) {
        openButton.addEventListener('click', openDrawer);
    }

    if (closeButton) {
        closeButton.addEventListener('click', closeDrawer);
    }

    if (applyButton) {
        applyButton.addEventListener('click', closeDrawer);
    }

    if (backdrop) {
        backdrop.addEventListener('click', closeDrawer);
    }

    document.addEventListener('keydown', event => {
        if (
            event.key === 'Escape' &&
            sidebar.classList.contains('is-open')
        ) {
            closeDrawer();
        }
    });

    // Close the drawer if the viewport returns to desktop width.
    const desktopQuery = window.matchMedia('(min-width: 1025px)');

    desktopQuery.addEventListener('change', event => {
        if (event.matches) {
            sidebar.classList.remove('is-open');

            if (backdrop) {
                backdrop.classList.remove('is-visible');
            }

            document.body.classList.remove('lihi-filter-drawer-open');

            if (openButton) {
                openButton.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // Custom Reset All — remove filters through the plugin's AJAX chip action.
    const resetButton = sidebar.querySelector('.archive-custom-reset');

    if (resetButton) {
        let isResetting = false;

        const getActiveChips = () =>
            Array.from(
                sidebar.querySelectorAll(
                    '.wpc-filter-chip:not(.wpc-chip-reset-all) [data-wpc-span-link]'
                )
            );

        function waitForChipRemoval(previousCount) {
            return new Promise(resolve => {
                const observer = new MutationObserver(() => {
                    if (getActiveChips().length < previousCount) {
                        observer.disconnect();
                        clearTimeout(timeout);
                        resolve(true);
                    }
                });

                observer.observe(sidebar, {
                    childList: true,
                    subtree: true
                });

                const timeout = setTimeout(() => {
                    observer.disconnect();
                    resolve(false);
                }, 5000);
            });
        }

        resetButton.addEventListener('click', async event => {
            event.preventDefault();
            event.stopImmediatePropagation();

            if (isResetting) return;

            isResetting = true;
            resetButton.style.pointerEvents = 'none';

            try {
                while (true) {
                    const chips = getActiveChips();

                    if (!chips.length) break;

                    const previousCount = chips.length;
                    const removal = waitForChipRemoval(previousCount);

                    chips[0].click();

                    const removed = await removal;

                    if (!removed) break;
                }
            } finally {
                isResetting = false;
                resetButton.style.pointerEvents = '';
            }
        });
    }
});
