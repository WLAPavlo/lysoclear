<?php
/**
 * Home.
 *
 * Standard loop for the blog-page
 */
get_header(); ?>

<?php
// Get page title and featured image for banner from the current page (News page)
$news_page_id = get_queried_object_id();
$banner_title = get_the_title($news_page_id);
$banner_image_url = has_post_thumbnail($news_page_id) ? get_the_post_thumbnail_url($news_page_id, 'full_hd') : IMAGE_PLACEHOLDER;
?>

<!-- News Banner Section -->
<section class="news-banner">
    <div class="news-banner__inner" <?php bg($banner_image_url); ?>>
        <div class="news-banner__content">
            <div class="news-title-block">
                <h1 class="news-title"><?php echo esc_html($banner_title); ?></h1>
            </div>
        </div>
    </div>
</section>

<!-- News Posts Section -->
<section class="news-posts-section">
    <div class="grid-container menu-grid-container">
        <div class="grid-x" id="news-page-container">
            <div class="cell">
                <!-- Top pagination before posts -->
                <?php foundation_pagination(); ?>

                <?php
                if (have_posts()) { ?>

                    <!-- News Grid -->
                    <div class="news-grid">
                        <div class="grid-x grid-margin-x">
                            <?php while (have_posts()) {
                                the_post();
                                ?>
                                <div class="cell large-4 medium-6 small-12">
                                    <?php get_template_part('parts/loop', 'post'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                <?php } else { ?>
                    <p><?php _e('No news posts found.', 'fwp'); ?></p>
                <?php } ?>

                <?php foundation_pagination(); ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
