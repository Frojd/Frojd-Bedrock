<?php

namespace App\Permissions;

/*
 * Restrict any file types to be uploaded by all users
 */
add_filter('upload_mimes', function($mimes, $user = null) {
    // SVG is considered harmful so only add if needed and to roles who need it
    // $mimes['svg'] = 'image/svg+xml';
    // $mimes['svgz'] = 'image/svg+xml';

    // All roles with customized allowed mimes must have capability unfiltered_html
    // Add capability allow_all_mimes to roles who need to upload all formats
    // Possibility to add more capabilities for different roles, eg. allow_document_mimes
    $allowAll = $user ? user_can($user, 'allow_all_mimes') : current_user_can('allow_all_mimes');
    if($allowAll) {
        return $mimes;
    }

    $allow = [
        'jpg|jpeg|jpe', 'gif', 'png', 'wepb', 'pdf',
    ];
    $restrictedMimes = [];
    foreach($allow as $type) {
        if(isset($mimes[$type])) {
            $restrictedMimes[$type] = $mimes[$type];
        }
    }
    return $restrictedMimes;
}, 10, 2);

/*
 * Restrict file upload size
 */
add_filter('upload_size_limit', function($size) {
    if(current_user_can('allow_full_upload_size')) {
        return $size;
    }
    return 5242880; // 5MB
});
