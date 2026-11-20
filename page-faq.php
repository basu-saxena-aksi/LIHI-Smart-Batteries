<?php
/**
 * Template Name: FAQ Page
 */

get_header();
?>

<main class="faq-page">

    <?php get_template_part('template-parts/faq/faq-hero'); ?>
    <?php get_template_part('template-parts/faq/faq-section'); ?>
    <?php get_template_part('template-parts/faq/faq-form'); ?>

</main>

<?php get_footer(); ?>