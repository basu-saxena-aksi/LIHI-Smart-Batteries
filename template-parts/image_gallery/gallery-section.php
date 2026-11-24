<?php

$gallery_images = [
    'gallery-01.webp',
    'gallery-02.jfif',
    'gallery-03.webp',
    'gallery-04.jfif',
    'gallery-05.jfif',
    'gallery-06.jfif',
    'gallery-07.jpg',
    'gallery-08.jpg',
    'gallery-09.jpg',
    'gallery-10.jpg',
    'gallery-11.jpg',
    'gallery-12.jpg',
    'gallery-13.jpg',
    'gallery-14.jpg',
    'gallery-15.jpg',
];

$gallery_base = get_template_directory_uri() . '/images/gallery/';

?>

<section class="company-gallery-section">
    <div class="wrap">

        <div class="company-gallery-header">

            <span class="eyebrow">OUR IMAGES</span>

            <h2>
                Explore Our <span>Image Gallery</span>
            </h2>

            <p>
                Discover our company, products and technology through our images.
            </p>

        </div>

        <!-- Your gallery heading -->

        <div class="company-gallery-grid">

            <?php foreach ($gallery_images as $index => $image): ?>

                <div class="company-gallery-item" <?php echo $index >= 6 ? 'hidden' : ''; ?>>

                    <a href="#" class="company-gallery-link" data-gallery-image
                        data-image-src="<?php echo esc_url($gallery_base . $image); ?>"
                        data-gallery-index="<?php echo esc_attr($index); ?>"
                        aria-label="<?php echo esc_attr('View gallery image ' . ($index + 1)); ?>">

                        <img src="<?php echo esc_url($gallery_base . $image); ?>"
                            alt="<?php echo esc_attr('LiHi Smart Batteries company gallery image ' . ($index + 1)); ?>"
                            loading="lazy">

                        <span class="gallery-image-icon" aria-hidden="true">
                            ↗
                        </span>

                    </a>

                </div>

            <?php endforeach; ?>

        </div>

        <?php if (count($gallery_images) > 6): ?>

            <div class="load-more-wrapper">

                <button type="button" class="link-btn load-more-btn" data-gallery-load>
                    <span class="load-more-text">Load More Images</span>
                    <svg width="15" height="10" viewBox="0 0 13 10" aria-hidden="true">
                        <path d="M1,5 L11,5"></path>
                        <polyline points="8 1 12 5 8 9"></polyline>
                    </svg>

                    <span class="loader" aria-hidden="true">
                        <span class="bar1"></span>
                        <span class="bar2"></span>
                        <span class="bar3"></span>
                        <span class="bar4"></span>
                        <span class="bar5"></span>
                        <span class="bar6"></span>
                    </span>
                </button>

            </div>

        <?php endif; ?>

    </div>
</section>

<!-- Gallery Lightbox -->
<div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true">

    <button type="button" class="gallery-lightbox-close" aria-label="Close gallery">
        &times;
    </button>

    <button type="button" class="gallery-lightbox-arrow gallery-lightbox-prev" aria-label="Previous image">
        &#10094;
    </button>

    <div class="gallery-lightbox-content">
        <img src="" alt="" class="gallery-lightbox-image" draggable="false">
        <span class="gallery-lightbox-counter"></span>
    </div>

    <button type="button" class="gallery-lightbox-arrow gallery-lightbox-next" aria-label="Next image">
        &#10095;
    </button>

</div>