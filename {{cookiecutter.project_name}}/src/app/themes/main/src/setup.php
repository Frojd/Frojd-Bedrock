<?php

namespace App\Setup;

use Roots\Sage\Template;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    $themeVersion = wp_get_theme()->get('Version');
    $verTag = WP_ENV == 'production' ? md5($themeVersion) : $themeVersion;

    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, $verTag);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], $verTag, true);
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link http://codex.wordpress.org/Function_Reference/register_nav_menus
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'service_navigation' => __('Service Navigation', 'sage'),
    ]);

    /**
     * Enable post thumbnails
     * @link http://codex.wordpress.org/Post_Thumbnails
     * @link http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
     * @link http://codex.wordpress.org/Function_Reference/add_image_size
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Use main stylesheet for visual editor
     * @see assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/editor.css'));

    /**
     * Localize
     */
    load_theme_textdomain('sage', get_template_directory() . '/lang' );

    /**
     * Register acf setting pages
     */
    if (function_exists('\acf_add_options_page')) {
        \acf_add_options_page([
            'page_title' 	=> __('Site settings', 'sage'),
            'menu_title' 	=> __('Site settings', 'sage'),
            'menu_slug' 	=> 'site-settings',
            'parent_slug' 	=> 'options-general.php',
        ]);
    }
});

/**
 * Purge nginx cache on option update
 */
add_action('updated_option', function() {
    if(class_exists('NginxCache')) {
        $nginx = new \NginxCache;
        $nginx->purge_zone_once();
    }
});
