<?php

namespace App\CookieScript;

/**
 * Whether the visitor has granted a given Cookie Script consent category.
 * Returns false when Cookie Script isn't used or the cookie is missing.
 */
function is_category_granted($category) {
    $cookie = $_COOKIE['CookieScriptConsent'] ?? '';
    if (empty($cookie)) {
        return false;
    }

    $cookie = json_decode(stripslashes($cookie), true);
    $categories = $cookie['categories'] ?? [];
    if (empty($categories)) {
        return false;
    }

    if (!is_array($categories)) {
        $categories = json_decode($categories, true) ?: [];
    }

    return in_array($category, $categories, true);
}

/**
 * Load the Cookie Script consent banner (only when a script id is configured).
 * Everything below this — the embed handling — runs regardless, so that video
 * embeds are always privacy-gated even on projects without Cookie Script.
 */
if (defined('COOKIE_SCRIPT') && !empty(COOKIE_SCRIPT)) {
    add_action('wp_enqueue_scripts', function () {
        $themeVersion = wp_get_theme()->get('Version');
        $verTag = WP_ENV == 'production' ? md5($themeVersion) : $themeVersion;
        wp_enqueue_script(
            'cookie-script',
            '//cdn.cookie-script.com/s/' . COOKIE_SCRIPT . '.js',
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
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'analytics_storage': 'denied',
            'wait_for_update': 500,
        });
        gtag('set', 'ads_data_redaction', true);
        <?php
        $script = ob_get_clean();
        wp_add_inline_script('cookie-script', $script, 'before');
    }, 0);
}

/**
 * Parse core/embed blocks so video embeds are privacy-handled.
 */
add_filter('render_block', function ($blockContent, $block) {
    $blockName = $block['blockName'] ?? '';
    if ('core/embed' != $blockName) {
        return $blockContent;
    }

    $url = $block['attrs']['url'] ?? '';
    $type = $block['attrs']['providerNameSlug'] ?? '';

    return apply_filters('render_embed', $blockContent, $url, $type, 'gutenberg');
}, 0, 2);

/**
 * Privacy handling for a single embed. Can be reused for custom blocks.
 *
 * - Vimeo is the only provider that supports Do-Not-Track, so it gets `dnt=1`
 *   and renders directly (no cookies until played, tracking disabled).
 * - Every other provider (YouTube, SoundCloud, Spotify, ...) sets cookies with
 *   no DNT option, so it is replaced with a consent message and only loads when
 *   the visitor accepts — regardless of whether Cookie Script is used. If
 *   Cookie Script is active and targeting consent is already granted, it renders.
 */
add_filter('render_embed', function ($content = '', $url = '', $type = '', $modifier = '', $allowMessage = true) {
    if (empty($content)) {
        return $content;
    }

    if (empty($url)) {
        preg_match('/src="([^"]+)"/', $content, $match);
        if (empty($match)) {
            return $content;
        }
        $url = $match[1];
    }

    // Vimeo: apply DNT and render.
    if ($type === 'vimeo' || strpos($url, 'vimeo') !== false) {
        return _parse_vimeo_dnt_embed($content);
    }

    // Everything else already has consent, or Cookie Script says targeting is granted.
    if (is_category_granted('targeting')) {
        return $content;
    }

    // No DNT available for this provider — gate it behind a consent message.
    if (!$allowMessage) {
        return '';
    }

    return apply_filters('render_embed_message', $content, $url, $type, $modifier);
}, 10, 5);

/**
 * Render the consent message that replaces a gated embed.
 */
add_filter('render_embed_message', function ($content = '', $url = '', $type = '', $modifier = '') {
    ob_start();
    \App\template_part('partials/embed-message', [
        'type' => $type,
        'iframe' => $content,
        'url' => $url,
        'modifier' => $modifier,
    ]);
    return ob_get_clean();
}, 10, 4);

/**
 * Add Vimeo's Do-Not-Track flag to an embed's iframe src.
 *
 * @param string $embed
 * @return string
 */
function _parse_vimeo_dnt_embed($embed) {
    preg_match('/src="([^"]+)"/', $embed, $match);
    if (empty($match)) {
        return $embed;
    }

    $url = $match[1];
    if (strpos($url, 'dnt=1') === false) {
        $url .= (strpos($url, '?') !== false ? '&' : '?') . 'dnt=1';
    }
    return preg_replace('/src="([^"]+)"/', 'src="' . $url . '"', $embed);
}
