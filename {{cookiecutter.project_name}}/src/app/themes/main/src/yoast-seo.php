<?php

namespace App\YoastSEO;

// Move yoast seo meta box to bottom of edit post page
add_filter('wpseo_metabox_prio', function() {
    return 'low';
});

/**
 * Add custom variable to be able to show hero text as fallback to excerpt
 */
add_action('wpseo_register_extra_replacements', function() {
    wpseo_register_var_replacement(
        '%%custom_excerpt%%',
        __NAMESPACE__ . '\\_wpseoRegisterCustomExcerpt',
        'advanced',
        'Shows hero text if it exists, and otherwise excerpt'
    );
});

function _wpseoRegisterCustomExcerpt() {
    global $post;

    $more = apply_filters('excerpt_more', '...');
    $default = wp_trim_words($post->post_content, 20, $more);

    $heroText = get_field('hero_text', $post->ID);
    if(empty($heroText))
        return $default;

    return wp_trim_words($heroText, 20, $more);
}
