<?php
/*
 * Research & Development Page
 */
get_header();
?>

<main class="grid-wrapper">
    <div class="grid-background">
        <?php get_template_part('template-parts/about/about-hero'); ?>


        <?php get_template_part('template-parts/about/our-story'); ?>

        <?php get_template_part('template-parts/about/manufacturing-approach'); ?>

        <?php get_template_part('template-parts/about/quality'); ?>

        <!-- <?php get_template_part('template-parts/about/capabilities'); ?> -->


        <?php get_template_part('template-parts/about/solutions'); ?>

        <?php get_template_part('template-parts/about/process'); ?>

        <?php get_template_part('template-parts/about/engineering-approach'); ?>

        <!-- <?php get_template_part('template-parts/about/certifications'); ?> -->
        <?php get_template_part('components/cta'); ?>
    </div>
</main>


<?php get_footer(); ?>