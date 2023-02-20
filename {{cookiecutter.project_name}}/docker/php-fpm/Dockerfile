FROM phpdockerio/php:8.1-fpm
WORKDIR "/app"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php8.1-mysql php8.1-zip \
    php8.1-xdebug php8.1-mysql php8.1-simplexml php8.1-gd php8.1-imagick \
    php8.1-mbstring php8.1-soap php8.1-curl php8.1-gettext curl openssl\
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

ARG XDEBUG_REMOTE_HOST
ARG XDEBUG_IDEKEY
RUN echo "xdebug.remote_enable=on" >> /etc/php/8.1/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /etc/php/8.1/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_host="${XDEBUG_REMOTE_HOST} >> /etc/php/8.1/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.idekey="${XDEBUG_IDEKEY} >> /etc/php/8.1/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_enable_trigger=1" >> /etc/php/8.1/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_output_dir=/app/profiles" >> /etc/php/8.1/fpm/conf.d/20-xdebug.ini \
    && echo "curl.cainfo=/usr/lib/ssl/cert.pem" >> /etc/php/8.1/mods-available/curl.ini \
    && curl https://curl.haxx.se/ca/cacert.pem > /usr/lib/ssl/cert.pem \
    && rm /etc/php/8.1/cli/conf.d/20-xdebug.ini