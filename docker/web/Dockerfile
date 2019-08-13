FROM php:7.3-fpm-alpine
MAINTAINER  <pieter@kokx.org>

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /usr/src/autohome
WORKDIR /usr/src/autohome

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --optimize-autoloader
