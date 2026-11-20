
document.addEventListener('DOMContentLoaded', () => {

    const lightbox = document.getElementById('galleryLightbox');

    if (!lightbox) return;

    const galleryLinks = document.querySelectorAll('[data-gallery-image]');

    const image = lightbox.querySelector('.gallery-lightbox-image');
    const counter = lightbox.querySelector('.gallery-lightbox-counter');

    const closeButton = lightbox.querySelector('.gallery-lightbox-close');
    const prevButton = lightbox.querySelector('.gallery-lightbox-prev');
    const nextButton = lightbox.querySelector('.gallery-lightbox-next');

    if (!galleryLinks.length || !image || !closeButton || !prevButton || !nextButton) {
        return;
    }

    // Collect all gallery images, including hidden Load More images.
    const images = Array.from(galleryLinks).map(link => ({
        src: link.dataset.imageSrc,
        alt: link.querySelector('img')?.alt || 'Gallery image'
    }));

    let currentIndex = 0;
    let touchStartX = 0;
    let touchStartY = 0;

    // Display selected image.
    function showImage(index) {

        currentIndex = (index + images.length) % images.length;

        image.src = images[currentIndex].src;
        image.alt = images[currentIndex].alt;

        if (counter) {
            counter.textContent = `${currentIndex + 1} / ${images.length}`;
        }

    }

    // Open lightbox.
    function openLightbox(index) {

        showImage(index);

        lightbox.classList.add('active');
        lightbox.setAttribute('aria-hidden', 'false');

        document.body.classList.add('gallery-lightbox-open');

        closeButton.focus();

    }

    // Close lightbox.
    function closeLightbox() {

        lightbox.classList.remove('active');
        lightbox.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('gallery-lightbox-open');

        image.src = '';

    }

    // Next image.
    function nextImage() {
        showImage(currentIndex + 1);
    }

    // Previous image.
    function prevImage() {
        showImage(currentIndex - 1);
    }


    // Open image when clicked.
    galleryLinks.forEach((link, index) => {

        link.addEventListener('click', event => {

            event.preventDefault();

            openLightbox(index);

        });

    });


    // Close button.
    closeButton.addEventListener('click', event => {
        event.preventDefault();
        closeLightbox();
    });


    // Previous and next buttons.
    prevButton.addEventListener('click', event => {
        event.preventDefault();
        prevImage();
    });

    nextButton.addEventListener('click', event => {
        event.preventDefault();
        nextImage();
    });


    // Close when clicking outside the image.
    lightbox.addEventListener('click', event => {

        if (event.target === lightbox) {
            closeLightbox();
        }

    });


    // Keyboard navigation.
    document.addEventListener('keydown', event => {

        if (!lightbox.classList.contains('active')) return;

        if (event.key === 'Escape') {
            closeLightbox();
        }

        if (event.key === 'ArrowRight') {
            nextImage();
        }

        if (event.key === 'ArrowLeft') {
            prevImage();
        }

    });


    // Mobile swipe navigation.
    lightbox.addEventListener('touchstart', event => {

        touchStartX = event.changedTouches[0].screenX;
        touchStartY = event.changedTouches[0].screenY;

    }, { passive: true });


    lightbox.addEventListener('touchend', event => {

        const touchEndX = event.changedTouches[0].screenX;
        const touchEndY = event.changedTouches[0].screenY;

        const differenceX = touchEndX - touchStartX;
        const differenceY = touchEndY - touchStartY;

        if (
            Math.abs(differenceX) > 50 &&
            Math.abs(differenceX) > Math.abs(differenceY)
        ) {

            if (differenceX < 0) {
                nextImage();
            } else {
                prevImage();
            }

        }

    }, { passive: true });

    const lightboxContent = lightbox.querySelector('.gallery-lightbox-content');
    // Mouse drag navigation.
    let isDragging = false;
    let dragStartX = 0;
    let dragDistance = 0;

    lightboxContent.addEventListener('mousedown', event => {

        if (event.target.closest('.gallery-lightbox-arrow, .gallery-lightbox-close')) {
            return;
        }

        isDragging = true;
        dragStartX = event.clientX;
        dragDistance = 0;

        lightboxContent.classList.add('dragging');

    });

    document.addEventListener('mousemove', event => {

        if (!isDragging) return;

        dragDistance = event.clientX - dragStartX;

    });

    document.addEventListener('mouseup', () => {

        if (!isDragging) return;

        isDragging = false;

        lightboxContent.classList.remove('dragging');

        if (dragDistance < -70) {
            nextImage();
        } else if (dragDistance > 70) {
            prevImage();
        }

        dragDistance = 0;

    });

    
});
