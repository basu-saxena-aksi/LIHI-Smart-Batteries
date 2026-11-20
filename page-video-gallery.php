
<?php
/* Template Name: Video Gallery Page */

get_header();
?>

<main class="video-gallery-page">

    <?php
    
    get_template_part(
        'template-parts/video_gallery/hero'
    );
    ?>

    <?php
    
    get_template_part(
        'template-parts/video_gallery/video-gallery-section'
    );
    ?>

</main>

<?php get_footer(); ?>
