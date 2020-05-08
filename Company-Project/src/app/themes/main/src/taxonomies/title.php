<?php

function register_title() {
    
    $single = _x('Title', 'Taxonomy single string', 'sage');
    $plural = _x('Titles', 'Taxonomy plural string', 'sage');
    $labels = taxonomy_labels($single, $plural);

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => false,
        'show_in_nav_menus' => false,
        'show_tagcloud' => false,
    );

    register_taxonomy('title', array('contact'), $args);
}

add_action('init', 'register_title', 0);
