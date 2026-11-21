<?php
/* Template Name: Gallery Page */

get_header();
?>

<main class="gallery-page">

    <?php get_template_part('template-parts/image_gallery/hero'); ?>

    <?php get_template_part('template-parts/image_gallery/gallery-section'); ?>

    <?php get_template_part('components/cta'); ?>

</main>

<?php get_footer(); ?>