<!-- BEGIN of News Post -->
<article class="news-item">
    <?php if (has_post_thumbnail()) { ?>
        <div class="news-item__image">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_post_thumbnail('medium_large', ['class' => 'news-item__img']); ?>
            </a>
        </div>
    <?php } ?>

    <div class="news-item__content">
        <h3 class="news-item__title">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="news-item__meta">
            <span class="news-item__date"><?php echo get_the_date('F j, Y'); ?></span>
            <span class="news-item__comments">
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

        <div class="news-item__excerpt">
            <?php the_excerpt(); ?>
        </div>

        <?php
        // Check if post content contains custom button HTML
        $content = get_the_content();
        $button_pattern = '/<a[^>]*style[^>]*>.*?Learn more.*?<\/a>/i';

        if (preg_match($button_pattern, $content, $matches)) {
            // If custom button found in content, display it
            echo '<div class="news-item__button">' . $matches[0] . '</div>';
        } else {
            // Default button if no custom button found
            echo '<div class="news-item__button">';
            echo '<a href="' . get_permalink() . '" class="button news-item__btn">Learn more</a>';
            echo '</div>';
        }
        ?>
    </div>
</article>
<!-- END of News Post -->
