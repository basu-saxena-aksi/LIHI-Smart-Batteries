
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.archive-filters');
    if (!sidebar) return;

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

        // Application must always stay open.
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

            // Hide dropdown arrow for Application.
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

    sidebar.addEventListener('click', event => {
        const button = event.target.closest('.archive-filter-toggle');

        if (!button || !sidebar.contains(button)) return;

        event.preventDefault();

        const section = button.closest('.wpc-filters-section');
        if (!section) return;

        // Prevent Application from collapsing.
        if (isFixedOpen(section)) return;

        const expanded =
            button.getAttribute('aria-expanded') === 'true';

        setExpanded(section, !expanded);
    });

    initializeFilters();

    // Handle Filter Everything AJAX updates.
    let scheduled = false;

    const observer = new MutationObserver(() => {
        if (scheduled) return;

        scheduled = true;

        requestAnimationFrame(() => {
            scheduled = false;
            initializeFilters();
        });
    });

    observer.observe(sidebar, {
        childList: true,
        subtree: true
    });
});
