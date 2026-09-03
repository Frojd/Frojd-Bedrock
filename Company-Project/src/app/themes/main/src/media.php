<?php

namespace App\Media;

/**
 * Convert generated image sizes to WebP regardless of the uploaded format, so
 * JPEG/PNG uploads are served as WebP. Uses the server's GD/Imagick WebP
 * support (no plugin). The original upload keeps its format; only the generated
 * sizes (which the theme serves via srcset) become WebP.
 *
 * Run `wp media regenerate --skip-delete` to convert existing uploads. Use
 * --skip-delete so the old jpg/png sub-sizes are kept for any content that
 * references their URLs directly (those can't be remapped to the new webp).
 */
add_filter('wp_editor_output_format', function ($formats) {
    $formats['image/jpeg'] = 'image/webp';
    $formats['image/png'] = 'image/webp';
    return $formats;
});

/**
 * Compress generated WebP image sizes. WordPress otherwise re-encodes WebP at
 * its default quality; 80 is visually lossless for photos while noticeably
 * reducing file size.
 */
add_filter('wp_editor_set_quality', function ($quality, $mime) {
    if ($mime === 'image/webp') {
        return 80;
    }
    return $quality;
}, 10, 2);

/**
 * Sanitize filenames on upload.
 */
add_filter('sanitize_file_name', function ($filename) {
    $filename = remove_accents($filename);
    $filename = preg_replace('/[^A-Za-z0-9-_\. ]/', '', $filename);
    return $filename;
});
