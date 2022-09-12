<?php

namespace App\GravityForms;

/**
 * Prevent storing of ip addresses
 */
add_filter('gform_ip_address', '__return_empty_string');

/**
 * Remove support for some fields in gravity forms
 */
add_filter('gform_add_field_buttons', function($groups) {
    $excludeGroups = ['pricing_fields', 'post_fields'];
    $excludeTypes = [
        'name', 'page', 'address', 'list', 'html', 'captcha', 'hidden'
    ];

    foreach($groups as $i => $group) {
        if(in_array($group['name'], $excludeGroups)) {
            unset($groups[$i]);
            continue;
        }
        foreach($group['fields'] as $j => $field) {
            if(in_array($field['data-type'], $excludeTypes)) {
                unset($groups[$i]['fields'][$j]);
            }
        }
    }

    return $groups;
});

/**
 * Add classes to gf fields
 */
add_filter('gform_field_css_class', function ($classes, $field, $form) {
    if (isset($field->size))
        $classes .= ' gfield_size_' . $field->size;

    if (isset($field->type))
        $classes .= ' gfield_' . $field->type;

    return $classes;
}, 10, 3);

/**
 * Add new wrapper for gravity forms blocks
 */
add_filter( 'render_block', function($block_content, $block) {
    if($block['blockName'] == 'gravityforms/form') {
        $block_content = '<div class="gravity-forms__wrapper">' . $block_content . '</div>';
    }
    return $block_content;
}, 10, 2);
