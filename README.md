# Frojd-bedrock

The Fröjd fork of [Bedrock](https://roots.io/bedrock/)

Bedrock is a modern WordPress stack inspired by [Twelve-Factor App](http://12factor.net/) including the [WordPress specific version](https://roots.io/twelve-factor-wordpress/).


## Requirements

* Docker, use either Docker for [Mac](https://docs.docker.com/docker-for-mac/)/[Windows](https://docs.docker.com/docker-for-windows/) or [Docker toolbox](https://www.docker.com/products/docker-toolbox)

## Installation

1. Clone repo 
2. Make sure docker-machine is running and in your environment `docker-machine start && eval $(docker-machine env)`
3. Add your docker-machine ip to your /etc/hosts
4. Copy docker/config/db.example.env > docker/config/db.env & docker/config/web.example.env ->  docker/config/web.env (might need some editing, keep the example-file updated if you add/change variables)
5. run `docker-compose up`
6. wp-admin available at http://domain:port(default 8080)/wp/wp-admin

Copy-paste version
```
git clone git@github.com:Frojd/Frojd-Bedrock.git myproject.dev

cd myproject.dev/docker/config
cp db.example.env db.env
cp web.example.env web.env

cd ../../
docker-compose up
```

### Remote debugging for xdebug

If you want remote-debugging for xdebug you need to make sure some ENV-vars is available 
when docker-compose build.
You could either add them to your local environment (e.g. .zshrc) or add a .env-file in the 
project root.
```
XDEBUG_REMOTE_HOST="111.111.111.111"
XDEBUG_IDEKEY="PHPSTORM"
```

.zshrc version, supporting dynamic IP´s:
```
export XDEBUG_REMOTE_HOST=$(ifconfig | grep "inet " | grep broadcast | head -n 1 | awk '{print $2}')
export XDEBUG_IDEKEY="PHPSTORM"
```

## Git hooks

These hooks will automatically bump the application version when using `git flow release ...`

```bash
chmod 755 $PWD/git-hooks/bump-version.sh

ln -nfs $PWD/git-hooks/bump-version.sh .git/hooks/post-flow-release-start
ln -nfs $PWD/git-hooks/bump-version.sh .git/hooks/post-flow-hotfix-start
```

## Theme (Sage)

Default theme is based on [Sage](https://github.com/roots/sage/tree/master/). (version 9 alpha)

### Requirements

* [Node.js](http://nodejs.org/) >= 4.5

### Theme installation

1. Run `npm install` from the theme root directory
2. Edit `src/setup.php` to enable/disable theme features

### Build commands

* `npm start` — Compile assets when file changes are made, start BrowserSync session
* `npm run build` — Compile and optimize the files in your assets directory
* `npm run build:production` — Compile assets for production

#### Additional commands

* `npm run clean` — Remove your `dist/` folder
* `npm run lint` — Run eslint against your assets and build scripts
* `composer test` — Check your PHP for code smells with `phpmd` and PSR-2 compliance with `phpcs`


### Using BrowserSync

To use BrowserSync during `npm start` you need to update `devUrl` at the bottom of `assets/config.json` to reflect your local development hostname.

If your local development URL is `https://project-name.dev`, update the file to read:
```json
...
  "devUrl": "https://project-name.dev",
...
```

If you are not using [Bedrock](https://roots.io/bedrock/), update `publicPath` to reflect your folder structure:

```json
...
  "publicPath": "/wp-content/themes/sage/"
...
```

By default, BrowserSync will use webpack's [HMR](https://webpack.github.io/docs/hot-module-replacement.html), which won't trigger a page reload in your browser.

If you would like to force BrowserSync to reload the page whenever certain file types are edited, then add them to `watch` in `assets/config.json`.

```json
...
  "watch": [
    "assets/scripts/**/*.js",
    "templates/**/*.php",
    "src/**/*.php"
  ],
...
```


### Theme structure

```shell
themes/main/              # → Root of your Sage based theme
├── assets                # → Front-end assets
│   ├── config.json       # → Settings for compiled assets
│   ├── build/            # → Webpack and ESLint config
│   ├── fonts/            # → Theme fonts
│   ├── images/           # → Theme images
│   ├── scripts/          # → Theme JS
│   └── styles/           # → Theme stylesheets
├── composer.json         # → Autoloading for `src/` files
├── composer.lock         # → Composer lock file (never edit)
├── dist/                 # → Built theme assets (never edit)
├── functions.php         # → Composer autoloader, theme includes
├── index.php             # → Never manually edit
├── node_modules/         # → Node.js packages (never edit)
├── package.json          # → Node.js dependencies and scripts
├── screenshot.png        # → Theme screenshot for WP admin
├── src/                  # → Theme PHP
│   ├── lib/Sage/         # → Theme wrapper, asset manifest
│   ├── admin.php         # → Theme customizer setup
│   ├── filters.php       # → Theme filters
│   ├── helpers.php       # → Helper functions
│   └── setup.php         # → Theme setup
├── style.css             # → Theme meta information
├── templates/            # → Theme templates
│   ├── layouts/          # → Base templates
│   └── partials/         # → Partial templates
└── vendor/               # → Composer packages (never edit)
```

## Multisite configuration - Apache

Might be outdated. Written before docker.

### .htaccess

```
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteRule ^wp-admin$ wp-admin/ [R=301,L]
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^[^/]*/{0,1}(wp-(content|admin|includes|login).*)$ wp/$1 [L]
RewriteRule ^(.*\.php)$ wp/$1 [L]
RewriteRule . index.php [L]

# add a trailing slash to /wp-admin
RewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]

# fixes for multisite
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]
RewriteRule . index.php [L]

```

### wp-config.php

```
define('WP_ALLOW_MULTISITE', true);
define('DOMAIN_CURRENT_SITE', getenv('DOMAIN_CURRENT_SITE'));
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
define('UPLOADBLOGSDIR', '../app/uploads/sites');
```

### web.env 

```
DOMAIN_CURRENT_SITE=www.current-site.com
```

## Multisite configuration - Nginx

Please fill this out if you figure out how :)

## Deployment

This project use [Fabrik](https://github.com/Frojd/Fabrik/) for deployment.

1. `cd deploy`
2. `pip install -r requirements.txt`
3. `cp fabricrc.example.txt fabricrc.txt`
4. Update the fabricrc.txt file
5. First time - run `fabrik stage setup`
6. Deploy with `fabrik stage deploy`

If you prefer, you could let circle-ci handle the deployments:
1. copy .circlerc.example to .circlerc and fill it out
2. encrypt it with `openssl aes-256-cbc -e -in .circlerc -out .circlerc-crypt -k SOMEENCRYPTIONKEY`
3. Add SOMEENCRYPTIONKEY on circle as $KEY
4. Customize circle.yml for your needs

## Documentation

* [Fabrik docs](https://github.com/Frojd/Fabrik/)
* [Bedrock docs](https://roots.io/bedrock/docs/)
* [Sage docs](https://roots.io/sage/docs/)
