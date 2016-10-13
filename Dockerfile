FROM ubuntu:16.04

MAINTAINER Frojd
LABEL version="v1.0.0"

# Install relevant software
RUN apt-get update && apt-get -y install supervisor nginx \
    php-fpm php-zip php-xdebug php-mysql php-simplexml composer vim
RUN mkdir -p /var/run/php /var/log/supervisor /var/log/nginx /app


# Install configurations
COPY docker/files/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/files/config/nginx.conf /etc/nginx/sites-enabled/default

# Install php dependencies

COPY ./composer.json /app
COPY ./composer.lock /app

WORKDIR /app
RUN composer install

# Permission hack
RUN usermod -u 1000 www-data

EXPOSE 80

CMD ["supervisord"]