FROM php:7.4-fpm

RUN apt-get update && apt-get install -y libpq-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql

RUN docker-php-ext-install opcache

COPY ./dockerfiles/php/conf.d /usr/local/etc/php/conf.d

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www

RUN mkdir -p /var/www/var/ && chmod -R 777 /var/www/var

COPY ./ ./
