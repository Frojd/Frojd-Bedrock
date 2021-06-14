<?php

namespace App\Gutenberg;

add_filter('allowed_block_types', function ($allowedBlocks, $post) {
    /**
     *  See all registered blocks:
     *
     *  $registeredBlocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
     *  var_dump($registeredBlocks);
     *
     */

    $allowedBlocks = [
        'core/block',
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/quote',
        'core/file',
        'core/media-text',
        'core/group',
        'core/embed',

        // Theme specific
        'sage/preamble',

        // ACF specific
        'acf/image-50-50',
        'acf/image',
    ];
    return $allowedBlocks;
}, 2, 10);

add_action('init', function() {
    wp_set_script_translations('sage-gutenberg', 'sage', get_template_directory() . '/lang');

    // This adds preamble as default block to post types
    $postTypes = ['post', 'page'];
    foreach($postTypes as $postType) {
        $obj = get_post_type_object($postType);
        $obj->template = [
            ['sage/preamble'],
        ];
    }
});

/*
 * Remove some default settings for gutenberg
 */
add_action('after_setup_theme', function() {
    // Disable and remove these settings
    add_theme_support('disable-custom-font-sizes');
    add_theme_support('disable-custom-colors');
    add_theme_support('disable-custom-gradients');
    add_theme_support('editor-font-sizes', []);
    add_theme_support('editor-color-palette', []);
    add_theme_support('editor-gradient-presets', []);
    add_theme_support('responsive-embeds');
    remove_theme_support('core-block-patterns');

    // Activate settings
    add_theme_support('editor-styles');
});

add_action('enqueue_block_editor_assets', function() {
    wp_enqueue_style('gutenberg-block-style', \App\asset_path('styles/editor.css'));

    // Used for editing default blocks
    wp_enqueue_script(
        'sage-gutenberg',
        get_template_directory_uri() . '/src/scripts/gutenberg.js',
        ['wp-blocks', 'wp-element', 'wp-dom-ready', 'wp-edit-post', 'wp-i18n']
    );

    // For registering blocks from js
    register_block_type('sage/preamble', ['editor_script' => 'sage-gutenberg']);
});

add_action('acf/init', function () {
    /**
     * Icon list: @link https://developer.wordpress.org/resource/dashicons/
     * acf_register_block_type docs: @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
     */

    // Image //
    acf_register_block_type([
        'name' => 'image',
        'title' => __('Image', 'sage'),
        'description' => __('Single image', 'sage'),
        'render_template' => get_template_directory() . '/templates/partials/block-image.php',
        'category' => 'common',
        'icon' => 'format-image',
        'keywords' => ['image'],
        'supports' => [
            'align' => ['full',]
        ]
    ]);
});
