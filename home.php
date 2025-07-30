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
                <?php
                // Get current page
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                // Query posts for pagination calculation
                $news_query_pagination = new WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => get_option('posts_per_page'),
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'paged' => $paged,
                ]);

                $max_pages_top = $news_query_pagination->max_num_pages;
                wp_reset_postdata();
                ?>

                <?php if ($max_pages_top > 1) { ?>
                    <div class="pagination-wrapper pagination-wrapper--top">
                        <div class="pagination-dots news-dots">
                            <?php for ($i = 1; $i <= $max_pages_top; $i++) { ?>
                                <a href="<?php echo get_pagenum_link($i); ?>" class="pagination-dot <?php echo $i === $paged ? 'active' : ''; ?>"></a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <?php
                // Query posts
                $news_query = new WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => get_option('posts_per_page'),
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'paged' => $paged,
                ]);

                if ($news_query->have_posts()) { ?>

                    <!-- News Grid -->
                    <div class="news-grid" id="news-grid-container">
                        <!-- News Slider with pagination -->
                        <div class="news-slider" id="news-slider">
                            <div class="news-slide">
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

                            <?php
                            // Get additional pages if there are more posts
                            $current_page = 2;
                            $max_pages = $news_query->max_num_pages;

                            while ($current_page <= $max_pages) {
                                $additional_posts = new WP_Query([
                                    'post_type' => 'post',
                                    'posts_per_page' => get_option('posts_per_page'),
                                    'post_status' => 'publish',
                                    'orderby' => 'date',
                                    'order' => 'DESC',
                                    'paged' => $current_page,
                                ]);

                                if ($additional_posts->have_posts()) { ?>
                                    <div class="news-slide">
                                        <div class="grid-x grid-margin-x">
                                            <?php while ($additional_posts->have_posts()) {
                                                $additional_posts->the_post();
                                                ?>
                                                <div class="cell large-4 medium-4 small-12">
                                                    <?php get_template_part('parts/loop', 'post'); ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php }
                                wp_reset_postdata();
                                $current_page++;
                            }
                            ?>
                        </div>

                        <!-- Custom dots pagination for news slider -->
                        <?php if ($max_pages > 1) { ?>
                            <div class="pagination-wrapper">
                                <div class="pagination-dots news-dots">
                                    <?php for ($i = 1; $i <= $max_pages; $i++) { ?>
                                        <a href="#" class="pagination-dot <?php echo $i === 1 ? 'active' : ''; ?>" data-slide="<?php echo $i - 1; ?>"></a>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                <?php } else { ?>
                    <p><?php _e('No news posts found.', 'fwp'); ?></p>
                <?php }

                wp_reset_postdata();
                ?>

                <script>
                    jQuery(document).ready(function($) {
                        // Initialize news slider
                        if ($('#news-slider').length && typeof $.fn.slick === 'function') {
                            $('#news-slider').slick({
                                dots: false, // We use custom dots
                                arrows: false,
                                infinite: false,
                                speed: 600,
                                fade: false,
                                cssEase: 'ease',
                                autoplay: false,
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                rows: 0,
                                adaptiveHeight: false,
                            });

                            // Custom dots functionality
                            $('.news-dots .pagination-dot').on('click', function(e) {
                                e.preventDefault();
                                var slideIndex = $(this).data('slide');
                                $('#news-slider').slick('slickGoTo', slideIndex);

                                // Update active dot
                                $('.news-dots .pagination-dot').removeClass('active');
                                $(this).addClass('active');
                            });

                            // Update dots on slide change
                            $('#news-slider').on('afterChange', function(event, slick, currentSlide) {
                                $('.news-dots .pagination-dot').removeClass('active');
                                $('.news-dots .pagination-dot').eq(currentSlide).addClass('active');
                            });
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
