<?php

namespace App\Filters;

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
 * Sanetize filenames on upload
 */
add_filter('sanitize_file_name', function($filename) {
    return preg_replace('/[^a-zA-Z0-9-_\.]/', '', $filename);
}, 20);

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

add_filter('get_the_excerpt', function($str, $post = null) {
    if(has_excerpt($post))
        return $str;

    $preamble = wp_strip_all_tags(get_field('preamble', $post->ID), true);
    if(!empty($preamble))
        return $preamble;

    return $str;
}, 10, 2);
