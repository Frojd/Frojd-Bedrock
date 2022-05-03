<?php
global $wp_query;
$posts = $wp_query->posts;
?>
<div class="search">
    <div class="search__wrap">
        <div class="search__header">
            <h1 class="search__title"><?= App\title(); ?></h1>

            <div class="search__form">
                <?php get_search_form(); ?>
            </div>
        </div>
        <div class="search__result">
            <?php if(empty($posts)) : ?>
                <p class="search__no-result">
                    <?= __('Sorry, no results were found.', 'sage'); ?>
                </p>
            <?php else : ?>
                <?php
                    App\template_part('partials/articlelist', [
                        'items' => $posts,
                    ]);
                    App\template_part('partials/pagination');
                ?>
            <?php endif; ?>
        </div>
    </div>
</div>
