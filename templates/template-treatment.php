<?php
/**
 * Template Name: Treatment Page.
 */
get_header(); ?>

<?php
// Get page title and featured image for banner
$banner_title = get_the_title();
$banner_image_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full_hd') : 'https://images.pexels.com/photos/3845810/pexels-photo-3845810.jpeg?auto=compress&cs=tinysrgb&w=1920&h=600&fit=crop';
?>

    <!-- Treatment Banner Section -->
<?php get_template_part('parts/treatment-banner'); ?>

    <!-- Treatment Flexible Content -->
<?php get_template_part('parts/treatment-flexible-content'); ?>

    <!-- BEGIN of main content -->
    <div class="grid-container">
        <div class="grid-x grid-margin-x">
            <div class="cell">
                <?php if (have_posts()) { ?>
                    <?php while (have_posts()) {
                        the_post(); ?>
                        <?php the_content(); ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- END of main content -->

<?php get_footer(); ?>
