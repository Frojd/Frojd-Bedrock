<?php

namespace App\ClassicEditor;

/**
 * Limit available formats in WYSIWYG
 */
add_filter('tiny_mce_before_init', function ($init) {
    $formats = array(
        array(
            'title'     => __('Facts', 'sage'),
            'block'     => 'div',
            'classes'   => 'facts',
            'wrapper'   => true
        )
    );
    $init['style_formats'] = json_encode($formats);
    $init['block_formats'] = 'Paragraph=p; Heading 2=h2; Heading 3=h3;';
    $init['toolbar1'] = 'formatselect,styleselect,bold,italic,underline,bullist,numlist,blockquote,alignright,aligncenter,alignleft,alignjustify,link,unlink,wp_adv';
    $init['toolbar2'] = 'strikethrough,hr,charmap,pastetext,removeformat,undo,redo,help';
    return $init;
});

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Use main stylesheet for visual editor
     * @see assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/editor.css'));
});