<article <?php post_class('article'); ?>>
    <div class="article__wrap">
        <header class="article__header">
            <h1 class="article__title"><?= App\title(); ?></h1>
        </header>
        <div class="article__content">
            <div class="article__richtext">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</article>
