<?php

namespace App;

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

        'acf/image-50-50',
        'acf/image',
    ];
    return $allowedBlocks;
}, 2, 10);


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
