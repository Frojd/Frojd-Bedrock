<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#ffffff"/>
    <meta name="application-name" content="<?= bloginfo('name'); ?>"/>
    <meta name="msapplication-TileColor" content="#ffffff" />

    <?php get_template_part('partials/favicons'); ?>

    <?php wp_head(); ?>

    <?php if (IS_DEVELOPMENT): ?>
        <script type="module" src="//localhost:3000/@vite/client"></script>
    <?php endif; ?>
</head>
