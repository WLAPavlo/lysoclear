<?php

use theme\Util;

// Register Testimonials Custom Post Type
add_action('init', function () {
    Util::registerPostType('testimonial', 'Testimonial', 'Testimonials', [
        'public' => true,
        'hierarchical' => false,
        'menu_icon' => 'dashicons-format-quote',
        'menu_position' => 25,
        'supports' => [
            'title',
            'editor',
            'thumbnail',
        ],
        'show_in_rest' => true,
        'has_archive' => false,
        'publicly_queryable' => false,
        'show_in_nav_menus' => false,
    ]);
});
