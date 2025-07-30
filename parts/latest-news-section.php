<?php

$posts_per_page_admin = get_option('posts_per_page'); // For pagination calculation
$posts_per_page_display = 3; // Always show 3 posts on home page

$latest_posts = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page_display,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
    'paged' => 1, // Always start from page 1 for home page
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

                    <!-- Home News Slider with AJAX pagination -->
                    <div class="home-news-slider" id="home-news-slider">
                        <div class="home-news-slide">
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
                        </div>

                        <?php
                        // Calculate total pages based on admin setting but show 3 per slide
                        $total_posts_query = new WP_Query([
                            'post_type' => 'post',
                            'posts_per_page' => -1,
                            'post_status' => 'publish',
                            'fields' => 'ids'
                        ]);
                        $total_posts = $total_posts_query->found_posts;
                        wp_reset_postdata();

                        // Calculate how many slides we need (3 posts per slide)
                        $max_pages = ceil($total_posts / $posts_per_page_display);

                        // But limit by admin setting for total posts to show
                        $max_posts_to_show = $posts_per_page_admin;
                        $actual_max_pages = ceil($max_posts_to_show / $posts_per_page_display);
                        $max_pages = min($max_pages, $actual_max_pages);

                        // Get additional pages if there are more posts
                        $current_page = 2;
                        while ($current_page <= $max_pages) {
                            $additional_posts = new WP_Query([
                                'post_type' => 'post',
                                'posts_per_page' => $posts_per_page_display,
                                'post_status' => 'publish',
                                'orderby' => 'date',
                                'order' => 'DESC',
                                'paged' => $current_page,
                            ]);

                            if ($additional_posts->have_posts()) { ?>
                                <div class="home-news-slide">
                                    <div class="home-news-grid">
                                        <div class="grid-x">
                                            <?php while ($additional_posts->have_posts()) {
                                                $additional_posts->the_post();
                                                ?>
                                                <div class="cell large-4 medium-4 small-12">
                                                    <?php get_template_part('parts/loop', 'post'); ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            wp_reset_postdata();
                            $current_page++;
                        }
                        ?>
                    </div>

                    <!-- Custom dots pagination for home news slider -->
                    <?php if ($max_pages > 1) { ?>
                        <div class="pagination-wrapper">
                            <div class="pagination-dots home-news-dots">
                                <?php for ($i = 1; $i <= $max_pages; $i++) { ?>
                                    <a href="#" class="pagination-dot <?php echo $i === 1 ? 'active' : ''; ?>" data-slide="<?php echo $i - 1; ?>"></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <script>
                        jQuery(document).ready(function($) {
                            // Initialize home news slider
                            if ($('#home-news-slider').length && typeof $.fn.slick === 'function') {
                                $('#home-news-slider').slick({
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
                                $('.home-news-dots .pagination-dot').on('click', function(e) {
                                    e.preventDefault();
                                    var slideIndex = $(this).data('slide');
                                    $('#home-news-slider').slick('slickGoTo', slideIndex);

                                    // Update active dot
                                    $('.home-news-dots .pagination-dot').removeClass('active');
                                    $(this).addClass('active');
                                });

                                // Update dots on slide change
                                $('#home-news-slider').on('afterChange', function(event, slick, currentSlide) {
                                    $('.home-news-dots .pagination-dot').removeClass('active');
                                    $('.home-news-dots .pagination-dot').eq(currentSlide).addClass('active');
                                });
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </section>
    <?php wp_reset_postdata(); ?>
<?php } ?>
