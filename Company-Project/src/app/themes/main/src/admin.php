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
 * Hide post from admin and add extra seperators
 */
add_action('admin_menu', function() {
    global $menu;

    remove_menu_page('edit.php');

    $menu[] = ['', 'read', 'separator10', '', 'wp-menu-separator'];
    $menu[] = ['', 'read', 'separator11', '', 'wp-menu-separator'];
});

add_action('admin_bar_menu', function($wpAdminBar) {
    $wpAdminBar->remove_node('new-post');
}, 999);

add_action('wp_dashboard_setup', function() {
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
});

add_action('load-post-new.php', function() {
    if (get_current_screen()->post_type == 'post') {
        wp_redirect(admin_url('post-new.php?post_type=page'));
        die;
    }
});

/**
 * Hide comments from admin
 */
add_action('admin_menu', function() {
    remove_menu_page( 'edit-comments.php' );
});

add_action('wp_before_admin_bar_render', function() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
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
