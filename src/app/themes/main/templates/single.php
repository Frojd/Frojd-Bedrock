<div class="content">
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('partials/content-header'); ?>
        <?php get_template_part('partials/article', get_post_type()); ?>
    <?php endwhile; ?>
</div>