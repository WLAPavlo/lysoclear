<?php
/**
 * Template Name: Home Page.
 */
get_header(); ?>

<!--HOME PAGE SLIDER-->
<?php if (shortcode_exists('slider')) {
    echo do_shortcode('[slider]');
} ?>
<!--END of HOME PAGE SLIDER-->
<!-- FEATURES SECTION -->
<?php get_template_part('parts/features-section'); ?>
<!-- END FEATURES SECTION -->

<!-- FLEXIBLE CONTENT -->
<?php get_template_part('parts/flexible-content'); ?>
<!-- END FLEXIBLE CONTENT -->

<!-- TESTIMONIALS SECTION -->
<?php get_template_part('parts/testimonials-section'); ?>
<!-- END TESTIMONIALS SECTION -->

<!-- LATEST NEWS SECTION -->
<?php get_template_part('parts/latest-news-section'); ?>
<!-- END LATEST NEWS SECTION -->

<?php get_footer(); ?>
