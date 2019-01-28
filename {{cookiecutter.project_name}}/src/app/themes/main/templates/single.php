<?php
    $related = get_field('related');
?>

<div class="content">
    <?php while (have_posts()) : the_post(); ?>
        <?php
            get_template_part('partials/content-header');
            
            get_template_part('partials/article', get_post_type());

            App\template_part('partials/list', array(
                'posts' => $related
            ));
        ?>
    <?php endwhile; ?>
</div>