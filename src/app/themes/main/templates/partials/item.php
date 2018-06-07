<article <?php post_class('item'); ?>>
    <header class="item__header">
        <h3 class="item__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    </header>
    <div class="item__content">
        <?php the_excerpt(); ?>
    </div>
</article>
