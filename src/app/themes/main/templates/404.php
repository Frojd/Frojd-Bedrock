<?php get_template_part('partials/page-header'); ?>

<div class="alert alert--warning">
    <?= get_field('404_description', 'option') ?>
</div>

<?php get_search_form(); ?>
