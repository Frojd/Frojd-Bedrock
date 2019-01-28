<?php

function post_type_labels($single, $plural = null) {
    $plural = is_null($plural) ? $single : $plural;
    return array(
        'name'                  => $plural,
        'singular_name'         => $single,
        'menu_name'             => $plural,
        'name_admin_bar'        => $single,
        'all_items'             => sprintf(__('All %s', 'sage'), $plural),
        'add_new'               => __('Add new', 'sage'),
        'add_new_item'          => sprintf(__('Add new %s', 'sage'), $single),
        'edit_item'             => sprintf(__('Edit %s', 'sage'), $single),
        'new_item'              => sprintf(__('New %s', 'sage'), $single),
        'view_item'             => sprintf(__('View %s', 'sage'), $single),
        'search_items'          => sprintf(__('Search %s', 'sage'), $single),
        'not_found'             => sprintf(__('No %s found', 'sage'), $plural),
        'not_found_in_trash'    => sprintf(__('No %s found in Trash', 'sage'), $plural),
        'parent_item_colon'     => sprintf(__('Parent %s', 'sage'), $single)
    );
}
