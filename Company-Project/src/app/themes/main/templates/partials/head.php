<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#ffffff"/>
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
                    environment: '<?= WP_ENV ?>',
                    ignoreErrors: [
                        // Random plugins/extensions
                        'top.GLOBALS',
                        // See: http://blog.errorception.com/2012/03/tale-of-unfindable-js-error.html
                        'originalCreateNotification',
                        'canvas.contentDocument',
                        'MyApp_RemoveAllHighlights',
                        'http://tt.epicplay.com',
                        'Can\'t find variable: ZiteReader',
                        'jigsaw is not defined',
                        'ComboSearch is not defined',
                        'http://loading.retry.widdit.com/',
                        'atomicFindClose',
                        // Facebook borked
                        'fb_xd_fragment',
                        // ISP "optimizing" proxy - `Cache-Control: no-transform` seems to reduce this. (thanks @acdha)
                        // See http://stackoverflow.com/questions/4113268/how-to-stop-javascript-injection-from-vodafone-proxy
                        'bmi_SafeAddOnload',
                        'EBCallBackMessageReceived',
                        // See http://toolbar.conduit.com/Developer/HtmlAndGadget/Methods/JSInjection.aspx
                        'conduitPage',
                        // Generic error code from errors outside the security sandbox
                        // You can delete this if using raven.js > 1.0, which ignores these automatically.
                        'Script error.'
                    ],
                    blacklistUrls: [
                        // Facebook flakiness
                        /graph\.facebook\.com/i,
                        // Facebook blocked
                        /connect\.facebook\.net\/en_US\/all\.js/i,
                        // Woopra flakiness
                        /eatdifferent\.com\.woopra-ns\.com/i,
                        /static\.woopra\.com\/js\/woopra\.js/i,
                        // Chrome extensions
                        /extensions\//i,
                        /^chrome:\/\//i,
                        // Other plugins
                        /127\.0\.0\.1:4001\/isrunning/i,  // Cacaoweb
                        /webappstoolbarba\.texthelp\.com\//i,
                        /metrics\.itunes\.apple\.com\.edgesuite\.net\//i
                    ]
                });
            }
        </script>
    <?php endif; ?>

    <?php wp_head(); ?>
</head>
