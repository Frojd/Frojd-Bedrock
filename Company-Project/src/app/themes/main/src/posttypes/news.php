<?php

namespace App\Posttypes\News;

add_action('init', function() {
    $single = _x('News', 'Post type single string', 'sage');
    $plural = _x('News', 'Post type plural string', 'sage');
    $labels = \App\Posttypes\get_labels($single, $plural);

    $args = [
        'label' => __('News', 'sage'),
        'description' => '',
        'labels' => $labels,
        'supports' => [
            'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author'
        ],
        'rewrite' => [
            'slug' => 'news',
        ],
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'hierarchical' => false,
        'capability_type' => 'page',
        'taxonomies' => ['category', 'post_tag'],
        'menu_icon' => 'dashicons-testimonial',
    ];
    register_post_type('news', $args);
}, 0);
