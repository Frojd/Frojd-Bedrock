<?php

namespace App\ACF;

/**
 * Hide acf in production
 */
add_filter('acf/settings/show_admin', function () {
    return WP_ENV == 'development';
});

/**
 * Save and load ACF Json in theme root.
 */
add_filter('acf/settings/save_json', function ($path) {
    return get_template_directory() .'/acf-json/';
});

add_filter('acf/settings/load_json', function ($path) {
    return [get_template_directory() .'/acf-json/'];
});

add_action('after_setup_theme', function () {
    /**
     * Register acf setting pages
     */
    if (function_exists('\acf_add_options_page')) {
        \acf_add_options_page([
            'page_title'    => __('Site settings', 'sage'),
            'menu_title'    => __('Site settings', 'sage'),
            'menu_slug'     => 'site-settings',
            'parent_slug'   => 'options-general.php',
        ]);
    }
});