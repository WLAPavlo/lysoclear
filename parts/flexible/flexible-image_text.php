<?php
$layout_type = get_sub_field('layout_type');
$block_theme = get_sub_field('block_theme') ?: 'light';
$image = get_sub_field('content_image');
$title = get_sub_field('content_title');
$content = get_sub_field('content_text');
$button = get_sub_field('content_button');

if (!$image || !$title || !$content) {
    return;
}

// Determine if we're on treatment page
$is_treatment_page = is_page_template('templates/template-treatment.php');
$block_prefix = $is_treatment_page ? 'treatment-flexible-block' : 'flexible-block';

// Build section classes based on theme
$section_classes = [
    $block_prefix,
    $block_prefix . '--' . $layout_type,
    $block_prefix . '--theme-' . $block_theme
];

$section_class = implode(' ', $section_classes);
?>

<div class="<?php echo esc_attr($section_class); ?>">
    <!-- Image section - takes exactly 50% width -->
    <div class="<?php echo $block_prefix; ?>__image-wrap">
        <div class="<?php echo $block_prefix; ?>__image">
            <?php
            if ($image) {
                echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => $block_prefix . '__img']);
            } else {
                echo '<img src="' . IMAGE_PLACEHOLDER . '" class="' . $block_prefix . '__img" alt="Placeholder">';
            }
            ?>
        </div>
    </div>

    <!-- Content section - takes exactly 50% width -->
    <div class="<?php echo $block_prefix; ?>__content-wrap">
        <div class="<?php echo $block_prefix; ?>__content">
            <h3 class="<?php echo $block_prefix; ?>__title"><?php echo esc_html($title); ?></h3>
            <div class="<?php echo $block_prefix; ?>__text">
                <?php echo $content; ?>
            </div>
            <?php if ($button && !empty($button['url'])) { ?>
                <div class="<?php echo $block_prefix; ?>__button">
                    <a href="<?php echo esc_url($button['url']); ?>"
                       class="button <?php echo $block_prefix; ?>__btn"
                        <?php echo $button['target'] ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                        <?php echo esc_html($button['title']); ?>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
