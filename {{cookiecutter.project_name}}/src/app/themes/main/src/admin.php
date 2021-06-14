<?php

namespace App\Admin;

/**
 * Theme customizer
 */
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    // Add postMessage support
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
});

/**
 * Customizer JS
 */
add_action('customize_preview_init', function () {
    wp_enqueue_script('sage/customizer.js', \App\asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});

/**
 * Add name to default template
 */
add_filter('default_page_template_title', function() {
    return __('Default template', 'sage');
});

/**
 * Add template to page list view
 */
add_filter('manage_pages_columns', function($columns) {
    return array_merge($columns, [
        'template' => __('Template', 'sage')
    ]);
});
add_filter('manage_edit-page_sortable_columns', function($columns) {
    $columns['template'] = 'template';
    return $columns;
});
add_action('manage_pages_custom_column', function($column, $postId) {
    switch ($column) {
        case 'template':
            $templates = get_page_templates();
            $template = get_page_template_slug($postId);
            $name = array_search($template, $templates);
            echo $name;
            break;
        default:
            break;
    }
}, 10, 2);

/**
 * Add custom sorting to columns
 */
add_action('pre_get_posts', function($query) {
    if (!is_admin())
        return;

    $orderby = $query->get('orderby');
    switch ($orderby) {
        case 'template':
            $query->set('meta_key', '_wp_page_template');
            $query->set('orderby', 'meta_value');
            break;
        default:
            break;
    }
});

/**
 * For making taxonomy option meta_box_cb = false work in Gutenberg
 */
add_filter('rest_prepare_taxonomy', function($response, $taxonomy, $request) {
    $context = !empty($request['context']) ? $request['context'] : 'view';
    // Context is edit in the editor
    if($context === 'edit' && $taxonomy->meta_box_cb === false) {
        $data = $response->get_data();
        $data['visibility']['show_ui'] = false;
        $response->set_data($data);
    }
    return $response;
}, 10, 3);
