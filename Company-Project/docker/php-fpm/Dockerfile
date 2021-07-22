FROM phpdockerio/php74-fpm:latest
WORKDIR "/app"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.4-mysql php7.4-zip \
    php7.4-xdebug php7.4-mysql php7.4-simplexml php7.4-gd php7.4-imagick \
    php7.4-mbstring php7.4-soap php7.4-curl php7.4-gettext curl openssl\
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

ARG XDEBUG_REMOTE_HOST
ARG XDEBUG_IDEKEY
RUN echo "xdebug.remote_enable=on" >> /etc/php/7.4/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /etc/php/7.4/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_host="${XDEBUG_REMOTE_HOST} >> /etc/php/7.4/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.idekey="${XDEBUG_IDEKEY} >> /etc/php/7.4/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_enable_trigger=1" >> /etc/php/7.4/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_output_dir=/app/profiles" >> /etc/php/7.4/fpm/conf.d/20-xdebug.ini \
    && echo "curl.cainfo=/usr/lib/ssl/cert.pem" >> /etc/php/7.4/mods-available/curl.ini \
    && curl https://curl.haxx.se/ca/cacert.pem > /usr/lib/ssl/cert.pem \
    && rm /etc/php/7.4/cli/conf.d/20-xdebug.ini