FROM php:8.3-fpm
WORKDIR "/app"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# The official php image is Debian-based: PHP extensions must be built with
# docker-php-ext-install / PECL (the distro `php8.3-*` apt packages do not
# exist here and never load). We install the required build libraries and
# compile the extensions this boilerplate ships by default.
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        curl openssl ca-certificates \
        libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
        libxml2-dev libonig-dev libmagickwand-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        mysqli pdo pdo_mysql bcmath gd zip mbstring soap exif \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Imagick: WordPress prefers it over GD for resizing/regenerating uploads.
# The last tagged release (3.7.0) fails to build against PHP 8.3
# ("Unterminated preprocessor conditions"), so build from a pinned upstream
# commit past 3.7.0 that fixes the 8.3 stub generation.
RUN curl -fSL https://github.com/Imagick/imagick/archive/28f27044e435a2b203e32675e942eb8de620ee58.tar.gz -o /tmp/imagick.tar.gz \
    && mkdir -p /usr/src/php/ext/imagick \
    && tar -xf /tmp/imagick.tar.gz -C /usr/src/php/ext/imagick --strip-components=1 \
    && docker-php-ext-install -j"$(nproc)" imagick \
    && rm -rf /tmp/imagick.tar.gz

# Xdebug (step-debugging / profiling) is driven entirely by the Xdebug-3
# environment variables set in docker-compose.yml (XDEBUG_MODE, XDEBUG_CONFIG).
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.output_dir=/app/profiles" > /usr/local/etc/php/conf.d/zz-xdebug.ini
