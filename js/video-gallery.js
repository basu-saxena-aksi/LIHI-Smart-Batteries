
document.addEventListener('DOMContentLoaded', () => {

    'use strict';

    /* =====================================================
       GALLERY ELEMENTS
    ===================================================== */

    const gallery = document.querySelector(
        '.company-video-gallery-section'
    );

    if (!gallery) return;

    const grid = gallery.querySelector(
        '.company-video-gallery-grid'
    );

    if (!grid) return;


    /* =====================================================
       LOAD MORE VIDEOS
    ===================================================== */

    const loadButton = gallery.querySelector(
        '[data-video-gallery-load]'
    );

    const loadWrapper = gallery.querySelector(
        '.load-more-wrapper'
    );

    const galleryItems = Array.from(
        grid.querySelectorAll('.company-video-gallery-item')
    );

    const batchSize = 6;

    let visibleCount = galleryItems.filter(
        item => !item.hidden
    ).length;

    let loading = false;

    if (loadButton && loadWrapper) {

        loadButton.addEventListener('click', () => {

            if (loading || visibleCount >= galleryItems.length) {
                return;
            }

            loading = true;

            loadButton.disabled = true;
            loadButton.classList.add('loading');

            const nextItems = galleryItems.slice(
                visibleCount,
                visibleCount + batchSize
            );

            window.setTimeout(() => {

                nextItems.forEach(item => {
                    item.hidden = false;
                });

                visibleCount += nextItems.length;
                loading = false;

                if (visibleCount >= galleryItems.length) {

                    loadWrapper.remove();

                } else {

                    loadButton.disabled = false;
                    loadButton.classList.remove('loading');

                }

            }, 350);

        });

    }


    /* =====================================================
       LIGHTBOX ELEMENTS
    ===================================================== */

    const lightbox = document.getElementById(
        'companyVideoLightbox'
    );

    if (!lightbox) return;

    const mp4Player = lightbox.querySelector(
        '.company-video-lightbox-player'
    );

    const youtubePlayer = lightbox.querySelector(
        '.company-youtube-lightbox-player'
    );

    const counter = lightbox.querySelector(
        '.company-video-lightbox-counter'
    );

    const closeButton = lightbox.querySelector(
        '.company-video-lightbox-close'
    );

    const prevButton = lightbox.querySelector(
        '.company-video-lightbox-prev'
    );

    const nextButton = lightbox.querySelector(
        '.company-video-lightbox-next'
    );

    if (
        !mp4Player ||
        !youtubePlayer ||
        !counter ||
        !closeButton ||
        !prevButton ||
        !nextButton
    ) {
        console.error(
            'Video gallery: One or more lightbox elements are missing.'
        );
        return;
    }


    /* =====================================================
       COLLECT ALL VIDEOS

       Each wrapper stores:
       data-video-type
       data-video-src
       data-video-title
    ===================================================== */

    const videoWrappers = Array.from(
        grid.querySelectorAll(
            '.company-video-wrapper[data-video-src]'
        )
    );

    const videos = videoWrappers.map(wrapper => ({
        type: wrapper.dataset.videoType,
        src: wrapper.dataset.videoSrc,
        title: wrapper.dataset.videoTitle || 'Company Video'
    }));

    if (!videos.length) return;

    let currentIndex = 0;


    /* =====================================================
       YOUTUBE ID EXTRACTION
    ===================================================== */

    function getYouTubeId(value) {

        if (!value) return '';

        value = String(value).trim();

        // Direct YouTube video ID.
        if (/^[a-zA-Z0-9_-]{11}$/.test(value)) {
            return value;
        }

        try {

            const url = new URL(value);

            const hostname = url.hostname.toLowerCase();

            // Short URL: youtu.be/VIDEO_ID
            if (
                hostname === 'youtu.be' ||
                hostname.endsWith('.youtu.be')
            ) {

                return url.pathname
                    .split('/')
                    .filter(Boolean)[0] || '';

            }

            // Standard YouTube URLs.
            if (
                hostname.includes('youtube.com') ||
                hostname.includes('youtube-nocookie.com')
            ) {

                const queryId = url.searchParams.get('v');

                if (queryId) {
                    return queryId;
                }

                const parts = url.pathname
                    .split('/')
                    .filter(Boolean);

                if (
                    ['embed', 'shorts', 'live'].includes(parts[0])
                ) {
                    return parts[1] || '';
                }

            }

        } catch (error) {

            console.error(
                'Invalid YouTube URL:',
                value,
                error
            );

        }

        return '';

    }


    /* =====================================================
       STOP GALLERY VIDEOS
    ===================================================== */

    function pauseGalleryVideos() {

        grid.querySelectorAll('video').forEach(video => {
            video.pause();
        });

    }


    /* =====================================================
       RESET POPUP PLAYERS
    ===================================================== */

    function resetPlayers() {

        // Stop MP4.
        mp4Player.pause();

        mp4Player.removeAttribute('src');

        mp4Player.load();

        // Stop YouTube.
        youtubePlayer.removeAttribute('src');

    }


    /* =====================================================
       OPEN VIDEO
    ===================================================== */

    function openVideo(index) {

        currentIndex =
            (index + videos.length) % videos.length;

        const selectedVideo = videos[currentIndex];

        if (!selectedVideo) return;


        // Validate YouTube ID before opening.
        let youtubeId = '';

        if (selectedVideo.type === 'youtube') {

            youtubeId = getYouTubeId(selectedVideo.src);

            if (!youtubeId) {

                console.error(
                    'Invalid YouTube video ID:',
                    selectedVideo.src
                );

                return;
            }

        }


        pauseGalleryVideos();

        resetPlayers();


        // Update counter.
        counter.textContent =
            `${currentIndex + 1} / ${videos.length}`;


        // Open lightbox.
        lightbox.classList.add('active');

        lightbox.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'company-video-lightbox-open'
        );


        /* ---------------------------------------------
           YOUTUBE VIDEO
        --------------------------------------------- */

        if (selectedVideo.type === 'youtube') {

            lightbox.classList.add('youtube-active');

            youtubePlayer.title = selectedVideo.title;

            youtubePlayer.setAttribute(
                'allow',
                'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen'
            );

            youtubePlayer.setAttribute(
                'allowfullscreen',
                ''
            );

            const embedUrl =
                `https://www.youtube-nocookie.com/embed/${youtubeId}?autoplay=1&controls=1&rel=0&playsinline=1&fs=1`;

            youtubePlayer.src = embedUrl;

        }


        /* ---------------------------------------------
           MP4 VIDEO
        --------------------------------------------- */

        else {

            lightbox.classList.remove('youtube-active');

            mp4Player.src = selectedVideo.src;

            mp4Player.setAttribute(
                'aria-label',
                selectedVideo.title
            );

            mp4Player.load();

            const playPromise = mp4Player.play();

            if (playPromise !== undefined) {

                playPromise.catch(error => {

                    console.warn(
                        'MP4 autoplay was prevented:',
                        error
                    );

                });

            }

        }

    }


    /* =====================================================
       CLOSE VIDEO
    ===================================================== */

    function closeVideo() {

        resetPlayers();

        lightbox.classList.remove(
            'active',
            'youtube-active'
        );

        lightbox.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'company-video-lightbox-open'
        );

    }


    /* =====================================================
       NEXT / PREVIOUS
    ===================================================== */

    function nextVideo() {

        openVideo(currentIndex + 1);

    }

    function previousVideo() {

        openVideo(currentIndex - 1);

    }


    /* =====================================================
       GALLERY CLICK HANDLING
    ===================================================== */

    grid.addEventListener('click', event => {

        const target = event.target;

        if (!(target instanceof Element)) return;


        const expandButton = target.closest(
            '[data-video-expand]'
        );

        const youtubeThumbnail = target.closest(
            '[data-video-open]'
        );


        // Ignore clicks on MP4 native controls.
        if (!expandButton && !youtubeThumbnail) {
            return;
        }

        event.preventDefault();

        const wrapper = target.closest(
            '.company-video-wrapper'
        );

        if (!wrapper) return;

        const index = videoWrappers.indexOf(wrapper);

        if (index !== -1) {
            openVideo(index);
        }

    });


    /* =====================================================
       YOUTUBE THUMBNAIL KEYBOARD ACCESS
    ===================================================== */

    grid.addEventListener('keydown', event => {

        const target = event.target;

        if (
            target instanceof Element &&
            target.matches('[data-video-open]') &&
            (
                event.key === 'Enter' ||
                event.key === ' '
            )
        ) {

            event.preventDefault();

            const wrapper = target.closest(
                '.company-video-wrapper'
            );

            if (!wrapper) return;

            const index = videoWrappers.indexOf(wrapper);

            if (index !== -1) {
                openVideo(index);
            }

        }

    });


    /* =====================================================
       LIGHTBOX BUTTONS
    ===================================================== */

    closeButton.addEventListener(
        'click',
        closeVideo
    );

    prevButton.addEventListener(
        'click',
        previousVideo
    );

    nextButton.addEventListener(
        'click',
        nextVideo
    );


    /* =====================================================
       CLICK OUTSIDE TO CLOSE
    ===================================================== */

    lightbox.addEventListener('click', event => {

        if (event.target === lightbox) {
            closeVideo();
        }

    });


    /* =====================================================
       KEYBOARD NAVIGATION
    ===================================================== */

    document.addEventListener('keydown', event => {

        if (!lightbox.classList.contains('active')) {
            return;
        }

        if (event.key === 'Escape') {

            closeVideo();

        } else if (event.key === 'ArrowRight') {

            event.preventDefault();
            nextVideo();

        } else if (event.key === 'ArrowLeft') {

            event.preventDefault();
            previousVideo();

        }

    });


    /* =====================================================
       MOBILE SWIPE NAVIGATION
    ===================================================== */

    let touchStartX = 0;
    let touchStartY = 0;

    lightbox.addEventListener('touchstart', event => {

        // Do not interfere with YouTube iframe interaction.
        if (event.target.closest('.company-youtube-lightbox-player')) {
            return;
        }

        touchStartX = event.changedTouches[0].screenX;
        touchStartY = event.changedTouches[0].screenY;

    }, { passive: true });


    lightbox.addEventListener('touchend', event => {

        if (!lightbox.classList.contains('active')) {
            return;
        }

        if (event.target.closest('.company-youtube-lightbox-player')) {
            return;
        }

        const touchEndX = event.changedTouches[0].screenX;
        const touchEndY = event.changedTouches[0].screenY;

        const differenceX = touchEndX - touchStartX;
        const differenceY = touchEndY - touchStartY;

        if (
            Math.abs(differenceX) > 60 &&
            Math.abs(differenceX) > Math.abs(differenceY)
        ) {

            if (differenceX < 0) {
                nextVideo();
            } else {
                previousVideo();
            }

        }

    }, { passive: true });


    console.log(
        'LiHi Video Gallery initialized:',
        videos.length,
        'videos'
    );

});
