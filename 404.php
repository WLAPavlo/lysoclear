<?php
/**
 * The template for displaying 404 pages (Not Found).
 */
get_header(); ?>
<!-- BEGIN of 404 page -->
<div class="grid-container not-found">
    <div class="grid-x">
        <div class="cell text-center">
            <h1><?php _e('404: Page Not Found', 'fwp'); ?></h1>
            <h3><?php _e('Keep on looking...', 'fwp'); ?></h3>
            <p><?php printf(__('Double check the URL or head back to the <a class="button" href="%1s">HOMEPAGE</a>', 'fwp'), home_url('/')); ?></p>
        </div>
    </div>
</div>
<!-- END of 404 page -->
<?php get_footer(); ?>
