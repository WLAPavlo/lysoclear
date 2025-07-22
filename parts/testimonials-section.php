<?php if (have_rows('testimonials_items')) { ?>
    <section class="testimonials-section">
        <div class="grid-container">
            <div class="grid-x grid-margin-x">
                <div class="cell">
                    <div class="testimonials-slider" id="testimonials-slider">
                        <?php while (have_rows('testimonials_items')) {
                            the_row();
                            $testimonial_text = get_sub_field('testimonial_text');
                            $testimonial_author = get_sub_field('testimonial_author');
                            ?>
                            <div class="testimonial-slide">
                                <div class="testimonial-item">
                                    <div class="testimonial-quote">
                                        <svg class="quote-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z" fill="currentColor"/>
                                        </svg>
                                    </div>
                                    <?php if ($testimonial_text) { ?>
                                        <blockquote class="testimonial-text">
                                            "<?php echo esc_html($testimonial_text); ?>"
                                        </blockquote>
                                    <?php } ?>
                                    <?php if ($testimonial_author) { ?>
                                        <cite class="testimonial-author">
                                            <?php echo esc_html($testimonial_author); ?>
                                        </cite>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Testimonials Slider
            jQuery(document).ready(function($) {
                const $testimonialsSlider = $('#testimonials-slider');

                if ($testimonialsSlider.length && typeof $.fn.slick === 'function') {
                    $testimonialsSlider.slick({
                        dots: true,
                        arrows: false,
                        infinite: true,
                        speed: 600,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 5000,
                        pauseOnHover: true,
                        fade: false,
                        cssEase: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
                        adaptiveHeight: true,
                        responsive: [
                            {
                                breakpoint: 768,
                                settings: {
                                    autoplaySpeed: 4000,
                                    speed: 500
                                }
                            }
                        ]
                    });

                    // Pause autoplay when user interacts with dots
                    $testimonialsSlider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
                        // Add smooth transition effect
                        $(this).find('.testimonial-item').removeClass('animate-in');
                    });

                    $testimonialsSlider.on('afterChange', function(event, slick, currentSlide) {
                        // Add animation class to current slide
                        $(this).find('.slick-current .testimonial-item').addClass('animate-in');
                    });

                    // Initialize first slide animation
                    $testimonialsSlider.find('.slick-current .testimonial-item').addClass('animate-in');
                }
            });
        </script>
    </section>
<?php } ?>
