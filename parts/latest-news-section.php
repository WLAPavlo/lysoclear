<?php
// Get posts count from WordPress Reading Settings (standard posts_per_page)
$posts_count = get_option('posts_per_page', 10);

$latest_posts = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => $posts_count,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
]);

if ($latest_posts->have_posts()) { ?>
    <section class="latest-news-section">
        <div class="grid-container">
            <div class="grid-x grid-margin-x">
                <div class="cell">
                    <!-- Horizontal line -->
                    <div class="section-divider"></div>

                    <!-- Section title -->
                    <h2 class="section-title">Latest News from Lysoclear</h2>

                    <!-- Posts slider or grid -->
                    <?php if ($posts_count > 3) { ?>
                        <!-- Slider for more than 3 posts -->
                        <div class="news-slider" id="latest-news-slider">
                            <?php while ($latest_posts->have_posts()) {
                                $latest_posts->the_post();
                                ?>
                                <div class="news-slide">
                                    <?php get_template_part('parts/loop', 'post'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <!-- Static grid for 3 or fewer posts -->
                        <div class="news-grid">
                            <div class="grid-x grid-margin-x">
                                <?php while ($latest_posts->have_posts()) {
                                    $latest_posts->the_post();
                                    ?>
                                    <div class="cell medium-4 small-12">
                                        <?php get_template_part('parts/loop', 'post'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <script>
                        jQuery(document).ready(function($) {
                            // Initialize news slider only if more than 3 posts
                            <?php if ($posts_count > 3) { ?>
                            if ($('#latest-news-slider').length && typeof $.fn.slick === 'function') {
                                $('#latest-news-slider').slick({
                                    dots: true,
                                    arrows: false, // Remove arrows
                                    infinite: true,
                                    speed: 600,
                                    slidesToShow: 3,
                                    slidesToScroll: 3,
                                    autoplay: false,
                                    pauseOnHover: true,
                                    rows: 0,
                                    responsive: [
                                        {
                                            breakpoint: 1024,
                                            settings: {
                                                slidesToShow: 2,
                                                slidesToScroll: 2
                                            }
                                        },
                                        {
                                            breakpoint: 768,
                                            settings: {
                                                slidesToShow: 1,
                                                slidesToScroll: 1
                                            }
                                        }
                                    ]
                                });
                            }
                            <?php } ?>
                        });
                    </script>

                </div>
            </div>
        </div>
    </section>
    <?php wp_reset_postdata(); ?>
<?php } ?>
