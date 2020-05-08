FROM phpdockerio/php72-fpm:latest
WORKDIR "/app"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.2-mysql php7.2-zip \
    php7.2-xdebug php7.2-mysql php7.2-simplexml php7.2-gd php7.2-imagick \
    php7.2-mbstring php7.2-soap php7.2-curl php7.2-gettext curl openssl\
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

ARG XDEBUG_REMOTE_HOST
ARG XDEBUG_IDEKEY
RUN echo "xdebug.remote_enable=on" >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_host="${XDEBUG_REMOTE_HOST} >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.idekey="${XDEBUG_IDEKEY} >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_enable_trigger=1" >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_output_dir=/app/profiles" >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "curl.cainfo=/usr/lib/ssl/cert.pem" >> /etc/php/7.2/mods-available/curl.ini \
    && curl https://curl.haxx.se/ca/cacert.pem > /usr/lib/ssl/cert.pem \
    && rm /etc/php/7.2/cli/conf.d/20-xdebug.ini