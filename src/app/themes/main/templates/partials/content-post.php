<article <?php post_class('entry'); ?>>
    <header class="entry__header">
        <div class="entry__meta">
            <?php get_template_part('partials/entry-meta'); ?>
        </div>
    </header>
    <div class="entry__content wysiwyg">
        <?php the_content(); ?>
    </div>
</article>
