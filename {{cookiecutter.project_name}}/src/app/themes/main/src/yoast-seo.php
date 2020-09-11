<?php

namespace App\YoastSEO;

// Move yoast seo meta box to bottom of edit post page
add_filter('wpseo_metabox_prio', function() {
    return 'low';
});

/**
 * Add custom variable to be able to show preamble as fallback to excerpt
 */
add_action('wpseo_register_extra_replacements', function() {
    wpseo_register_var_replacement(
        '%%preamble%%',
        __NAMESPACE__ . '\\_wpseoRegisterPreamble',
        'advanced',
        'Shows preamble if it exists'
    );
});

function _wpseoRegisterPreamble() {
    global $post;

    $more = apply_filters('excerpt_more', '...');
    $preamble = wp_trim_words(get_field('preamble', $post->ID), 20, $more);

    if(!empty($preamble))
        return $preamble;

    return wp_trim_words($post->post_content, 20, $more);
}
