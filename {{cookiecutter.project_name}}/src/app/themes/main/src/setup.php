<?php

namespace App\Setup;

use Roots\Sage\Template;
use Roots\Sage\Template\Wrapper;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    $verTag = \App\get_ver_tag();
    wp_enqueue_style('sage/main.css', \App\asset_path('styles/main.css'), false, $verTag);
    wp_enqueue_script('sage/main.js', \App\asset_path('scripts/main.js'), ['jquery'], $verTag, true);
}, 100);

/**
 * Use theme wrapper
 */
add_filter('template_include', function ($main) {
    if (!is_string($main) && !(is_object($main) && method_exists($main, '__toString'))) {
        return $main;
    }
    return ((new Template(new Wrapper($main)))->layout());
}, 109);

add_action('wp_head', function() {
    $verTag = \App\get_ver_tag();
?>
    <link rel="preload" href="<?= \App\asset_path('assets/fonts/filename.woff2'); ?>" as="font" type="font/woff2" crossorigin />

    <link rel="preload" href="<?= \App\asset_path("styles/main.css?ver={$verTag}"); ?>" as="style" />

    <link rel="manifest" href="<?= get_template_directory_uri() . "/manifest.webmanifest?ver={$verTag}"; ?>" crossorigin="use-credentials">
<?php
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
    return $classes;
});

/**
 * Add preamble to fallback for excerpt
 */
add_filter('get_the_excerpt', function($str, $post = null) {
    if(has_excerpt($post))
        return $str;

    $preamble = wp_strip_all_tags(get_field('preamble', $post->ID), true);
    if(!empty($preamble))
        return $preamble;

    return $str;
}, 10, 2);

/**
 * Sanetize filenames on upload
 */
add_filter('sanitize_file_name', function($filename) {
    $filename = remove_accents($filename);
    $filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $filename);
    return $filename;
});

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
     * Localize
     */
    load_theme_textdomain('sage', get_template_directory() . '/lang' );

    /**
     * Remove default wordpress extra sizes
     */
    remove_image_size('1536x1536');
    remove_image_size('2048x2048');
});

add_action('switch_theme', function() {
    /**
     * Reset default image sizes
     */
    $imageSizes = \App\get_image_sizes();
    foreach($imageSizes as $size) {
        update_option("{$name}_size_w", $size['width']);
        update_option("{$name}_size_h", 9999);
    }
    update_option('thumbnail_crop', 0);
});

/**
 * Make sure only allowed image sizes are registered
 */
add_filter('intermediate_image_sizes', function($default) {
    $imageSizes = \App\get_image_sizes();
    return array_keys($imageSizes);
});

/**
 * Define the sizes available in admin
 */
add_filter('image_size_names_choose', function($sizes) {
    $imageSizes = \App\get_image_sizes();
    return array_combine(array_keys($imageSizes), array_column($imageSizes, 'title'));
});
