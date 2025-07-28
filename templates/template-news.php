<?php
/**
 * Template Name: News Page.
 */
get_header(); ?>

<?php
// Get page title and featured image
$banner_title = get_the_title();
$banner_image_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full_hd') : 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=1920&h=600&fit=crop';
?>

<!-- News Banner Section -->
<section class="news-banner">
    <div class="news-banner__inner" style="background-image: url('<?php echo esc_url($banner_image_url); ?>');">
        <div class="news-banner__content">
            <div class="grid-container">
                <div class="news-title-block">
                    <h1 class="news-title"><?php echo esc_html($banner_title); ?></h1>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News Posts Section -->
<section class="news-posts-section">
    <div class="grid-container menu-grid-container">
        <div class="grid-x">
            <div class="cell">
                <?php
                // Get posts per page from WordPress Reading Settings
                $posts_per_page = get_option('posts_per_page');

                // Get current page
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                // Query posts
                $news_query = new WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => $posts_per_page,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'paged' => $paged,
                ]);

                if ($news_query->have_posts()) { ?>

                    <!-- News Grid -->
                    <div class="news-grid">
                        <!-- Pagination dots before posts -->
                        <?php foundation_pagination($news_query, 'dots'); ?>

                        <div class="grid-x grid-margin-x" id="news-posts-container">
                            <?php while ($news_query->have_posts()) {
                                $news_query->the_post();
                                ?>
                                <div class="cell large-4 medium-6 small-12">
                                    <?php get_template_part('parts/loop', 'post'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Pagination dots after posts -->
                    <?php foundation_pagination($news_query, 'dots'); ?>

                <?php } else { ?>
                    <p><?php _e('No news posts found.', 'fwp'); ?></p>
                <?php }

                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
