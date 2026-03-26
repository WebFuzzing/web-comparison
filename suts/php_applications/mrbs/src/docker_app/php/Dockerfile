FROM php:7.3-apache

RUN a2enmod rewrite
RUN apt-get update && apt-get install -y locales-all
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug

COPY coverage.ini /usr/local/etc/php/conf.d/coverage.ini
RUN mkdir -p /var/www/coverage
RUN chmod 777 /var/www/coverage
