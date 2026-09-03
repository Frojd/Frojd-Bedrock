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

/**
 * Bump a timestamp whenever the site notice is saved. The notice partial hashes
 * this into the notice id, so editing the notice makes it reappear for everyone
 * who had previously dismissed it.
 */
add_filter('acf/update_value/name=notice', function ($value, $post_id, $field, $original) {
    update_option('notice_updated_date', date('Y-m-d H:i:s'));
    return $value;
}, 10, 4);