<?php
$content = get_field('404', 'option');
$title = $content['title'] ?? '';
$text = $content['text'] ?? '';
?>
<article <?php post_class('article article--404'); ?>>
    <div class="article__wrap">
        <div class="article__content">
            <h1 class="article__title"><?= $title; ?></h1>

            <div class="article__richtext"><?= $text; ?></div>
        </div>
    </div>
</article>