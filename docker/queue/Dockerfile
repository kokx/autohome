FROM php:7.3-cli-alpine
MAINTAINER  <pieter@kokx-bto>

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /usr/src/autohome
WORKDIR /usr/src/autohome

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --optimize-autoloader
RUN php public/index.php
RUN ./vendor/bin/doctrine orm:generate-proxies

CMD [ "php", "./queue.php"]
