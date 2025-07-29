<?php
// Get posts count from WordPress Reading Settings
$posts_per_page = get_option('posts_per_page');

$latest_posts = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
]);

if ($latest_posts->have_posts()) { ?>
    <section class="latest-news-section">
        <div class="grid-container">
            <div class="grid-x">
                <div class="cell">
                    <!-- Horizontal line -->
                    <div class="section-divider"></div>

                    <!-- Section title -->
                    <h2 class="section-title">Latest News from Lysoclear</h2>

                    <!-- Posts slider or grid -->
                    <!-- Home News Grid with proper gap support -->
                    <div class="home-news-grid">
                        <div class="grid-x" id="home-news-posts-container">
                            <?php while ($latest_posts->have_posts()) {
                                $latest_posts->the_post();
                                ?>
                                <div class="cell large-4 medium-4 small-12">
                                    <?php get_template_part('parts/loop', 'post'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Pagination dots for home news -->
                    <?php if ($latest_posts->max_num_pages > 1) { ?>
                        <?php foundation_pagination($latest_posts, 'dots'); ?>
                    <?php } ?>

                </div>
            </div>
        </div>
    </section>
    <?php wp_reset_postdata(); ?>
<?php } ?>
