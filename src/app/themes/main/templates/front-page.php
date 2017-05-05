<?php
    $latest = get_posts(array(
        'numberposts' => 4,
        'post_type' => 'post'
    ));
?>
<div class="content">
    <?php
        get_template_part('partials/hero');

        get_template_part('partials/blurbs');

        App\template_part('partials/list', array(
            'posts' => $latest
        ));
    ?>
</div>