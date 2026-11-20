<?php
/*
 * Template Name: About Us
 */
get_header();
?>

<main class="grid-wrapper">
    <div class="grid-background">
        <?php get_template_part('template-parts/about/about-hero'); ?>

        <?php get_template_part('template-parts/about/about-us'); ?>

        <?php get_template_part('template-parts/about/our-story'); ?>

        <?php get_template_part('template-parts/about/manufacturing-approach'); ?>

        <!-- <?php get_template_part('template-parts/about/mission-vision-values'); ?> -->
        <?php get_template_part('template-parts/about/mvv'); ?>
        <?php get_template_part('template-parts/about/manufacturing_journey'); ?>
        <?php get_template_part('template-parts/home/lihi-stats'); ?>

        <?php get_template_part('template-parts/about/technology-and-expertise'); ?>

        <?php get_template_part('template-parts/about/team'); ?>
        <?php get_template_part('template-parts/about/quality'); ?>

        <!-- <?php get_template_part('template-parts/about/capabilities'); ?> -->


        <?php get_template_part('template-parts/about/solutions'); ?>

        <?php get_template_part('template-parts/about/process'); ?>

        <?php get_template_part('template-parts/about/engineering-approach'); ?>

        <!-- <?php get_template_part('template-parts/about/certifications'); ?> -->

        <?php get_template_part('template-parts/about/cta'); ?>
    </div>
</main>


<?php get_footer(); ?>