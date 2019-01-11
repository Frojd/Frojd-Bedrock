FROM ubuntu:18.04

MAINTAINER Frojd
LABEL version="v1.0.0"

ENV TZ=Europe/Stockholm
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# apt-get install All the things!

RUN apt-get update && apt-get -y install supervisor nginx \
    php-fpm php-zip php-xdebug php-mysql php-simplexml \
    php-gd php-imagick php-mbstring php-soap php-curl \
    composer vim curl mysql-client \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && mkdir -p /var/run/php /var/log/supervisor /var/log/nginx /app

# wp-cli
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x wp-cli.phar && \
    mv wp-cli.phar /usr/local/bin/wp


# Install configurations

COPY docker/files/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/files/config/nginx.conf /etc/nginx/sites-enabled/default
COPY docker/files/config/php.ini /etc/php/7.2/fpm


# Permission hack

RUN usermod -u 1000 www-data

# Set up remote debugging for xdebug

ARG XDEBUG_REMOTE_HOST
ARG XDEBUG_IDEKEY
RUN echo "xdebug.remote_enable=on" >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_host="${XDEBUG_REMOTE_HOST} >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.idekey="${XDEBUG_IDEKEY} >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_enable_trigger=1" >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_output_dir=/app/profiles" >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini \
    && rm /etc/php/7.2/cli/conf.d/20-xdebug.ini


# Open ports, multiple separated with space, e.g. EXPOSE 80 22 443

EXPOSE 80

# Default command for machine
CMD cd /app; composer install; supervisord
