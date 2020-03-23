<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php get_template_part('partials/favicons'); ?>

    <?php if (SENTRY_DSN) : ?>
        <script
            src="https://browser.sentry-cdn.com/5.15.0/bundle.min.js"
            integrity="sha384-+ysfQckQvwCB5SppH41IScIz/Iynt2pePnJNMl+D7ZOzDJ+VYhQEuwB0pA60IDM0"
            crossorigin="anonymous"></script>
        <script>
            if (window.Sentry) {
                Sentry.init({
                    dsn: '<?= SENTRY_DSN ?>',
                    release: '<?= APP_VERSION ?>',
                    // tags: {current_site: '<?= CURRENT_SITE ?>'},
                    environment: '<?= WP_ENV ?>'
                });
            }
        </script>
    <?php endif; ?>

    <?php wp_head(); ?>
</head>
