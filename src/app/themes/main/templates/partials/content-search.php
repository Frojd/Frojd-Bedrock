<article <?php post_class('entry'); ?>>
    <header class="entry__header">
        <h2 class="entry__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php if (get_post_type() === 'post') {
            get_template_part('partials/entry-meta');
        } ?>
    </header>
    <div class="entry__summary">
        <?php the_excerpt(); ?>
    </div>
</article>
