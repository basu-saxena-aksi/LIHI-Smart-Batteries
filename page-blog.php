<?php
/* Template Name: Blog Page */

get_header();
?>

<main class="blog-page">

    <?php get_template_part('template-parts/blogs/hero'); ?>

    <?php get_template_part('template-parts/blogs/blog-section'); ?>

</main>

<?php get_footer(); ?>