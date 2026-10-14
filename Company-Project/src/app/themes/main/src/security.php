<?php

/**
 * SECURITY HARDENING CHECKLIST
 * =============================================================================
 * This file applies the hardening steps a THEME can own. The rest of the
 * checklist lives outside the theme (config / server / plugin) — use the boxes
 * below when spinning up a new project to see what still needs doing.
 *
 *   [x] done in the project (location noted)   [ ] TODO — set up per project
 *
 * THEME  (this file)
 *   [x] Hide WordPress version / generator + discovery links
 *   [x] Disable oEmbed provider (stop other sites embedding our posts)
 *   [x] Disable XML-RPC + pingbacks (brute-force amp, SSRF/DDoS)
 *   [x] Block REST /users + ?author=N user enumeration
 *   [x] Deny anonymous REST by default (also blocks the batch RCE vector)
 *   [x] Disable Application Passwords (enable per project if headless)
 *   [x] Serve /.well-known/security.txt disclosure channel
 *
 * CONFIG  (config/*.php)
 *   [x] DISALLOW_FILE_EDIT / DISALLOW_FILE_MODS — config/environments/*.php
 *   [x] AUTOMATIC_UPDATER_DISABLED (use Composer) — config/application.php
 *   [x] FORCE_SSL_ADMIN (stage + production) — config/environments/*.php
 *
 * CI / DEPLOY  (.github/workflows)
 *   [x] Trivy scan — vuln, misconfig, secrets — ci.yml
 *   [x] Trivy SBOM on deploy — deploy.yml (security-sbom job)
 *   [x] npm audit fix on dependency updates — update-dependencies.yml
 *   [x] Remove readme.html / other version-leaking files — deploy/tasks/after-symlink.yml
 *
 * SERVER / EDGE  (nginx / Cloudflare)
 *   [ ] WAF + brute-force protection on wp-login.php
 *   [ ] Restrict wp-admin / wp-login by IP where feasible
 *   [ ] Deny PHP execution in writable dirs, e.g. uploads (contains a planted
 *       shell); keep code owned by the deploy user, not www-data
 *   [ ] Security headers:
 *         Strict-Transport-Security: max-age=31536000; includeSubDomains
 *         X-Frame-Options: SAMEORIGIN
 *         X-Content-Type-Options: nosniff
 *         Referrer-Policy: strict-origin-when-cross-origin
 *         Permissions-Policy: geolocation=(), microphone=(), camera=()
 *
 * PLUGIN & MAINTENANCE
 *   [ ] Two-factor auth for admin accounts
 *   [ ] Audit logging
 *   [ ] Anti-spam if comments are enabled
 *   [ ] Keep core + plugins updated — run scripts/update.sh
 *   [ ] Review and cull unused plugins regularly
 *
 * These two guides back the checklist; where a specific item has its own
 * authoritative source (a CVE or OWASP test) it is cited on that code block.
 * @see https://developer.wordpress.org/advanced-administration/security/hardening/ Official WordPress hardening guide
 * @see https://cio.ubc.ca/information-security/policy-standards-resources/M5/gui-securing-wordpress UBC "Securing WordPress" checklist
 * @see https://patchstack.com/articles/wordpress-security-headers/ Security header values (vendor guide)
 */

namespace App\Security;

// Remove version/generator disclosure so the WordPress version can't be read
// from the markup and matched to a known CVE.
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');

add_filter('the_generator', function() {return '';});

add_filter('after_setup_theme', function () {
    // Remove link-rel attributes
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');

    // Remove headers
    remove_action('template_redirect', 'wp_shortlink_header', 11);
    remove_action('template_redirect', 'rest_output_link_header', 11);
});

// Disable the oEmbed provider – other sites should not be able to embed our posts
remove_action('rest_api_init', 'wp_oembed_register_route');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
add_filter('embed_oembed_discover', '__return_false');

// Disable XML-RPC – legacy endpoint used for brute-force amplification and
// pingback-based SSRF/DDoS; not needed by a standard site.
add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', function ($methods) {
    unset($methods['pingback.ping'], $methods['pingback.extensions.getPingbacks']);
    return $methods;
});

// Disable Application Passwords – off by default since a standard (non-headless)
// site has no API consumer needing one, and each is a full-API credential.
// Enable per project if an external integration authenticates over REST.
add_filter('wp_is_application_passwords_available', '__return_false');


// Disable the /users endpoint – Make it harder to find potential usernames to
// launch brute force attempts against
// @see https://owasp.org/www-project-web-security-testing-guide/stable/4-Web_Application_Security_Testing/03-Identity_Management_Testing/04-Testing_for_Account_Enumeration_and_Guessable_User_Account
add_filter('rest_endpoints', function ($endpoints) {
    if(is_admin() || is_user_logged_in()) {
        return $endpoints;
    }

    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }

    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }

    return $endpoints;
});


/**
 * Deny anonymous REST requests unless the route is allow-listed. Authenticated
 * requests always pass, so the block editor and logged-in flows are unaffected.
 * The batch controller (a pre-auth attack vector) is blocked by being left off
 * the list.
 *
 * This gates ANONYMOUS access only — it does not register routes. Keep
 * `show_in_rest => true` on post types (the block editor needs it); that just
 * makes the route exist for logged-in users. Add a pattern below only when the
 * public should read a route without auth, e.g. '#^/wp/v2/news#' for the "news"
 * post type (match its rest_base), '#^/wc/store/#' for the WooCommerce Store API.
 */
add_filter('rest_authentication_errors', function ($result) {
    if (!empty($result) || is_user_logged_in()) {
        return $result;
    }

    $route = '/' . ltrim($GLOBALS['wp']->query_vars['rest_route'] ?? '', '/');

    $publicRoutes = [
        '#^/$#',  // API index
    ];

    foreach ($publicRoutes as $pattern) {
        if (preg_match($pattern, $route)) {
            return $result;
        }
    }

    return new \WP_Error(
        'rest_not_authenticated',
        __('Authentication required for this endpoint.', 'sage'),
        ['status' => 401]
    );
});


// Block ?author=N enumeration – stops anonymous callers from mapping author IDs
// to usernames via the redirect to /author/{username}.
// @see https://owasp.org/www-project-web-security-testing-guide/stable/4-Web_Application_Security_Testing/03-Identity_Management_Testing/04-Testing_for_Account_Enumeration_and_Guessable_User_Account
add_action('template_redirect', function () {
    if (is_user_logged_in()) {
        return;
    }

    if (isset($_GET['author']) && preg_match('/^\d+$/', (string) $_GET['author'])) {
        wp_die(__('Not found.', 'sage'), '', ['response' => 404]);
    }
});


// Serve /.well-known/security.txt (RFC 9116) so researchers have a disclosure
// channel. Served dynamically so the required Expires date never goes stale.
// Update the contact if the project uses a different address.
add_action('init', function () {
    if (($_SERVER['REQUEST_URI'] ?? '') !== '/.well-known/security.txt') {
        return;
    }

    header('Content-Type: text/plain; charset=utf-8');
    $expires = gmdate('Y-m-d\TH:i:s\Z', time() + YEAR_IN_SECONDS);
    echo "Contact: mailto:security@frojd.se\n";
    echo "Expires: {$expires}\n";
    echo "Preferred-Languages: en, sv\n";
    exit;
});
