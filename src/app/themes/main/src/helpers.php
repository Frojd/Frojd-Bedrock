<?php

namespace App;

use Roots\Sage\Asset;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template;

function template($layout = 'base') {
    return Template::$instances[$layout];
}

function template_part($template, array $context = [], $layout = 'base') {
    extract($context);
    include template($layout)->partial($template);
}

/**
 * @param $filename
 * @return string
 */
function asset_path($filename) {
    static $manifest;
    isset($manifest) || $manifest = new JsonManifest(get_template_directory() . '/' . Asset::$dist . '/assets.json');

    return (string)new Asset($filename, $manifest);
}

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar() {
    static $display;
    isset($display) || $display = apply_filters('sage/display_sidebar', true);
    return $display;
}

/**
 * Page titles
 * @return string
 */
function title() {
    if (is_home()) {
        if ($home = get_option('page_for_posts', true)) {
            return get_the_title($home);
        }
        return __('Latest Posts', 'sage');
    }
    if (is_archive()) {
        return get_the_archive_title();
    }
    if (is_search()) {
        return sprintf(__('Search Results for %s', 'sage'), get_search_query());
    }
    if (is_404()) {
        return get_field('404_title', 'option');
    }
    return get_the_title();
}

/**
 * Get a field as object when it is a repeater field with a single row
 */
function get_field_group($field = '', $postId = null) {
    $postId = is_null($postId) ? get_the_ID() : $postId;
    $group = get_field($field, $postId);

    if($group)
        return current($group);
    return false;
}

/**
 * SVG icons
 */
function the_svg_icon($icon, $relPath = '/dist/images/') {
    echo get_the_svg_icon($icon, $relPath);
}

function get_the_svg_icon($icon, $relPath = '/dist/images/') {
    $icon = preg_replace('/\.svg$/', '', $icon);

    $path = get_template_directory() . $relPath . $icon . '.svg';
    if(file_exists($path)) {
        return preg_replace( "/\r|\n/", "", trim(file_get_contents($path)));
    }
    return '';
}

/**
 * Post thumbnail as background image
 */
function the_post_thumbnail_background($size = '', $postId = null, $thumbnailId = null) {
    $thumbnail = get_post_thumbnail_data($size, $postId, $thumbnailId);

    if(empty($thumbnail->src)) {
        return '';
    }
    
    $html = ' style="background-image:url(\'' . $thumbnail->src . '\');"';
    if(!empty($thumbnail->alt)) {
        $html .= ' title="' . $$thumbnail->alt . '"';
    }
    echo $html;
}

function get_post_thumbnail_data($size = '', $postId = null, $thumbnailId = null) {
    $size = empty($size) ? 'thumbnail' : $size;
    $postId = is_null($postId) ? get_the_ID() : $postId;
    $thumbnailId = is_null($thumbnailId) ? get_post_thumbnail_id($postId) : $thumbnailId;

    $attachment = get_post($thumbnailId);
    $src = wp_get_attachment_image_src($thumbnailId, $size);
    return (object) array(
        'title' => $attachment->post_title,
        'caption' => $attachment->post_excerpt,
        'description' => $attachment->post_content,
        'src' => !empty($src) ? $src[0] : '',
        'alt' => trim(strip_tags(get_post_meta($thumbnailId, '_wp_attachment_image_alt', true)))
    );
}
