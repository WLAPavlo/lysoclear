<?php
// Get ACF field values for Latest News section
$latest_news_title = get_field('latest_news_title') ?: 'Latest News from Lysoclear';
$posts_per_page = get_field('latest_news_posts_per_page') ?: 6;
$total_posts = get_field('latest_news_total_posts') ?: 0;

// Get current page
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Query posts using ACF settings
$latest_posts = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page,
    'numberposts' => $total_posts > 0 ? $total_posts : -1,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
    'paged' => $paged,
]);

if ($latest_posts->have_posts()) { ?>
    <section class="latest-news-section">
        <div class="grid-container">
            <div class="grid-x">
                <div class="cell">
                    <!-- Horizontal line -->
                    <div class="section-divider"></div>

                    <!-- Section title -->
                    <h2 class="section-title"><?php echo esc_html($latest_news_title); ?></h2>

                    <!-- News Grid -->
                    <div class="home-news-grid">
                        <div class="grid-x">
                            <?php while ($latest_posts->have_posts()) {
                                $latest_posts->the_post();
                                ?>
                                <div class="cell large-4 medium-4 small-12">
                                    <?php get_template_part('parts/loop', 'post'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Pagination using foundation_pagination -->
                    <?php foundation_pagination($latest_posts); ?>
                </div>
            </div>
        </div>
    </section>
    <?php wp_reset_postdata(); ?>
<?php } ?>
