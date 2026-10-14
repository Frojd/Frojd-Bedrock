<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#ffffff"/>
    <meta name="application-name" content="<?= bloginfo('name'); ?>"/>
    <meta name="msapplication-TileColor" content="#ffffff" />

    <?php get_template_part('partials/favicons'); ?>

    <?php wp_head(); ?>

    <?php if (\App\use_vite_dev_server()): ?>
        <script type="module" src="<?= \App\vite_dev_server_url(); ?>/@vite/client"></script>
    <?php endif; ?>
</head>
