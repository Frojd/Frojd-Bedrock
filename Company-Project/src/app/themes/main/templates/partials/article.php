<article <?php post_class('article article--has-sidebar'); ?>>
    <div class="article__wrap">
        <aside class="article__sidebar">
            <?php App\template_part('partials/sidemenu'); ?>
        </aside>
        <div class="article__content">
            <header class="article__header">
                <h1 class="article__title"><?= App\title(); ?></h1>
            </header>
            <div class="article__richtext">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</article>
