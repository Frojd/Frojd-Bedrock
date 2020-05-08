<?php

namespace App\Posttypes\Contact;

add_action('init', function() {
    $single = _x('Contact', 'Post type single string', 'sage');
    $plural = _x('Contacts', 'Post type plural string', 'sage');
    $labels = \App\Posttypes\get_labels($single, $plural);

    $args = [
        'label' => __('Contact', 'sage'),
        'description' => __('Contact person', 'sage'),
        'labels' => $labels,
        'supports' => [
            'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author'
        ],
        'show_in_nav_menus' => false,
        'has_archive' => false,
        'exclude_from_search' => true,
        'capability_type' => 'page',
        'menu_icon' => 'dashicons-id-alt',
    ];
    register_post_type('contact', $args);
}, 0);
