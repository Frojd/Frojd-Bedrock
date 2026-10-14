<?php
/* Staging */
ini_set('display_errors', 0);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true); // this disables all file modifications including updates and update notifications
define('FORCE_SSL_ADMIN', true); // force HTTPS for wp-admin/login (needs X-Forwarded-Proto honored behind a TLS-terminating proxy)
