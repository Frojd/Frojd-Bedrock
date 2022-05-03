<?php
$authorUrl = get_author_posts_url(get_the_author_meta('ID'));
$authorName = get_the_author();
?>
<article <?php post_class('article article--post'); ?>>
    <div class="article__wrap">
        <header class="article__header">
            <h1 class="article__title"><?= App\title(); ?></h1>
            <div class="article__meta">
                <time
                    class="article__date"
                    datetime="<?= get_post_time('c', true); ?>"
                ><?= get_the_date(); ?></time>

                <p class="article__author">
                    <?= __('By', 'sage'); ?> <a
                        href="<?= $authorUrl; ?>"
                        rel="author"
                    ><?= $authorName; ?></a>
                </p>

            </div>
        </header>
        <div class="article__content">
            <div class="article__richtext">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</article>
