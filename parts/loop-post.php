<!-- BEGIN of News Post -->
<article class="<?php echo is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item'; ?>">
    <?php if (has_post_thumbnail()) { ?>
        <div class="<?php echo is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item'; ?>__image">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_post_thumbnail('medium_large', ['class' => (is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item') . '__img']); ?>
            </a>
        </div>
    <?php } ?>

    <div class="<?php echo is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item'; ?>__content">
        <h3 class="<?php echo is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item'; ?>__title">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="<?php echo is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item'; ?>__meta">
            <span class="<?php echo is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item'; ?>__date"><?php echo get_the_date('F j, Y'); ?></span>
            <span class="<?php echo is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item'; ?>__comments">
                <?php
                $comments_count = get_comments_number();
                if (0 == $comments_count) {
                    echo '(0) Comments';
                } elseif (1 == $comments_count) {
                    echo '(1) Comment';
                } else {
                    echo '(' . $comments_count . ') Comments';
                }
                ?>
            </span>
        </div>

        <div class="<?php echo is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item'; ?>__excerpt">
            <?php the_excerpt(); ?>
        </div>

        <?php
        // Check if post content contains custom button HTML
        $content = get_the_content();
        $button_pattern = '/<a[^>]*style[^>]*>.*?Learn more.*?<\/a>/i';

        if (preg_match($button_pattern, $content, $matches)) {
            // If custom button found in content, display it
            echo '<div class="' . (is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item') . '__button">' . $matches[0] . '</div>';
        } else {
            // Default button if no custom button found
            echo '<div class="' . (is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item') . '__button">';
            echo '<a href="' . get_permalink() . '" class="button ' . (is_home() || is_front_page() || (is_page_template('templates/template-home.php')) ? 'home-item' : 'news-item') . '__btn">Learn more</a>';
            echo '</div>';
        }
        ?>
    </div>
</article>
<!-- END of News Post -->
