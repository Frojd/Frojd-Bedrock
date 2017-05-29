<?php

function taxonomy_labels($single, $plural = null) {
    $plural = is_null($plural) ? $single : $plural;
    return array(
        'name'                          => $plural,
        'singular_name'                 => $single,
        'menu_name'                     => $plural,
        'all_items'                     => sprintf(__('All %s', 'sage'), $plural),
        'edit_item'                     => sprintf(__('Edit %s', 'sage'), $single),
        'view_item'                     => sprintf(__('View %s', 'sage'), $single),
        'update_item'                   => sprintf(__('Update %s', 'sage'), $single),
        'add_new_item'                  => sprintf(__('Add new %s', 'sage'), $single),
        'new_item_name'                 => sprintf(__('New %s Name', 'sage'), $single),
        'parent_item'                   => sprintf(__('Parent %s', 'sage'), $single),
        'parent_item_colon'             => sprintf(__('Parent %s:', 'sage'), $single),
        'search_items'                  => sprintf(__('Search %s', 'sage'), $plural),
        'popular_items'                 => sprintf(__('Popular %s', 'sage'), $plural),
        'separate_items_with_commas'    => sprintf(__('Separate %s with commas', 'sage'), $plural),
        'add_or_remove_items'           => sprintf(__('Add or remove %s', 'sage'), $plural),
        'choose_from_most_used'         => sprintf(__('Choose from the most used %s', 'sage'), $plural),
        'not_found'                     => sprintf(__('No %s found', 'sage'), $plural)
    );
}
