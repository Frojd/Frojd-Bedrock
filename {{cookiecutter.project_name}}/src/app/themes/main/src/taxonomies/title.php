<?php

namespace App\Taxonomies\Title;

add_action('init', function() {
    $single = _x('Title', 'Taxonomy single string', 'sage');
    $plural = _x('Titles', 'Taxonomy plural string', 'sage');
    $labels = \App\Taxonomies\get_labels($single, $plural);

    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'show_in_nav_menus' => false,
        'show_tagcloud' => false,
    ];
    register_taxonomy('title', ['contact'], $args);
}, 0);
