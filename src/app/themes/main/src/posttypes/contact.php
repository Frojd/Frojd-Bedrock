<?php

function register_contact() {

    $single = _x('Contact', 'Post type single string', 'sage');
    $plural = _x('Contacts', 'Post type plural string', 'sage');
    $labels = post_type_labels($single, $plural);

    $args = array(
        'label' => __('Contact', 'sage'),
        'description' => __('Contact person', 'sage'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions',),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => false,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'menu_icon' => 'dashicons-id-alt',
    );

    register_post_type('contact', $args);

}

add_action('init', 'register_contact', 0);
