<?php 
get_header(); 
get_template_part('components/home-preloader');
?>
    
<main>

    <?php get_template_part('template-parts/home/hero-top'); ?>

    <!-- <?php get_template_part('template-parts/home/hero'); ?> -->

    <!-- <?php get_template_part('template-parts/home/industries'); ?> -->
    
    <?php get_template_part('template-parts/home/home-industries'); ?>

    <?php get_template_part('template-parts/home/carousel'); ?>
    
    <!-- <?php get_template_part('template-parts/home/hover-cards'); ?> -->
    
    <?php get_template_part('template-parts/home/features'); ?>
    
    <?php get_template_part('template-parts/home/home-product-carousel'); ?>
  
    <!-- <?php get_template_part('template-parts/home/technology'); ?> -->
    
    <?php get_template_part('template-parts/home/lihi-stats'); ?>
    
    <?php get_template_part('template-parts/home/service-cards'); ?>
    <?php get_template_part('template-parts/home/faq'); ?>


    <?php get_template_part('template-parts/home/contact'); ?>


</main>
    

<?php get_footer(); ?>