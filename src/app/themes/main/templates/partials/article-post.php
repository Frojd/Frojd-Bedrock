<article <?php post_class('article'); ?>>
    <div class="article__container">
        <header class="article__header">
            <div class="article__meta">
                <?php get_template_part('partials/entry-meta'); ?>
            </div>
        </header>
        <div class="article__content wysiwyg">
            <?php the_content(); ?>
        </div>
    </div>
</article>
