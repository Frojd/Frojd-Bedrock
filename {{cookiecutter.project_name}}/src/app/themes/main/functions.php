<?php
/**
 * Fix compatibility issues with alternative template path in
 * WP >6.3
 */
add_filter('stylesheet_directory', function ($stylesheet_dir) {
    $subdirectory = 'templates';

    if (basename($stylesheet_dir) === $subdirectory) {
        return $stylesheet_dir;
    }

    return $stylesheet_dir . '/' . $subdirectory;
}, 10, 3);

/**
 * Here's what's happening with these hooks:
 * 1. WordPress detects theme in themes/sage
 * 2. When we activate, we tell WordPress that the theme is actually in themes/sage/templates
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/sage
 *
 * We do this so that the Template Hierarchy will look in themes/sage/templates for core WordPress themes
 * But functions.php, style.css, and index.php are all still located in themes/sage
 *
 * themes/sage/index.php also contains some self-correcting code, just in case the template option gets reset
 */
add_filter('stylesheet', function ($stylesheet) {
    return dirname($stylesheet);
});
add_action('after_switch_theme', function () {
    $stylesheet = get_option('stylesheet');
    if (basename($stylesheet) !== 'templates') {
        update_option('stylesheet', $stylesheet . '/templates');
    }
});
add_action('customize_render_section', function ($section) {
    if ($section->type === 'themes') {
        $section->title = wp_get_theme(basename(__DIR__))->display('Name');
    }
}, 10, 2);

/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 */
$sage_includes = [
    'src/acf.php',
    'src/admin.php',
    'src/classic-editor.php',
    'src/cookie-script.php',
    'src/elasticpress.php',
    'src/gravity-forms.php',
    'src/gutenberg.php',
    'src/helpers.php',
    'src/nginx-cache.php',
    'src/permissions.php',
    'src/security.php',
    'src/sentry.php',
    'src/setup.php',
    'src/yoast-seo.php',
];
array_walk($sage_includes, function ($file) {
    if (!locate_template($file, true, true)) {
        trigger_error(sprintf('Error locating %s for inclusion', $file), E_USER_ERROR);
    }
});

if (defined('WP_CLI') && WP_CLI) {
    foreach (glob(__DIR__ . '/src/commands/*') as $file) {
        if (!preg_match('|fixture|', $file)){
            require_once $file;
        }
    }
}

foreach (glob(__DIR__.'/src/posttypes/*') as $file) {
    require_once $file;
}

foreach (glob(__DIR__.'/src/taxonomies/*') as $file) {
    require_once $file;
}

foreach (glob(__DIR__.'/src/walkers/*') as $file) {
    require_once $file;
}


