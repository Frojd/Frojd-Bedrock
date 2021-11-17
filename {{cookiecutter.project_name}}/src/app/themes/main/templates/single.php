<?php
$postType = get_post_type();

$related = get_field('articlelist');
$related = $related['articlelist'] ?? $related;
$relatedTitle = $related['title'] ?? '';

$relatedTitle = $title ?: __('Related', 'sage');

App\template_part("partials/article-{$postType}");

if(!empty($related))
    App\template_part('partials/articlelist', [
        'title' => __('Related', 'sage'),
        'items' => $related,
    ]);
