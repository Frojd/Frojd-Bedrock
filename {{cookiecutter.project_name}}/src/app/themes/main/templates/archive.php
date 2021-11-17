<?php
global $wp_query;
$posts = $wp_query->posts;
?>
<div class="archive">
    <div class="archive__wrap">
        <div class="archive__header">
            <h1 class="archive__title"><?= App\title(); ?></h1>
        </div>
        <div class="archive__result">
            <?php
                App\template_part('partials/articlelist', [
                    'items' => $posts,
                ]);
                App\template_part('partials/pagination');
            ?>
        </div>
    </div>
</div>
