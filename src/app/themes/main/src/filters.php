<?php

namespace App;

use Roots\Sage\Template;
use Roots\Sage\Template\Wrapper;

/**
 * Determine which pages should NOT display the sidebar
 * @link https://codex.wordpress.org/Conditional_Tags
 */
add_filter('sage/display_sidebar', function ($display) {
    // The sidebar will NOT be displayed if ANY of the following return true
    return $display ? !in_array(true, [
        is_404(),
        is_front_page(),
        is_page_template('templates/template-custom.php'),
    ]) : $display;
});

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    // Add page slug if it doesn't exist
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    // Add class if sidebar is active
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    return $classes;
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Read more', 'sage') . '</a>';
});

/**
 * Use theme wrapper
 */
add_filter('template_include', function ($main) {
    if (!is_string($main) && !(is_object($main) && method_exists($main, '__toString'))) {
        return $main;
    }
    return ((new Template(new Wrapper($main)))->layout());
}, 109);

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

/**
 * Sanetize filenames on upload
 */
add_filter('sanitize_file_name', function($filename) {
    return preg_replace('/[^a-zA-Z-_]/', '', $filename);
}, 20);
