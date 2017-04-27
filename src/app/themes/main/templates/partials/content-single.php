<article <?php post_class('entry'); ?>>
    <header class="entry__header">
        <?php get_template_part('partials/entry-meta'); ?>
    </header>
    <div class="entry__content wysiwyg">
        <?php the_content(); ?>
    </div>
</article>
