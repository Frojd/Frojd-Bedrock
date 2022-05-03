<?php
$postType = get_post_type();

$related = get_field('articlelist');
$related = $related['articlelist'] ?? $related;
$relatedTitle = $related['title'] ?? '';

$relatedTitle = $title ?: __('Related', 'sage');
$related['title'] = $relatedTitle;

App\template_part("partials/article-{$postType}");

if(!empty($related))
    App\template_part('partials/articlelist', $related);
