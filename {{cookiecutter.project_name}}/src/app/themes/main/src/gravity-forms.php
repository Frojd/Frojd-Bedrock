<?php

namespace App\GravityForms;

/**
 * Prevent storing of ip addresses
 */
add_filter('gform_ip_address', '__return_empty_string');

/**
 * Disable Gravity Forms' default CSS; form styling is owned by the theme. The
 * admin setting for this is removed after GF 2.8, so it is set via the filter.
 */
add_filter('gform_disable_css', '__return_true');

/**
 * Extend the blocklist of file extensions the upload endpoints refuse to write.
 */
add_filter('gform_disallowed_file_extensions', function($extensions) {
    return array_merge($extensions, [
        // SVG / XML based XSS vectors
        'svg', 'svgz', 'xhtml', 'shtml', 'xml', 'xsl', 'xslt',
        // PHP variants not blocked by default
        'php7', 'php8', 'phtm', 'pht', 'pgif', 'inc',
        // Perl / CGI / shell
        'pl', 'cgi', 'sh', 'bash', 'ksh', 'zsh',
        // Java
        'jsp', 'jspx', 'jsw', 'jsv', 'jspf', 'war', 'class',
        // .NET / ColdFusion / other server-side and executables
        'cfm', 'cfml', 'cfc', 'dll', 'asa', 'asax', 'ascx', 'ashx', 'asmx',
        'aspq', 'axd', 'swf', 'vbe', 'vbs', 'wsf', 'wsc', 'msi', 'scr', 'reg',
    ]);
});

/**
 * Disable the multi-file async upload endpoint.
 */
add_filter('gform_multifile_upload_field', '__return_empty_string');

/**
 * Hide the "Enable multiple files" checkbox in the form editor.
 */
add_action('admin_head', function() {
    if(class_exists('GFCommon') && \GFCommon::is_form_editor()) {
        echo '<style>.field_setting.multiple_files_setting{display:none !important;}</style>';
    }
});

/**
 * Remove support for some fields in gravity forms
 */
add_filter('gform_add_field_buttons', function($groups) {
    $excludeGroups = ['pricing_fields', 'post_fields'];
    $excludeTypes = [
        'name', 'page', 'address', 'list', 'html', 'captcha', 'hidden',
        'fileupload', 'post_image',
    ];

    foreach($groups as $i => $group) {
        if(in_array($group['name'], $excludeGroups)) {
            unset($groups[$i]);
            continue;
        }
        foreach($group['fields'] as $j => $field) {
            if(in_array($field['data-type'], $excludeTypes)) {
                unset($groups[$i]['fields'][$j]);
            }
        }
    }

    return $groups;
});

/**
 * Add classes to gf fields
 */
add_filter('gform_field_css_class', function ($classes, $field, $form) {
    if (isset($field->size))
        $classes .= ' gfield_size_' . $field->size;

    if (isset($field->type))
        $classes .= ' gfield_' . $field->type;

    return $classes;
}, 10, 3);

/**
 * Add new wrapper for gravity forms blocks
 */
add_filter( 'render_block', function($block_content, $block) {
    if($block['blockName'] == 'gravityforms/form') {
        $block_content = '<div class="gravity-forms__wrapper">' . $block_content . '</div>';
    }
    return $block_content;
}, 10, 2);
