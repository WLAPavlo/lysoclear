<?php
$layout_type = get_sub_field('layout_type');
$content_alignment = get_sub_field('content_alignment') ?: 'center';
$block_theme = get_sub_field('block_theme') ?: 'light';
$image = get_sub_field('content_image');
$title = get_sub_field('content_title');
$content = get_sub_field('content_text');
$button = get_sub_field('content_button');

// Перевірки в коді замість required полів
if (!$image || !$title || !$content) {
    return; // Не виводимо блок якщо немає основного контенту
}

// Build section classes based on theme
$section_classes = [
    'flexible-block',
    'flexible-block--' . $layout_type,
    'flexible-block--theme-' . $block_theme,
    'flexible-block--content-' . $content_alignment
];

$section_class = implode(' ', array_filter($section_classes));
?>

<div class="<?php echo esc_attr($section_class); ?>">
    <!-- Image section - takes exactly 50% width -->
    <div class="flexible-block__image-wrap">
        <div class="flexible-block__image">
            <?php echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'flexible-block__img']); ?>
        </div>
    </div>

    <!-- Content section - takes exactly 50% width -->
    <div class="flexible-block__content-wrap">
        <div class="flexible-block__content">
            <h3 class="flexible-block__title"><?php echo esc_html($title); ?></h3>
            <div class="flexible-block__text">
                <?php echo $content; ?>
            </div>
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
