<?php
/**
 * Index.
 *
 * Standard loop for the search result page
 */
get_header(); ?>

<div class="grid-container">
    <div class="grid-x">
        <div class="cell small-12">
            <!-- BEGIN of search results -->
            <main class="main-content">
                <h1 class="page-title"><?php printf(__('Search Results for: %s', 'fwp'), '<span>' . esc_html(get_search_query()) . '</span>'); ?></h1>
                <?php get_search_form(); ?>
                <?php if (have_posts()) { ?>
                    <div class="search-results-grid">
                        <div class="grid-x grid-margin-x">
                            <?php while (have_posts()) {
                                the_post(); ?>
                                <div class="cell large-4 medium-6 small-12">
                                    <?php get_template_part('parts/loop', 'post'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'fwp'); ?></p>
                <?php } ?>
                <?php foundation_pagination($wp_query); ?>
            </main>
        </div>
        <!-- END of search results -->
    </div>
</div>

<?php get_footer(); ?>
