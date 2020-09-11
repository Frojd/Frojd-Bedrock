# [Sage](https://roots.io/sage/)
[![Packagist](https://img.shields.io/packagist/vpre/roots/sage.svg?style=flat-square)](https://packagist.org/packages/roots/sage)
[![devDependency Status](https://img.shields.io/david/dev/roots/sage.svg?style=flat-square)](https://david-dm.org/roots/sage#info=devDependencies)
[![Build Status](https://img.shields.io/travis/roots/sage.svg?style=flat-square)](https://travis-ci.org/roots/sage)

Sage is a WordPress starter theme with a modern development workflow.

**Sage 9 is in active development and is only currently in alpha. The `master` branch tracks Sage 9 development. If you want a stable version, use the [latest Sage 8 release](https://github.com/roots/sage/releases/latest).**

## Features

* Sass for stylesheets
* ES6 for JavaScript
* [Webpack](https://webpack.github.io/) for compiling assets, optimizing images, and concatenating and minifying files
* [BrowserSync](http://www.browsersync.io/) for synchronized browser testing
* [Bootstrap 4](http://getbootstrap.com/) for a front-end framework (can be removed or replaced)
* Template inheritance with the [theme wrapper](https://roots.io/sage/docs/theme-wrapper/)

See a working example at [roots-example-project.com](https://roots-example-project.com/).

## Requirements

Make sure all dependencies have been installed before moving on:

* [PHP](http://php.net/manual/en/install.php) >= 5.5.x
* [Composer](https://getcomposer.org/download/)
* [Node.js](http://nodejs.org/) >= 4.5

## Theme installation

Install Sage using Composer from your WordPress themes directory (replace `your-theme-name` below with the name of your theme):

```shell
# @ example.com/site/web/app/themes/
$ composer create-project roots/sage your-theme-name dev-master
```

## Theme structure

```shell
themes/your-theme-name/   # → Root of your Sage based theme
├── assets                # → Front-end assets
│   ├── config.json       # → Settings for compiled assets
│   ├── build/            # → Webpack and ESLint config
│   ├── fonts/            # → Theme fonts
│   ├── images/           # → Theme images
│   ├── scripts/          # → Theme JS
│   └── styles/           # → Theme stylesheets
├── composer.json         # → Autoloading for `src/` files
├── composer.lock         # → Composer lock file (never edit)
├── dist/                 # → Built theme assets (never edit)
├── functions.php         # → Composer autoloader, theme includes
├── index.php             # → Never manually edit
├── node_modules/         # → Node.js packages (never edit)
├── package.json          # → Node.js dependencies and scripts
├── screenshot.png        # → Theme screenshot for WP admin
├── src/                  # → Theme PHP
│   ├── lib/Sage/         # → Theme wrapper, asset manifest
│   ├── posttypes/        # → Theme custom post types
│   ├── taxonomies/       # → Theme custom taxonomies
│   ├── walkers/          # → Theme custom walkers
│   ├── admin.php         # → Theme customizer setup
│   ├── filters.php       # → Theme filters
│   ├── helpers.php       # → Helper functions
│   └── setup.php         # → Theme setup
├── style.css             # → Theme meta information
├── templates/            # → Theme templates
│   ├── layouts/          # → Base templates
│   └── partials/         # → Partial templates
└── vendor/               # → Composer packages (never edit)
```

## Theme setup

Edit `src/setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, post formats, and sidebars.

## Theme base settings

There is some base functionality and templates in the theme that can be used in your project

### Settings

We use ACF to add a settings page in admin to set some fields:
* Footer - Flexible content fields that are column based
* Popups - Fields for text in Browser and Cookie popups
* 404 - Title and description fields for 404 template

We use ACF to add other settings to posts or pages:
* Preamble - Added to all pages and posts, used as meta description and in listings
* Hero & Blurbs - Added to Front page
* Hide in menu - Added to pages to be able to hide a page in all menus, specifically when using automatic hierarchical menus.
* Related - Added to posts for selecting related posts

### Templates
* Front-page - Template used when setting static page, includes hero block, custom blurbs and latest list
* Single - Used for posts and other custom post types, has a related list block
* Article - Used for single post/page
* Item - Used for post/page in lists
* Footer - Displaying the column based footer settings
* Header - Primary and service menu included and implemented in header including mobile menu
* List - Used by for example related, has basic column-based layout of list of items
* Blurbs - Used by start page for listing custom blurbs
* Favicons - Prints out favicon links if they exist in favicons-directory

### Custom functions
* title() - Used for printing out title in content-header template, helps make sure only this templates defines the h1
* get_field_group() - Used to get a the first item in a ACF repeater field as an object, used when you want to group fields into a single object e.g. hero.
* the_svg_icon/get_the_svg_icon - Used to print the svg-icon content instead of using as image.
* the_post_thumbnail_background - Used to print the post thumbnail as background image, includes title attribute if alt-field exists.
* get_post_thumbnail_data - Collects data about the post thumbnail into an object (title, alt, caption, description, src).

### Filters
* the_content - Wraps an iframe into a container to make it responsive
* tiny_mce_before_init - Changes settings in tinymce WYSIWYG to remove some settings, e.g. h1, and add Facts format selection
* get_the_excerpt - If the excerpt-field is empty it will use preamble instead (in lists, meta description etc), and last use body content.

## Theme development

Sage uses [Webpack](https://webpack.github.io/) as a build tool and [npm](https://www.npmjs.com/) to manage front-end packages.

### Install dependencies

From the command line on your host machine (not on your Vagrant development box), navigate to the theme directory then run `npm install`:

```shell
# @ example.com/site/web/app/themes/your-theme-name
$ npm install
```

You now have all the necessary dependencies to run the build process.

### Build commands

* `gulp watch` — Compile assets when file changes are made, start BrowserSync session
* `gulp` — Compile and optimize the files in your assets directory
* `gulp --production` — Compile assets for production

#### Additional commands

* `composer test` — Check your PHP for code smells with `phpmd` and PSR-2 compliance with `phpcs`

### Using BrowserSync

To use BrowserSync during `gulp watch` you need to update BrowserSync proxy in `gulpfile.js` to reflect your local development hostname.

If your local development URL is `https://project-name.dev`, update the file to read:
```
...
    .browserSync({proxy: 'project-name.dev:8080'})
...
```

## Documentation

Sage 8 documentation is available at [https://roots.io/sage/docs/](https://roots.io/sage/docs/).

Sage 9 documention is currently in progress and can be viewed at [https://github.com/roots/docs/tree/sage-9/sage](https://github.com/roots/docs/tree/sage-9/sage).

## Contributing

Contributions are welcome from everyone. We have [contributing guidelines](https://github.com/roots/guidelines/blob/master/CONTRIBUTING.md) to help you get started.

## Community

Keep track of development and community news.

* Participate on the [Roots Discourse](https://discourse.roots.io/)
* Follow [@rootswp on Twitter](https://twitter.com/rootswp)
* Read and subscribe to the [Roots Blog](https://roots.io/blog/)
* Subscribe to the [Roots Newsletter](https://roots.io/subscribe/)
* Listen to the [Roots Radio podcast](https://roots.io/podcast/)
