<?php

function register_title() {
    $labels = array(
        'name' => __('Title', 'sage'),
        'singular_name' => __('Title', 'sage'),
        'menu_name' => __('Titles', 'sage'),
        // 'all_items' => __('All Items', 'sage'),
        // 'parent_item' => __('Parent Item', 'sage'),
        // 'parent_item_colon' => __('Parent Item:', 'sage'),
        // 'new_item_name' => __('New Item Name', 'sage'),
        // 'add_new_item' => __('Add New Item', 'sage'),
        // 'edit_item' => __('Edit Item', 'sage'),
        // 'update_item' => __('Update Item', 'sage'),
        // 'view_item' => __('View Item', 'sage'),
        // 'separate_items_with_commas' => __('Separate items with commas', 'sage'),
        // 'add_or_remove_items' => __('Add or remove items', 'sage'),
        // 'choose_from_most_used' => __('Choose from the most used', 'sage'),
        // 'popular_items' => __('Popular Items', 'sage'),
        // 'search_items' => __('Search Items', 'sage'),
        // 'not_found' => __('Not Found', 'sage'),
        // 'no_terms' => __('No items', 'sage'),
        // 'items_list' => __('Items list', 'sage'),
        // 'items_list_navigation' => __('Items list navigation', 'sage'),
    );

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
