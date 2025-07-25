<?php
/**
 * Page.
 */
get_header(); ?>

<main class="main-content">
    <div class="grid-container">
        <div class="grid-x grid-margin-x">
            <!-- BEGIN of page content -->
            <div class="large-8 medium-8 small-12 cell">
                <?php if (have_posts()) { ?>
                    <?php while (have_posts()) {
                        the_post(); ?>
                        <article <?php post_class('entry'); ?>>
                            <h1 class="page-title entry__title"><?php the_title(); ?></h1>
                            <?php if (has_post_thumbnail()) { ?>
                                <div title="<?php the_title_attribute(); ?>" class="entry__thumb">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php } ?>
                            <div class="entry__content">
                                <?php the_content('', true); ?>
                            </div>
                        </article>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- END of page content -->

            <!-- BEGIN of sidebar -->
            <div class="large-4 medium-4 small-12 cell sidebar">
                <?php get_sidebar('right'); ?>
            </div>
            <!-- END of sidebar -->
        </div>
    </div>
</main>

<?php get_footer(); ?>
