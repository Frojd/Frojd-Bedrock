<?php

namespace App\NginxCache;

/**
 * Purge nginx cache on option update
 */
add_action('updated_option', function() {
    if(class_exists('NginxCache')) {
        $nginx = new \NginxCache;
        $nginx->purge_zone_once();
    }
});
