<!doctype html>
<html <?php language_attributes(); ?>>

    <?php get_template_part('partials/head'); ?>

    <body <?php body_class(); ?>>
        <!--[if IE]>
        <?php get_template_part('partials/popup-browser'); ?>
        <![endif]-->

        <?php
            do_action('get_header');
            get_template_part('partials/header');
        ?>

        <div class="main" role="document">
            <?php include App\template()->main(); ?>
        </div>

        <?php
            get_template_part('partials/popup-cookie');

            do_action('get_footer');
            get_template_part('partials/footer');
            wp_footer();
        ?>
    </body>
</html>
