<?php

namespace App\ClassicEditor;

/**
 * Limit available formats in WYSIWYG
 */
add_filter('tiny_mce_before_init', function ($init) {
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
    add_editor_style(\App\asset_path('styles/editor.css'));
});

/**
 * Filter and remove WP image wrappers, replace with custom markup
 */
add_filter('the_content', function($content, $preg = true) {
    if(!$preg)
        return $content;

    $new = preg_replace('/<p>(.*<iframe.*>.*<\/iframe>.*)<\/p>/i', '<div class="iframe">$1</div>', $content);

    if(empty($new)) // Make sure content is returned even if it preg_replace gives an error
       return $content;
    
   return $new;
});
