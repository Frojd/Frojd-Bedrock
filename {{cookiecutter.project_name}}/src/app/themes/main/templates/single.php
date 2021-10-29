<?php
$related = get_field('related');
$postType = get_post_type();
App\template_part("partials/article-{$postType}");

App\template_part('partials/list', [
    'posts' => $related
]);
