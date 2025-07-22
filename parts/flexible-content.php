<?php if (have_rows('flexible_content')) { ?>
    <?php
    // Function to determine if color is dark
    if (!function_exists('is_dark_color')) {
        function is_dark_color($hex_color) {
            // Check if color is empty or null
            if (empty($hex_color)) {
                return false; // Default to light if no color
            }

            // Remove # if present
            $hex_color = ltrim($hex_color, '#');

            // Validate hex color format (should be 3 or 6 characters)
            if (!preg_match('/^[a-fA-F0-9]{3}$|^[a-fA-F0-9]{6}$/', $hex_color)) {
                return false; // Default to light if invalid format
            }

            // Convert 3-character hex to 6-character
            if (strlen($hex_color) == 3) {
                $hex_color = $hex_color[0] . $hex_color[0] . $hex_color[1] . $hex_color[1] . $hex_color[2] . $hex_color[2];
            }

            // Convert to RGB
            $r = hexdec(substr($hex_color, 0, 2));
            $g = hexdec(substr($hex_color, 2, 2));
            $b = hexdec(substr($hex_color, 4, 2));

            // Calculate luminance using standard formula
            $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

            // Return true if dark (luminance < 0.5)
            return $luminance < 0.5;
        }
    }
    ?>
    <section class="flexible-content">
        <?php while (have_rows('flexible_content')) {
            the_row();
            $layout = get_row_layout();

            if ($layout == 'image_text') {
                $layout_type = get_sub_field('layout_type');
                $background_color = get_sub_field('background_color') ?: '#ffffff';
                $image = get_sub_field('content_image');
                $title = get_sub_field('content_title');
                $content = get_sub_field('content_text');
                $button = get_sub_field('content_button');

                $is_dark_bg = is_dark_color($background_color);

                $section_class = 'flexible-block flexible-block--' . $layout_type;
                if ($is_dark_bg) {
                    $section_class .= ' flexible-block--dark';
                } else {
                    $section_class .= ' flexible-block--light';
                }
                ?>
                <div class="<?php echo $section_class; ?>" style="background-color: <?php echo esc_attr($background_color); ?>;">
                    <div class="grid-x">
                        <div class="cell medium-6 flexible-block__image-wrap">
                            <?php if ($image) { ?>
                                <div class="flexible-block__image">
                                    <?php echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'flexible-block__img']); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="cell medium-6 flexible-block__content-wrap">
                            <div class="flexible-block__content">
                                <?php if ($title) { ?>
                                    <h3 class="flexible-block__title"><?php echo esc_html($title); ?></h3>
                                <?php } ?>
                                <?php if ($content) { ?>
                                    <div class="flexible-block__text">
                                        <?php echo $content; ?>
                                    </div>
                                <?php } ?>
                                <?php if ($button && !empty($button['url'])) { ?>
                                    <div class="flexible-block__button">
                                        <a href="<?php echo esc_url($button['url']); ?>"
                                           class="button flexible-block__btn"
                                            <?php echo $button['target'] ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                                            <?php echo esc_html($button['title']); ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
    </section>
<?php } ?>
