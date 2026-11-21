<?php
/* Template Name: News Page */

get_header();
?>

<main class="news-page">
    <?php get_template_part('template-parts/news/hero'); ?>
    <?php get_template_part('template-parts/news/news-section'); ?>
    <?php get_template_part('components/cta'); ?>

</main>

<?php get_footer(); ?>