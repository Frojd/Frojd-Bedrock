<?php
$postType = get_post_type();
App\template_part("partials/article-{$postType}");
