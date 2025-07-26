<?php
// Get approved comments from WordPress
$comments = get_comments([
    'status' => 'approve',
    'number' => 10, // Limit to 10 testimonials
    'orderby' => 'comment_date',
    'order' => 'DESC',
]);

if (!empty($comments)) { ?>
    <section class="testimonials-section">
        <div class="grid-container">
            <div class="grid-x grid-margin-x">
                <div class="cell">
                    <div class="testimonials-slider" id="testimonials-slider">
                        <?php foreach ($comments as $comment) { ?>
                            <div class="testimonial-slide">
                                <div class="testimonial-item">
                                    <div class="testimonial-quote">
                                        <svg class="quote-icon" width="60" height="60" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"
                                                fill="currentColor" />
                                        </svg>
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text">
                                            <?php echo esc_html($comment->comment_content); ?>
                                        </p>
                                        <p class="testimonial-author">
                                            <?php echo esc_html($comment->comment_author); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <script>
                        jQuery(document).ready(function($) {
                            // Initialize testimonials slider with simple slide effect
                            if ($('#testimonials-slider').length && typeof $.fn.slick === 'function') {
                                $('#testimonials-slider').slick({
                                    dots: true,
                                    arrows: false,
                                    infinite: true,
                                    speed: 600,
                                    fade: false, // Changed from fade to slide
                                    cssEase: 'ease',
                                    autoplay: false,
                                    pauseOnHover: true,
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    rows: 0,
                                    dotsClass: 'slick-dots',
                                    rtl: false, // Slide to the right
                                });

                                // Initialize first slide animation
                                $('#testimonials-slider .slick-current .testimonial-item').addClass('animate-in');

                                // Handle slide change animations
                                $('#testimonials-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
                                    // Remove animation from all slides
                                    $('.testimonial-item').removeClass('animate-in');
                                });

                                $('#testimonials-slider').on('afterChange', function(event, slick, currentSlide) {
                                    // Add animation to current slide
                                    $(slick.$slides[currentSlide]).find('.testimonial-item').addClass('animate-in');
                                });
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
