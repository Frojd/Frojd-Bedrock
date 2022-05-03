<?php
$latest = get_posts([
    'numberposts' => 4,
    'post_type' => 'post'
]);

App\template_part('partials/hero');

App\template_part('partials/modules');
