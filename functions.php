<?php

function lihi_enqueue_assets()
{

    wp_enqueue_style(
        'lihi-tailwind',
        get_template_directory_uri() . '/css/output.css',
        array(),
        filemtime(get_template_directory() . '/css/output.css')
    );

    wp_enqueue_style(
        'lihi-style',
        get_stylesheet_uri(),
        array('lihi-tailwind'),
        filemtime(get_stylesheet_directory() . '/style.css')
    );



    // Google Fonts
    wp_enqueue_style(
        'lihi-inter',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap',
        array(),
        null
    );

    // Lucide
    wp_enqueue_script(
        'lucide',
        'https://unpkg.com/lucide@latest',
        array(),
        null,
        true
    );

    // GSAP
    wp_enqueue_script(
        'gsap',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
        array(),
        '3.12.5',
        true
    );


    // GSAP ScrollTrigger
    wp_enqueue_script(
        'gsap-scrolltrigger',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js',
        array('gsap'),
        '3.12.5',
        true
    );


    wp_enqueue_script(
        'lihi-main',
        get_template_directory_uri() . '/js/main.js',
        array('lucide', 'gsap', 'gsap-scrolltrigger'),
        '1.0',
        true
    );

}

add_action('wp_enqueue_scripts', 'lihi_enqueue_assets');

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
});




add_action('init', function () {

    remove_action(
        'woocommerce_before_shop_loop_item_title',
        'woocommerce_template_loop_product_thumbnail',
        10
    );

    add_action('woocommerce_before_shop_loop_item_title', function () {

        global $product;

        if (!$product instanceof WC_Product) {
            return;
        }

        $image_id = $product->get_image_id();

        if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);

            if ($image_url) {
                echo '<img class="lihi-archive-product-image"
                    src="' . esc_url($image_url) . '"
                    alt="' . esc_attr($product->get_name()) . '"
                    loading="lazy">';
            }
        }

    }, 10);
});

function lihi_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
}


add_action('after_setup_theme', 'lihi_theme_setup');

add_filter('woocommerce_catalog_orderby', function ($options) {
    return array(
        'menu_order' => 'Default Sorting',
        'date' => 'Newest First',
        'title' => 'Name: A to Z',
        'title-desc' => 'Name: Z to A',
    );
});

add_filter('wp_calculate_image_srcset', function ($sources) {
    foreach ($sources as &$source) {
        if (!empty($source['url'])) {
            $source['url'] = set_url_scheme($source['url'], 'https');
        }
    }

    return $sources;
});

// font 
function lihi_enqueue_transition_font()
{
    wp_enqueue_style(
        'lihi-playfair-font',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap',
        [],
        null
    );
}
add_action('wp_enqueue_scripts', 'lihi_enqueue_transition_font');

/* =========================================================
   FAQ ACCORDION SCRIPT
========================================================= */

function lihi_enqueue_faq_script()
{

    $file = get_template_directory() . '/js/faq.js';

    if (file_exists($file)) {
        wp_enqueue_script(
            'lihi-faq',
            get_template_directory_uri() . '/js/faq.js',
            [],
            filemtime($file),
            true
        );
    }

}
add_action('wp_enqueue_scripts', 'lihi_enqueue_faq_script');


/**
 * Load Google's 3D Model Viewer on product pages.
 */
function lihi_enqueue_model_viewer()
{

    if (!is_product()) {
        return;
    }

    wp_enqueue_script(
        'lihi-model-viewer',
        'https://ajax.googleapis.com/ajax/libs/model-viewer/4.1.0/model-viewer.min.js',
        [],
        '4.1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'lihi_enqueue_model_viewer');


/**
 * Load Model Viewer as an ES module.
 */
function lihi_model_viewer_script_type($tag, $handle, $src)
{

    if ($handle !== 'lihi-model-viewer') {
        return $tag;
    }

    return '<script type="module" src="' . esc_url($src) . '"></script>';
}
add_filter('script_loader_tag', 'lihi_model_viewer_script_type', 10, 3);


function lihi_register_industry_post_type()
{

    register_post_type('industry', array(

        'labels' => array(
            'name' => 'Industries',
            'singular_name' => 'Industry',
            'add_new_item' => 'Add New Industry',
            'edit_item' => 'Edit Industry',
            'all_items' => 'All Industries',
        ),

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,

        'menu_icon' => 'dashicons-building',

        'supports' => array(
            'title',
            'thumbnail',
        ),

        'has_archive' => false,

        'rewrite' => array(
            'slug' => 'industries',
        ),

        'show_in_rest' => true,
    ));
}

add_action('init', 'lihi_register_industry_post_type');

function lihi_register_application_cpt()
{

    register_post_type('application', [
        'labels' => [
            'name' => 'Applications',
            'singular_name' => 'Application',
            'add_new_item' => 'Add New Application',
            'edit_item' => 'Edit Application',
            'new_item' => 'New Application',
            'view_item' => 'View Application',
            'search_items' => 'Search Applications',

        ],

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,

        'menu_icon' => 'dashicons-screenoptions',

        'supports' => [
            'title',
            'editor',
            'thumbnail',
        ],

        'has_archive' => false,

        'query_var' => 'lihi_application',

        'rewrite' => [
            'slug' => 'applications',
        ],

        'show_in_rest' => true,
    ]);

}

add_action('init', 'lihi_register_application_cpt');

function lihi_enqueue_related_components_scripts()
{

    wp_enqueue_script(
        'lihi-related-applications',
        get_template_directory_uri() . '/js/related_applications.js',
        array(),
        '1.0',
        true
    );

    if (is_singular('product')) {

        wp_enqueue_script(
            'lihi-related-products',
            get_template_directory_uri() . '/js/related_products.js',
            array(),
            '1.0',
            true
        );
    }
}

add_action(
    'wp_enqueue_scripts',
    'lihi_enqueue_related_components_scripts'
);

function lihi_enqueue_mobile_menu_script()
{
    wp_enqueue_script(
        'lihi-mobile-menu',
        get_template_directory_uri() . '/js/mobile-menu.js',
        array(),
        filemtime(get_template_directory() . '/js/mobile-menu.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'lihi_enqueue_mobile_menu_script');


function lihi_enqueue_archive_load_more()
{

    if (!is_shop() && !is_product_category()) {
        return;
    }

    $script_path = get_template_directory() . '/js/load-more.js';

    wp_enqueue_script(
        'lihi-load-more',
        get_template_directory_uri() . '/js/load-more.js',
        [],
        file_exists($script_path) ? filemtime($script_path) : '1.0',
        true
    );
}

add_action('wp_enqueue_scripts', 'lihi_enqueue_archive_load_more');

add_filter('loop_shop_per_page', function () {
    return 8;
}, 20);

add_action('init', function () {
    remove_action(
        'woocommerce_before_shop_loop',
        'woocommerce_result_count',
        20
    );
});


function lihi_enqueue_archive_filters()
{

    if (!is_product_category()) {
        return;
    }

    $script_path = get_template_directory()
        . '/js/archive-filters.js';

    wp_enqueue_script(
        'lihi-archive-filters',
        get_template_directory_uri() . '/js/archive-filters.js',
        [],
        file_exists($script_path)
        ? filemtime($script_path)
        : '1.0',
        true
    );
}

add_action(
    'wp_enqueue_scripts',
    'lihi_enqueue_archive_filters'
);

add_action('wp_enqueue_scripts', function () {
    if (!is_product()) {
        return;
    }

    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    wp_enqueue_script(
        'lihi-single-product',
        $uri . '/js/single-product.js',
        array(),
        filemtime($dir . '/js/single-product.js'),
        true
    );
});


/* =========================================================
   LIHI - MATCH PDF DATASHEETS TO PRODUCTS
   TEMPORARY ADMIN TOOL
========================================================= */

add_action('admin_menu', function () {
    add_management_page(
        'Link Battery Datasheets',
        'Link Battery Datasheets',
        'manage_options',
        'lihi-link-datasheets',
        'lihi_link_datasheets_page'
    );
});

function lihi_link_datasheets_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    echo '<div class="wrap"><h1>Link Battery Datasheets</h1>';

    if (
        !isset($_POST['lihi_link_datasheets']) ||
        !check_admin_referer('lihi_link_datasheets_action')
    ) {
        echo '<p>Matches PDF filenames with WooCommerce product names and links them to the Product Datasheet ACF field.</p>';

        echo '<form method="post">';
        wp_nonce_field('lihi_link_datasheets_action');
        submit_button('Match and Link Datasheets', 'primary', 'lihi_link_datasheets');
        echo '</form></div>';
        return;
    }

    if (!function_exists('wc_get_products') || !function_exists('update_field')) {
        echo '<p>WooCommerce and ACF must be active.</p></div>';
        return;
    }

    $normalize = static function ($name) {
        $name = preg_replace('/\.pdf$/i', '', $name);
        $name = preg_replace('/^\s*\d+\s*[.\-)]\s*/u', '', $name);
        $name = remove_accents($name);
        $name = mb_strtolower($name, 'UTF-8');
        $name = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $name);

        return trim(preg_replace('/\s+/u', ' ', $name));
    };

    $products = wc_get_products([
        'limit' => -1,
        'status' => ['publish', 'private', 'draft'],
        'return' => 'objects'
    ]);

    $product_map = [];

    foreach ($products as $product) {
        $key = $normalize($product->get_name());
        $product_map[$key][] = $product->get_id();
    }

    $pdf_ids = get_posts([
        'post_type' => 'attachment',
        'post_mime_type' => 'application/pdf',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);

    $linked = 0;
    $unmatched = [];
    $ambiguous = [];
    $failed = [];

    foreach ($pdf_ids as $pdf_id) {
        $path = get_attached_file($pdf_id);

        if (!$path) {
            continue;
        }

        $filename = basename($path);

        // Only process numbered PDF filenames.
        if (!preg_match('/^\s*\d+\s*[.\-)]\s*/u', $filename)) {
            continue;
        }

        $key = $normalize($filename);

        if (empty($product_map[$key])) {
            $unmatched[] = $filename;
            continue;
        }

        if (count($product_map[$key]) !== 1) {
            $ambiguous[] = $filename;
            continue;
        }

        $product_id = $product_map[$key][0];

        // Never overwrite an existing datasheet.
        $existing = get_field('product_datasheet', $product_id);

        if (!empty($existing)) {
            continue;
        }

        $saved = update_field(
            'product_datasheet',
            (int) $pdf_id,
            $product_id
        );

        if ($saved) {
            $linked++;
        } else {
            $failed[] = $filename;
        }
    }

    echo '<h2>Results</h2>';
    echo '<p><strong>Datasheets linked:</strong> ' . esc_html($linked) . '</p>';
    echo '<p><strong>Unmatched PDFs:</strong> ' . count($unmatched) . '</p>';
    echo '<p><strong>Ambiguous matches:</strong> ' . count($ambiguous) . '</p>';
    echo '<p><strong>Failed updates:</strong> ' . count($failed) . '</p>';

    foreach ([
        'Unmatched PDFs' => $unmatched,
        'Ambiguous matches' => $ambiguous,
        'Failed updates' => $failed
    ] as $heading => $items) {
        if (!$items) {
            continue;
        }

        echo '<h3>' . esc_html($heading) . '</h3><ul>';

        foreach ($items as $item) {
            echo '<li>' . esc_html($item) . '</li>';
        }

        echo '</ul>';
    }

    echo '</div>';
}

/* =========================================================
   LIHI HOMEPAGE PRELOADER
========================================================= */

function lihi_enqueue_home_preloader()
{

    if (!is_front_page()) {
        return;
    }

    wp_enqueue_style(
        'lihi-preloader-fonts',
        'https://fonts.googleapis.com/css2?family=Tulpen+One&family=IBM+Plex+Mono:wght@400&display=swap',
        [],
        null
    );

    $file = get_template_directory() . '/js/home-preloader.js';

    wp_enqueue_script(
        'lihi-home-preloader',
        get_template_directory_uri() . '/js/home-preloader.js',
        [],
        file_exists($file) ? filemtime($file) : null,
        true
    );
}

add_action('wp_enqueue_scripts', 'lihi_enqueue_home_preloader');


/* =========================================================
   HOME PRODUCT CAROUSEL SCRIPT
========================================================= */

function lihi_enqueue_home_product_carousel()
{

    if (is_front_page()) {

        wp_enqueue_script(
            'lihi-home-product-carousel',
            get_template_directory_uri() . '/js/home-product-carousel.js',
            array(),
            filemtime(get_template_directory() . '/js/home-product-carousel.js'),
            true
        );

    }
}

add_action(
    'wp_enqueue_scripts',
    'lihi_enqueue_home_product_carousel'
);

/**
 * Allow GLB 3D model uploads.
 */
function lihi_allow_glb_uploads($mimes)
{
    $mimes['glb'] = 'model/gltf-binary';
    return $mimes;
}
add_filter('upload_mimes', 'lihi_allow_glb_uploads');


/**
 * Correctly identify GLB files during upload validation.
 */
function lihi_check_glb_filetype($data, $file, $filename, $mimes)
{

    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if ($extension === 'glb') {
        $data['ext'] = 'glb';
        $data['type'] = 'model/gltf-binary';
    }

    return $data;
}
add_filter('wp_check_filetype_and_ext', 'lihi_check_glb_filetype', 10, 4);

/* =========================================================
   LIHI IMAGE CAROUSEL
========================================================= */

function lihi_enqueue_image_carousel()
{

    if (is_front_page()) {

        wp_enqueue_script(
            'lihi-image-carousel',
            get_template_directory_uri() . '/js/image-carousel.js',
            [],
            filemtime(get_template_directory() . '/js/image-carousel.js'),
            true
        );

    }
}

add_action('wp_enqueue_scripts', 'lihi_enqueue_image_carousel');

/* =========================================================
   LIHI HOVER CARDS
========================================================= */

function lihi_enqueue_hover_cards()
{

    if (is_front_page()) {

        wp_enqueue_script(
            'lihi-hover-cards',
            get_template_directory_uri() . '/js/hover-cards.js',
            [],
            filemtime(
                get_template_directory() . '/js/hover-cards.js'
            ),
            true
        );
    }
}

add_action('wp_enqueue_scripts', 'lihi_enqueue_hover_cards');

function lihi_enqueue_global_parallax()
{

    $file = get_template_directory() . '/js/lihi-parallax.js';

    wp_enqueue_script(
        'lihi-global-parallax',
        get_template_directory_uri() . '/js/lihi-parallax.js',
        [],
        file_exists($file) ? filemtime($file) : null,
        true
    );
}

add_action('wp_enqueue_scripts', 'lihi_enqueue_global_parallax');

/* =========================================================
   LIHI STATS - COUNTERS & SCROLL REVEAL
========================================================= */

function lihi_enqueue_stats_script()
{

    $file = get_template_directory() . '/js/lihi-stats.js';

    wp_enqueue_script(
        'lihi-stats',
        get_template_directory_uri() . '/js/lihi-stats.js',
        [],
        file_exists($file) ? filemtime($file) : null,
        true
    );
}

add_action('wp_enqueue_scripts', 'lihi_enqueue_stats_script');

/* =========================================================
   LIHI SERVICE CARDS
========================================================= */

function lihi_enqueue_service_cards()
{

    if (is_front_page()) {

        $script_path = get_template_directory() . '/js/service-cards.js';

        wp_enqueue_script(
            'lihi-service-cards',
            get_template_directory_uri() . '/js/service-cards.js',
            [],
            file_exists($script_path) ? filemtime($script_path) : null,
            true
        );
    }
}

add_action('wp_enqueue_scripts', 'lihi_enqueue_service_cards');


/* =========================================================
   GLOBAL SMOOTH SCROLL
========================================================= */

function lihi_enqueue_smooth_scroll()
{

    wp_enqueue_script(
        'lihi-lenis',
        'https://cdn.jsdelivr.net/npm/lenis@1.3.17/dist/lenis.min.js',
        [],
        '1.3.17',
        true
    );

    wp_enqueue_script(
        'lihi-smooth-scroll',
        get_template_directory_uri() . '/js/smooth-scroll.js',
        ['lihi-lenis'],
        filemtime(get_template_directory() . '/js/smooth-scroll.js'),
        true
    );
}

add_action('wp_enqueue_scripts', 'lihi_enqueue_smooth_scroll');
/* =========================================================
   MISSION, VISION & VALUES JS
========================================================= */

function lihi_mvv_scripts()
{

    wp_enqueue_script(
        'lihi-mvv',
        get_template_directory_uri() . '/js/mvv.js',
        array(),
        filemtime(get_template_directory() . '/js/mvv.js'),
        true
    );

}
add_action('wp_enqueue_scripts', 'lihi_mvv_scripts');

function lihi_enqueue_faq_page_script()
{

    if (is_page('faq')) {
        wp_enqueue_script(
            'lihi-faq-page',
            get_template_directory_uri() . '/js/faq-page.js',
            array(),
            '1.0.0',
            true
        );
    }

}
add_action('wp_enqueue_scripts', 'lihi_enqueue_faq_page_script');

function lihi_gallery_assets()
{

    if (is_page_template('page-gallery.php')) {

        wp_enqueue_script(
            'lihi-gallery',
            get_template_directory_uri() . '/js/gallery.js',
            [],
            '1.0.0',
            true
        );

    }

}

add_action('wp_enqueue_scripts', 'lihi_gallery_assets');

function lihi_video_gallery_assets()
{

    if (is_page_template('page-video-gallery.php')) {

        $script_path = get_template_directory() . '/js/video-gallery.js';

        wp_enqueue_script(
            'lihi-video-gallery',
            get_template_directory_uri() . '/js/video-gallery.js',
            [],
            file_exists($script_path) ? filemtime($script_path) : '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'lihi_video_gallery_assets');

/* =========================================================
   GLOBAL PAGE TRANSITION
========================================================= */

// Add transition overlay immediately after the opening body tag.

function lihi_page_transition_overlay()
{
    ?>
    <div class="page-transition-overlay" aria-hidden="true">
        <span class="page-transition-title"></span>
    </div>

    <script>
        try {
            if (
                document.documentElement.classList.contains(
                    'is-transition-entering'
                )
            ) {
                const savedTitle = sessionStorage.getItem(
                    'lihi-page-title'
                );

                if (savedTitle) {
                    document.querySelector(
                        '.page-transition-title'
                    ).textContent = savedTitle;
                }
            }
        } catch (error) { }
    </script>
    <?php
}
add_action('wp_body_open', 'lihi_page_transition_overlay');



// Set the incoming transition state before the page renders.
function lihi_page_transition_head()
{
    ?>
    <script>
        try {
            if (sessionStorage.getItem('lihi-page-transition') === '1') {
                document.documentElement.classList.add('is-transition-entering');
                sessionStorage.removeItem('lihi-page-transition');
            }
        } catch (error) { }
    </script>
    <?php
}
add_action('wp_head', 'lihi_page_transition_head', 1);


// Load the transition JavaScript on the entire website.

function lihi_page_transition_assets()
{
    $script_path = get_template_directory() . '/js/page-transition.js';

    wp_enqueue_script(
        'lihi-page-transition',
        get_template_directory_uri() . '/js/page-transition.js',
        [],
        file_exists($script_path) ? filemtime($script_path) : '1.0.1',
        true
    );
}
add_action('wp_enqueue_scripts', 'lihi_page_transition_assets');

/* =========================================================
   LIHI - NEWS CUSTOM POST TYPE
========================================================= */

function lihi_register_news_post_type()
{

    $labels = [
        'name' => 'News',
        'singular_name' => 'News Article',
        'menu_name' => 'News',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Article',
        'edit_item' => 'Edit Article',
        'new_item' => 'New Article',
        'view_item' => 'View Article',
        'search_items' => 'Search News',
        'not_found' => 'No news articles found',
        'not_found_in_trash' => 'No news articles found in Trash',
        'all_items' => 'All News',
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-media-document',

        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'revisions',
        ],

        'has_archive' => false,
        'publicly_queryable' => true,
        'query_var' => true,

        'rewrite' => [
            'slug' => 'news',
            'with_front' => false,
        ],
    ];

    register_post_type('news', $args);
}

add_action('init', 'lihi_register_news_post_type');


/* =========================================================
   LIHI - BLOG CUSTOM POST TYPE
========================================================= */

function lihi_register_blog_post_type()
{

    $labels = [
        'name' => 'Blogs',
        'singular_name' => 'Blog Article',
        'menu_name' => 'Blogs',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Blog',
        'edit_item' => 'Edit Blog',
        'new_item' => 'New Blog',
        'view_item' => 'View Blog',
        'search_items' => 'Search Blogs',
        'not_found' => 'No blogs found',
        'all_items' => 'All Blogs',
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-edit-page',

        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'author',
            'revisions',
        ],

        'has_archive' => false,
        'publicly_queryable' => true,

        'rewrite' => [
            'slug' => 'blog',
            'with_front' => false,
        ],
    ];

    register_post_type('blog', $args);

    register_taxonomy('blog_category', ['blog'], [
        'labels' => [
            'name' => 'Blog Categories',
            'singular_name' => 'Blog Category',
            'add_new_item' => 'Add New Category',
        ],
        'public' => true,
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => [
            'slug' => 'blog-category',
        ],
    ]);
}

add_action('init', 'lihi_register_blog_post_type');

function lihi_company_gallery_scripts()
{

    wp_enqueue_script(
        'lihi-company-gallery',
        get_template_directory_uri() . '/js/company-gallery.js',
        [],
        filemtime(get_template_directory() . '/js/company-gallery.js'),
        true
    );

}

add_action('wp_enqueue_scripts', 'lihi_company_gallery_scripts');


/* =========================================================
   INDUSTRY & APPLICATION CARD TILT
========================================================= */

function lihi_industry_card_assets()
{

    if (
        !is_page('applications') &&
        !is_page_template('industries-page.php') &&
        !is_page('news') &&
        !is_page('blog')
    ) {
        return;
    }

    $script_path = get_template_directory() . '/js/industry-card-tilt.js';

    if (!file_exists($script_path)) {
        return;
    }

    wp_enqueue_script(
        'lihi-industry-card-tilt',
        get_template_directory_uri() . '/js/industry-card-tilt.js',
        [],
        filemtime($script_path),
        true
    );

}
add_action('wp_enqueue_scripts', 'lihi_industry_card_assets');

require_once get_template_directory() . '/inc/custom-battery-request.php';
