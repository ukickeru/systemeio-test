ARG PHP_IMAGE

FROM composer:latest AS composer

FROM ${PHP_IMAGE} AS php

RUN apt-get update && \
    apt-get install -y git libzip-dev zip && \
    docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN mkdir -p /var/www/app && \
    chown www-data:www-data /var/www/app

WORKDIR /var/www/app
USER www-data

COPY --chown=www-data:www-data ./ ./

RUN composer install