
<?php
/**
 * Single News Article Template
 */

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();

        // Article information
        $news_id      = get_the_ID();
        $news_title   = get_the_title();
        $news_image   = get_the_post_thumbnail_url($news_id, 'full');
        $news_date    = get_the_date('F j, Y');

        // Calculate reading time
        $news_content = get_post_field('post_content', $news_id);
        $word_count   = str_word_count(
            wp_strip_all_tags(strip_shortcodes($news_content))
        );

        $reading_time = max(1, ceil($word_count / 200));

        // Parallax Hero
        get_template_part('components/parallax-hero', null, [
            'eyebrow'    => 'NEWS & UPDATES',
            'heading'    => $news_title,
            'highlight'  => '',
            'background' => $news_image ?: 'news-hero-bg.jpg',
        ]);
        ?>

        <main class="news-detail-page">

            <section class="news-detail-section">

                <div class="wrap">

                    <!-- ARTICLE HEADER -->
                    <div class="news-detail-header">

                        <div class="news-detail-meta">

                            <span class="news-detail-date">
                                <?php echo esc_html($news_date); ?>
                            </span>

                            <span class="news-detail-meta-divider"></span>

                            <span class="news-detail-reading-time">
                                <?php echo esc_html($reading_time); ?> min read
                            </span>

                        </div>

                        <div class="news-detail-divider"></div>

                    </div>


                    <!-- ARTICLE CONTENT -->
                    <article class="news-detail-content">

                        <?php the_content(); ?>

                    </article>


                    <!-- ARTICLE FOOTER -->
                    <div class="news-detail-footer">

                        <a href="<?php echo esc_url(home_url('/news/')); ?>"
                           class="news-back-link">

                            <span class="news-back-arrow">←</span>
                            Back to All News

                        </a>

                    </div>

                </div>

            </section>

        </main>

        <?php

    endwhile;
endif;

get_footer();
