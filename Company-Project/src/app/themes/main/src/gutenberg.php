<?php

namespace App\Gutenberg;

/*
 * Possibility to disable gutenberg completely in admin view so the editor is removed, e.g. front page
 */
function _disable_gutenberg($postId) {
    return false;
}

add_filter('gutenberg_can_edit_post_type', function($canEdit) {
    if(is_admin() && isset($_GET['post'])) {
        $disableGutenberg = _disable_gutenberg($_GET['post']);
        if($disableGutenberg)
            return false;
    }
    return $canEdit;
});

add_filter('use_block_editor_for_post_type', function($canEdit) {
    if(is_admin() && isset($_GET['post'])) {
        $disableGutenberg = _disable_gutenberg($_GET['post']);
        if($disableGutenberg)
            return false;
    }
    return $canEdit;
});

add_action('admin_head', function() {
    if(isset($_GET['post'])) {
        $disableGutenberg = _disable_gutenberg($_GET['post']);
        if($disableGutenberg)
            remove_post_type_support('page', 'editor');
    }
});

add_filter('allowed_block_types_all', function ($allowedBlocks, $editor) {
    $post = $editor->post;

    /**
     *  See all registered blocks:
     *
     *  $registeredBlocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
     *  var_dump($registeredBlocks);
     *
     */

    $disableGutenberg = _disable_gutenberg($post->ID);
    if($disableGutenberg)
        return [];

    $allowedBlocks = [
        'core/block',
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/list-item',
        'core/quote',
        'core/file',
        'core/media-text',
        'core/group',
        'core/embed',
        'core/buttons',
        'core/button',

        // Theme specific
        'sage/preamble',

        // ACF specific
        // 'acf/related',
    ];
    return $allowedBlocks;
}, 2, 10);

// Add blocks to be allowed for excerpt
add_filter('excerpt_allowed_blocks', function($allowedBlocks) {
    return array_merge($allowedBlocks, ['sage/preamble']);
});

add_action('init', function() {
    wp_set_script_translations('sage-gutenberg', 'sage', get_template_directory() . '/lang');

    // Register a "Link" style for the button block (fill and outline ship with
    // core). The theme maps fill -> primary, outline -> secondary, link -> link.
    if (function_exists('register_block_style')) {
        register_block_style('core/button', [
            'name'  => 'link',
            'label' => __('Link', 'sage'),
        ]);
    }

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
    // The editor lockdown (disabling custom colors/font-sizes/gradients and
    // emptying the palettes) and the layout sizes live in theme.json. Keep here
    // only what theme.json can't express:

    // No theme.json equivalent
    add_theme_support('responsive-embeds');
    remove_theme_support('core-block-patterns');

    // Load the theme editor styles (see add_editor_style / editor.scss)
    add_theme_support('editor-styles');
});

add_action('enqueue_block_editor_assets', function() {
    // Editor styles load without the Vite client, so always use the built CSS.
    wp_enqueue_style('gutenberg-block-style', \App\built_asset_path('styles/editor.scss'));

    // Used for editing default blocks
    wp_enqueue_script(
        'sage-gutenberg',
        get_template_directory_uri() . '/src/scripts/gutenberg.js',
        ['wp-blocks', 'wp-element', 'wp-dom-ready', 'wp-edit-post', 'wp-i18n', 'wp-rich-text', 'wp-block-editor']
    );

    // For registering blocks from js
    register_block_type('sage/preamble', ['editor_script' => 'sage-gutenberg']);
});

/*
 * Unwrap the editor-only soft hyphen marker on the frontend.
 *
 * The sage/soft-hyphen format (src/scripts/gutenberg.js) wraps each U+00AD in a
 * <span class="soft-hyphen"> so editors can see it. On the frontend we strip the
 * span and keep the bare soft hyphen character.
 */
add_filter('render_block', function($blockContent, $block) {
    // Cheap bail-out: only the soft-hyphen format adds this class.
    if (strpos($blockContent, 'soft-hyphen') === false) {
        return $blockContent;
    }

    // Match any <span> carrying the soft-hyphen class (regardless of attribute
    // order or extra attributes like title) and keep only the inner character.
    return preg_replace(
        '/<span\b[^>]*\bclass="[^"]*\bsoft-hyphen\b[^"]*"[^>]*>(\x{00AD})<\/span>/u',
        '$1',
        $blockContent
    );
}, 10, 2);

// add_action('acf/init', function () {
//     /**
//      * Icon list: @link https://developer.wordpress.org/resource/dashicons/
//      * acf_register_block_type docs: @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
//      */

//     // Register acf blocks that need more complicated layout
//     acf_register_block_type([
//         'name' => 'related',
//         'title' => __('Related posts', 'sage'),
//         'description' => __('List of related posts', 'sage'),
//         'render_template' => get_template_directory() . '/templates/partials/related.php',
//         'category' => 'common',
//     ]);
// });
