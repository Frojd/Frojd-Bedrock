<?php

namespace App\Sentry;

add_action('wp_head', function() {
    if (!defined('SENTRY_DSN') || empty(SENTRY_DSN))
        return;

    $tags = [];
    if(defined('CURRENT_SITE') && !empty(CURRENT_SITE))
        $tags['current_site'] = CURRENT_SITE;
    ?>
    <script
        src="https://browser.sentry-cdn.com/6.13.3/bundle.min.js"
        integrity="sha384-sGMbmxgVprpEFMz6afNDyADd4Kav86v5Tvo2Y6w5t8tHUn1P1at3lCjN7IQo2c7E"
        crossorigin="anonymous"></script>
    <script>
        if (window.Sentry) {
            Sentry.init({
                dsn: '<?= SENTRY_DSN ?>',
                release: '<?= APP_VERSION ?>',
                tags: <?= json_encode($tags); ?>,
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
    <?php
});
