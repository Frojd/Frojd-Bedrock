<?php

namespace App\CookieScript;

// Early return if cookie script isn't used
if(!defined('COOKIE_SCRIPT') || empty(COOKIE_SCRIPT))
    return;

// Make sure cookie script is loaded first of all, gtm will have priority 10
add_action('wp_enqueue_scripts', function() {
    $themeVersion = wp_get_theme()->get('Version');
    $verTag = WP_ENV == 'production' ? md5($themeVersion) : $themeVersion;
    wp_enqueue_script(
        'cookie-script',
        '//cdn.cookie-script.com/s/'.COOKIE_SCRIPT.'.js',
        [],
        $verTag
    );
    ob_start();
    ?>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('consent', 'default', {
        ad_storage: 'denied',
        analytics_storage: 'denied',
        wait_for_update: 500
    });
    gtag('set', 'ads_data_redaction', true);
    <?php
    $script = ob_get_clean();
    wp_add_inline_script('cookie-script', $script, 'after');
}, 0);

// Parse embeds if user hasn't accepted cookies and instead show message
add_filter('render_block', function ($blockContent, $block) {
    $blockName = $block['blockName'] ?? '';
    if ('core/embed' != $blockName)
        return $blockContent;

    $url = $block['attrs']['url'] ?? '';
    $type = $block['attrs']['providerNameSlug'] ?? '';

    return apply_filters('render_embed', $blockContent, $url, $type, 'gutenberg');
}, 0, 2);

add_filter('render_embed_message', function($content = '', $url = '', $type = '', $modifier = '') {
    // Possibility to hide embed message completely depending on location

    ob_start();
    \App\template_part('partials/embed-message', [
        'type' => $type,
        'iframe' => $content,
        'url' => $url,
        'modifier' => $modifier,
    ]);
    $output = ob_get_clean();
    return $output;
}, 10, 4);

// New filter for rendering embed, can be used for custom blocks also
add_filter('render_embed', function($content = '', $url = '', $type = '', $modifier = '') {
    if(empty($content))
        return $content;

    if(empty($url)) {
        preg_match('/src="([^"]+)"/', $content, $match);
        if(empty($match))
            return $content;

        $url = $match[1];
    }

    $type = strpos($url, 'youtube') !== false ? 'youtube' : $type;
    $type = strpos($url, 'vimeo') !== false ? 'vimeo' : $type;
    if($type != 'youtube' && $type != 'vimeo')
        return $content;

    $cookie = $_COOKIE['CookieScriptConsent'] ?? '';
    if(!empty($cookie)) {
        $cookie = json_decode(stripslashes($cookie), true);
        $categories = $cookie['categories'] ?? [];
        if($categories && strpos($categories, 'targeting') !== false)
            return $content;
    }

    if($type == 'vimeo')
        return _parse_vimeo_dnt_embed($content);

    return apply_filters('render_embed_message', $content, $url, $type, $modifier);
}, 10, 4);

/**
 * For parsing vimeo embed and changing options
 *
 * @param $embed string
 * @return string
 */
function _parse_vimeo_dnt_embed($embed) {
    preg_match('/src="([^"]+)"/', $embed, $match);
    if(empty($match))
        return $embed;

    $url = $match[1];
    if(strpos($url, 'dnt=1') === false)
        $url .= '&dnt=1';
    return preg_replace('/src="([^"]+)"/', "src=\"$url\"", $embed);
}
