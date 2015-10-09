# Frojd-bedrock
The Fröjd fork of [Bedrock](https://roots.io/bedrock/)

Bedrock is a modern WordPress stack that helps you get started with the best development tools and project structure.

Much of the philosophy behind Bedrock is inspired by the [Twelve-Factor App](http://12factor.net/) methodology including the [WordPress specific version](https://roots.io/twelve-factor-wordpress/).

## Features

* Better folder structure
* Dependency management with [Composer](http://getcomposer.org)
* Easy WordPress configuration with environment specific files
* Environment variables with [Dotenv](https://github.com/vlucas/phpdotenv)
* Autoloader for mu-plugins (use regular plugins as mu-plugins)

## Requirements

* PHP >= 5.5
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

## Installation

1. Run `composer create-project frojd/frojd-bedrock project_name` and fill in the requested credentials
2. Set your site vhost document root to `/path/to/site/web/` (`/path/to/site/current/web/` if using deploys)
3. Access WP admin at `http://example.com/wp/wp-admin`

## Documentation

Official bedrock documentation is available at [https://roots.io/bedrock/docs/](https://roots.io/bedrock/docs/).
