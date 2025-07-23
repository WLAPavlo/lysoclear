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
            <div class="news-banner__overlay"></div>
            <div class="news-banner__content">
                <div class="news-title-block">
                    <h1 class="news-title"><?php echo esc_html($banner_title); ?></h1>
                </div>
            </div>
        </div>
    </section>

    <!-- News Posts Section -->
    <section class="news-posts-section">
        <div class="grid-container">
            <div class="grid-x">
                <div class="cell">
                    <?php
                    // Always show maximum 9 posts per page
                    $posts_per_page = 9;

                    // Get current page
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                    // Query posts
                    $news_query = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => $posts_per_page,
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'paged' => $paged
                    ]);

                    if ($news_query->have_posts()) { ?>

                        <?php if ($news_query->max_num_pages > 1) { ?>
                        <!-- Top Pagination -->
                        <div class="news-pagination news-pagination--top">
                            <div class="news-dots">
                                <?php for ($i = 1; $i <= $news_query->max_num_pages; $i++) { ?>
                                    <button class="news-dot <?php echo ($i == max(1, get_query_var('paged'))) ? 'active' : ''; ?>"
                                            data-page="<?php echo $i; ?>"></button>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                        <!-- News Grid -->
                        <div class="news-grid">
                            <div class="grid-x grid-margin-x" id="news-posts-container">
                                <?php while ($news_query->have_posts()) {
                                    $news_query->the_post();
                                    ?>
                                    <div class="cell medium-4 small-12">
                                        <?php get_template_part('parts/loop', 'post'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                    <?php if ($news_query->max_num_pages > 1) { ?>
                        <!-- Bottom Pagination -->
                        <div class="news-pagination news-pagination--bottom">
                            <div class="news-dots">
                                <?php for ($i = 1; $i <= $news_query->max_num_pages; $i++) { ?>
                                    <button class="news-dot <?php echo ($i == max(1, get_query_var('paged'))) ? 'active' : ''; ?>"
                                            data-page="<?php echo $i; ?>"></button>
                                <?php } ?>
                            </div>
                        </div>

                        <script>
                            jQuery(document).ready(function($) {
                                $('.news-dot').on('click', function(event) {
                                    // Prevent default behavior
                                    event.preventDefault();

                                    var page = $(this).data('page');

                                    // Remove active class from all dots (both top and bottom)
                                    $('.news-dot').removeClass('active');
                                    // Add active class to all dots with same page number
                                    $('.news-dot[data-page="' + page + '"]').addClass('active');

                                    // Hide all news posts with fade effect
                                    $('#news-posts-container .cell').fadeOut(300, function() {
                                        // After fade out, load new content via AJAX
                                        $.ajax({
                                            url: ajax_object.ajax_url,
                                            type: 'POST',
                                            data: {
                                                action: 'load_news_posts',
                                                page: page,
                                                nonce: ajax_object.nonce
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    $('#news-posts-container').html(response.data);
                                                    $('#news-posts-container .cell').hide().fadeIn(400);
                                                }
                                            }
                                        });
                                    });
                                });
                            });
                        </script>
                    <?php } ?>

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
