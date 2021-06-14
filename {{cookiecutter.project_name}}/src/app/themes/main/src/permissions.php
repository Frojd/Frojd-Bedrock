<?php

namespace App\Permissions;

/*
 * Restrict any file types to be uploaded by all users
 */
add_filter('upload_mimes', function($mimes, $user = null) {
    $allowAll = $user ? user_can($user, 'allow_all_mimes') : current_user_can('allow_all_mimes');
    if($allowAll)
        return $mimes;

    $allow = [
        'jpg|jpeg|jpe', 'gif', 'png', 'svg', 'svgz', 'pdf'
    ];
    $restrictedMimes = [];
    foreach($allow as $type) {
        if(isset($mimes[$type]))
            $restrictedMimes[$type] = $mimes[$type];
    }
    return $restrictedMimes;
}, 10, 2);

/*
 * Restrict file upload size
 */
add_filter('upload_size_limit', function($size) {
    if(current_user_can('allow_full_upload_size'))
        return $size;
    return 5242880; // 5MB
});
