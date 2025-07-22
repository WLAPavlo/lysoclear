<?php
/**
 * Footer.
 */
?>

<!-- BEGIN of footer -->
<footer class="footer">
    <?php if (get_field('footer_cta_text', 'options') || get_field('footer_button', 'options')) { ?>
        <div class="footer__cta">
            <div class="grid-container menu-grid-container">
                <div class="grid-x grid-margin-x">
                    <div class="cell medium-8 text-center medium-text-left">
                        <?php if ($cta_text = get_field('footer_cta_text', 'options')) { ?>
                            <h3><?php echo esc_html($cta_text); ?></h3>
                        <?php } ?>
                    </div>
                    <div class="cell medium-4 text-center">
                        <?php
                        $button = get_field('footer_button', 'options');
                        if ($button && !empty($button['url'])) { ?>
                            <a href="<?php echo esc_url($button['url']); ?>"
                               class="button"
                                <?php echo $button['target'] ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                                <?php echo esc_html($button['title']); ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="footer__bottom">
        <div class="grid-container">
            <div class="grid-x grid-margin-x">
                <div class="cell text-center">
                    <div class="footer-info">
                        <?php
                        $name = get_field('name', 'options');
                        $copyright = get_field('copyright', 'options');
                        $address = get_field('address', 'options');
                        $email = get_field('email', 'options');
                        $phone = get_field('phone', 'options');

                        ?>



                        <?php if ($name && $copyright) { ?>
                            <span><?php echo esc_html($name); ?>  <?php echo esc_html($copyright); ?></span>
                        <?php } ?>

                        <?php if ($address) { ?>
                            <span><?php echo esc_html($address); ?></span>
                        <?php } ?>

                        <?php if ($email) { ?>
                            <span><?php echo esc_html(strtoupper($email)); ?></span>
                        <?php } ?>

                        <?php if ($phone) { ?>
                            <span><?php echo esc_html($phone); ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- END of footer -->

<?php wp_footer(); ?>
</body>
</html>
