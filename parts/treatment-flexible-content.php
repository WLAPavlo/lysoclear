<?php if (have_rows('flexible_content')) { ?>
    <section class="treatment-flexible-content">
        <?php while (have_rows('flexible_content')) {
            the_row();
            $layout = get_row_layout();

            if ('image_text' == $layout) {
                $layout_type = get_sub_field('layout_type');
                $content_alignment = get_sub_field('content_alignment') ?: 'center';
                $block_theme = get_sub_field('block_theme') ?: 'light';

                $image = get_sub_field('content_image');
                $title = get_sub_field('content_title');
                $content = get_sub_field('content_text');
                $button = get_sub_field('content_button');

                // Build section classes
                $section_classes = [
                    'treatment-flexible-block',
                    'treatment-flexible-block--' . $layout_type,
                    'treatment-flexible-block--theme-' . $block_theme,
                    'treatment-flexible-block--content-' . $content_alignment
                ];
                $section_class = implode(' ', array_filter($section_classes));
                ?>
                <div class="<?php echo esc_attr($section_class); ?>">
                    <!-- Image section - takes exactly 50% width -->
                    <div class="treatment-flexible-block__image-wrap">
                        <?php if ($image) { ?>
                            <div class="treatment-flexible-block__image">
                                <?php echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'treatment-flexible-block__img']); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Content section - takes exactly 50% width with theme background -->
                    <div class="treatment-flexible-block__content-wrap">
                        <div class="treatment-flexible-block__content">
                            <?php if ($title) { ?>
                                <h3 class="treatment-flexible-block__title"><?php echo esc_html($title); ?></h3>
                            <?php } ?>
                            <?php if ($content) { ?>
                                <div class="treatment-flexible-block__text">
                                    <?php echo $content; ?>
                                </div>
                            <?php } ?>
                            <?php if ($button && !empty($button['url'])) { ?>
                                <div class="treatment-flexible-block__button">
                                    <a href="<?php echo esc_url($button['url']); ?>"
                                       class="button treatment-flexible-block__btn"
                                        <?php echo $button['target'] ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                                        <?php echo esc_html($button['title']); ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
    </section>
<?php } ?>
