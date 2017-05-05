<?php
    if(!isset($posts) || empty($posts))
        return;

    global $post;
?>

<div class="list">
    <div class="list__container">
        <div class="list__list">
            <?php foreach($posts as $post) : ?>
                <?php setup_postdata($post); ?>

                <div class="list__item">
                    <?php get_template_part('partials/item', get_post_type()); ?>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php wp_reset_postdata(); ?>