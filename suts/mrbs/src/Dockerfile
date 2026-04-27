FROM php:8.4-apache

RUN a2enmod rewrite
RUN apt-get update && apt-get install -y libicu-dev locales-all \
 && apt-get clean
RUN docker-php-ext-install mysqli pdo pdo_mysql intl

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY coverage.ini /usr/local/etc/php/conf.d/coverage.ini
RUN mkdir -p /var/www/coverage
RUN chmod 777 /var/www/coverage

COPY web/ /var/www/html/
COPY docker-config.inc.php /var/www/html/config.inc.php
