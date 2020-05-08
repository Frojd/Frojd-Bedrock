<?php

namespace App\Filters;

/**
 * Filter and remove WP image wrappers, replace with custom markup
 */
add_filter('the_content', function($content, $preg = true) {
    if(!$preg)
        return $content;

    $new = preg_replace('/<p>(.*<iframe.*>.*<\/iframe>.*)<\/p>/i', '<div class="iframe">$1</div>', $content);

    if(empty($new)) // Make sure content is returned even if it preg_replace gives an error
       return $content;
    
   return $new;
});
