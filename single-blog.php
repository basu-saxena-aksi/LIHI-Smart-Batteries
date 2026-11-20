
<?php
/**
 * Single Blog Article Template
 */

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();

        $blog_id      = get_the_ID();
        $blog_title   = get_the_title();
        $blog_excerpt = get_the_excerpt();
        $blog_image   = get_the_post_thumbnail_url($blog_id, 'full');
        $blog_date    = get_the_date('F j, Y');

        // Reading time
        $content_words = str_word_count(
            wp_strip_all_tags(get_the_content())
        );

        $reading_time = max(1, ceil($content_words / 200));

        // Blog category
        $categories = get_the_terms($blog_id, 'blog_category');
        ?>

        <?php
        // Parallax Hero
        get_template_part('components/parallax-hero', null, [
            'eyebrow'    => 'BLOG & INSIGHTS',
            'heading'    => $blog_title,
            'highlight'  => '',
            'background' => $blog_image ?: 'blog-hero-bg.jpg',
        ]);
        ?>

        <main class="blog-detail-page">

            <section class="blog-detail-section">

                <div class="wrap">

                    <!-- Article Header -->
                    <div class="blog-detail-header">

                        <div class="blog-detail-meta">

                            <?php if ($categories && !is_wp_error($categories)) : ?>
                                <span class="blog-detail-category">
                                    <?php echo esc_html($categories[0]->name); ?>
                                </span>
                            <?php endif; ?>

                            <span class="blog-detail-date">
                                <?php echo esc_html($blog_date); ?>
                            </span>

                            <span class="blog-detail-reading-time">
                                <?php echo esc_html($reading_time); ?> min read
                            </span>

                        </div>

                        <div class="blog-detail-divider"></div>

                    </div>


                    <!-- Article Content -->
                    <article class="blog-detail-content">

                        <?php the_content(); ?>

                    </article>


                    <!-- Back to Blog -->
                    <div class="blog-detail-footer">

                        <a href="<?php echo esc_url(home_url('/blog/')); ?>"
                           class="blog-back-link">

                            <span class="blog-back-arrow">←</span>
                            Back to All Blogs

                        </a>

                    </div>

                </div>

            </section>

        </main>

        <?php

    endwhile;
endif;

get_footer();
