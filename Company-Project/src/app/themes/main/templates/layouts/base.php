<!doctype html>
<html <?php language_attributes(); ?>>

    <?php App\template_part('partials/head'); ?>

    <body <?php body_class(); ?>>
        <!--[if IE]>
        <?php App\template_part('partials/popup-browser'); ?>
        <![endif]-->

        <?php
            do_action('get_header');

            App\template_part('partials/popup-cookie');
            App\template_part('partials/header');
        ?>

        <main>
            <div class="main">
                <?php include App\template()->main(); ?>
            </div>
        </main>

        <?php
            do_action('get_footer');
            App\template_part('partials/footer');
            wp_footer();
        ?>
    </body>
</html>
