FROM ubuntu:16.04

MAINTAINER Frojd
LABEL version="v1.0.0"

# apt-get install All the things!

RUN apt-get update && apt-get -y install supervisor nginx \
    php-fpm php-zip php-xdebug php-mysql php-simplexml composer vim \
    && mkdir -p /var/run/php /var/log/supervisor /var/log/nginx /app


# Install configurations

COPY docker/files/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/files/config/nginx.conf /etc/nginx/sites-enabled/default


# Permission hack

RUN usermod -u 1000 www-data

# Set up remote debugging for xdebug

ARG XDEBUG_REMOTE_HOST
ARG XDEBUG_IDEKEY
RUN echo "xdebug.remote_enable=on" >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_host="${XDEBUG_REMOTE_HOST} >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini \
    && echo "xdebug.idekey="${XDEBUG_IDEKEY} >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini


# Open ports, multiple separated with space, e.g. EXPOSE 80 22 443

EXPOSE 80

# Default command for machine
CMD cd /app; composer install; supervisord
