<?php
$script_path = get_template_directory() . '/js/video-gallery.js';

wp_enqueue_script(
    'lihi-video-gallery',
    get_template_directory_uri() . '/js/video-gallery.js',
    [],
    file_exists($script_path) ? filemtime($script_path) : '1.0.0',
    true
);
?>
<?php
/**
 * Company Video Gallery
 */

// MP4 videos and YouTube videos.
$gallery_videos = [

    // [
    //     'type'  => 'mp4',
    //     'file'  => 'video-01.mp4',
    //     'title' => 'LiHi Company Video 1',
    // ],

    [
        'type'  => 'mp4',
        'file'  => 'video-02.mp4',
        'title' => 'LiHi Company Video 2',
    ],

    [
        'type'  => 'youtube',
        'url'   => 'https://youtu.be/vaNE9mXGha8?si=1jDeaiKGe4wQa5O_',
        'title' => 'LiHi Company Video 3',
    ],

    // Add more videos here.
];

$video_base = get_template_directory_uri() . '/videos/gallery/';

/**
 * Extract a YouTube video ID from supported URL formats.
 */
$get_youtube_id = function ($url) {

    $url = trim((string) $url);

    // Accept a video ID directly.
    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
        return $url;
    }

    $parsed_url = wp_parse_url($url);

    if (empty($parsed_url['host'])) {
        return '';
    }

    $host = strtolower($parsed_url['host']);
    $path = isset($parsed_url['path']) ? trim($parsed_url['path'], '/') : '';

    // Short URL: youtu.be/VIDEO_ID
    if (strpos($host, 'youtu.be') !== false) {
        $parts = explode('/', $path);
        $video_id = $parts[0] ?? '';
    }

    // Standard YouTube URLs.
    elseif (
        strpos($host, 'youtube.com') !== false ||
        strpos($host, 'youtube-nocookie.com') !== false
    ) {

        parse_str($parsed_url['query'] ?? '', $query);

        if (!empty($query['v'])) {
            $video_id = $query['v'];
        } else {
            $parts = explode('/', $path);

            if (
                isset($parts[0]) &&
                in_array($parts[0], ['embed', 'shorts', 'live'], true)
            ) {
                $video_id = $parts[1] ?? '';
            } else {
                $video_id = '';
            }
        }
    }

    else {
        $video_id = '';
    }

    return preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_id)
        ? $video_id
        : '';
};


// Prepare valid gallery entries.
$valid_videos = [];

foreach ($gallery_videos as $video) {

    $type = $video['type'] ?? '';
    $title = $video['title'] ?? 'Company Video';

    if ($type === 'youtube') {

        $youtube_id = $get_youtube_id($video['url'] ?? '');

        if (!$youtube_id) {
            continue;
        }

        $valid_videos[] = [
            'type'  => 'youtube',
            'src'   => $youtube_id,
            'title' => $title,
        ];

    } elseif ($type === 'mp4' && !empty($video['file'])) {

        $valid_videos[] = [
            'type'  => 'mp4',
            'src'   => $video_base . ltrim($video['file'], '/'),
            'title' => $title,
        ];
    }
}

?>

<section class="company-video-gallery-section">

    <div class="wrap">

        <div class="company-video-gallery-header">

            <span class="eyebrow">OUR VIDEOS</span>

            <h2>
                Explore Our <span>Video Gallery</span>
            </h2>

            <p>
                Discover our company, products and technology through our videos.
            </p>

        </div>


        <?php if (!empty($valid_videos)) : ?>

            <div class="company-video-gallery-grid">

                <?php foreach ($valid_videos as $index => $video) : ?>

                    <div
                        class="company-video-gallery-item"
                        <?php echo $index >= 6 ? 'hidden' : ''; ?>
                    >

                        <div
                            class="company-video-wrapper"
                            data-video-type="<?php echo esc_attr($video['type']); ?>"
                            data-video-src="<?php echo esc_attr($video['src']); ?>"
                            data-video-title="<?php echo esc_attr($video['title']); ?>"
                        >

                            <?php if ($video['type'] === 'youtube') : ?>

                                <button
                                    type="button"
                                    class="company-youtube-thumbnail"
                                    data-video-open
                                    aria-label="<?php echo esc_attr('Play ' . $video['title']); ?>"
                                >

                                    <img
                                        src="<?php echo esc_url('https://img.youtube.com/vi/' . $video['src'] . '/hqdefault.jpg'); ?>"
                                        alt="<?php echo esc_attr($video['title']); ?>"
                                        loading="lazy"
                                    >

                                    <span class="company-youtube-play" aria-hidden="true">
                                        <svg
                                            width="28"
                                            height="28"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path d="M8 5.14v13.72c0 .78.85 1.26 1.52.86l11-6.86a1 1 0 0 0 0-1.72l-11-6.86A1 1 0 0 0 8 5.14Z"/>
                                        </svg>
                                    </span>

                                </button>

                            <?php else : ?>

                                <video
                                    controls
                                    preload="metadata"
                                    playsinline
                                    muted
                                    loop
                                    aria-label="<?php echo esc_attr($video['title']); ?>"
                                >
                                    <source
                                        src="<?php echo esc_url($video['src']); ?>"
                                        type="video/mp4"
                                    >
                                    Your browser does not support HTML5 video.
                                </video>

                            <?php endif; ?>


                            <button
                                type="button"
                                class="company-video-expand"
                                data-video-expand
                                aria-label="<?php echo esc_attr('Open ' . $video['title'] . ' in popup'); ?>"
                            >

                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <path d="M15 3h6v6"/>
                                    <path d="M9 21H3v-6"/>
                                    <path d="M21 3l-7 7"/>
                                    <path d="M3 21l7-7"/>
                                </svg>

                            </button>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>


            <?php if (count($valid_videos) > 6) : ?>

                <div class="load-more-wrapper">

                    <button
                        type="button"
                        class="load-more-btn"
                        data-video-gallery-load
                    >
                        Load More
                    </button>

                </div>

            <?php endif; ?>


        <?php else : ?>

            <p>No videos are currently available.</p>

        <?php endif; ?>

    </div>

</section>


<!-- VIDEO LIGHTBOX -->

<div
    class="company-video-lightbox"
    id="companyVideoLightbox"
    aria-hidden="true"
>

    <button
        type="button"
        class="company-video-lightbox-close"
        aria-label="Close video"
    >
        &times;
    </button>


    <button
        type="button"
        class="company-video-lightbox-arrow company-video-lightbox-prev"
        aria-label="Previous video"
    >
        &#10094;
    </button>


    <div class="company-video-lightbox-content">

        <!-- MP4 PLAYER -->

        <video
            class="company-video-lightbox-player"
            controls
            playsinline
            preload="metadata"
        >
            Your browser does not support HTML5 video.
        </video>


        <!-- YOUTUBE PLAYER -->

        <iframe
            class="company-youtube-lightbox-player"
            src=""
            title="LiHi YouTube Video"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen
        ></iframe>


        <span class="company-video-lightbox-counter"></span>

    </div>


    <button
        type="button"
        class="company-video-lightbox-arrow company-video-lightbox-next"
        aria-label="Next video"
    >
        &#10095;
    </button>

</div>
