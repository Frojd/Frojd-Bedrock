<?php

function register_contact() {

    $labels = array(
        'name' => _x('Contact', 'Post Type General Name', 'sage'),
        'singular_name' => _x('Contact', 'Post Type Singular Name', 'sage'),
        'menu_name' => __('Contact', 'sage'),
        //'name_admin_bar' => __('Post Type', 'sage'),
        //'archives' => __('Item Archives', 'sage'),
        //'parent_item_colon' => __('Parent Item:', 'sage'),
        //'all_items' => __('All Items', 'sage'),
        //'add_new_item' => __('Add New Item', 'sage'),
        //'add_new' => __('Add New', 'sage'),
        //'new_item' => __('New Item', 'sage'),
        //'edit_item' => __('Edit Item', 'sage'),
        //'update_item' => __('Update Item', 'sage'),
        //'view_item' => __('View Item', 'sage'),
        //'search_items' => __('Search Item', 'sage'),
        //'not_found' => __('Not found', 'sage'),
        //'not_found_in_trash' => __('Not found in Trash', 'sage'),
        //'featured_image' => __('Featured Image', 'sage'),
        //'set_featured_image' => __('Set featured image', 'sage'),
        //'remove_featured_image' => __('Remove featured image', 'sage'),
        //'use_featured_image' => __('Use as featured image', 'sage'),
        //'insert_into_item' => __('Insert into item', 'sage'),
        //'uploaded_to_this_item' => __('Uploaded to this item', 'sage'),
        //'items_list' => __('Items list', 'sage'),
        //'items_list_navigation' => __('Items list navigation', 'sage'),
        //'filter_items_list' => __('Filter items list', 'sage'),
    );

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
