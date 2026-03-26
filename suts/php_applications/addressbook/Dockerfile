# Use official PHP 5.6 with Apache
FROM php:5.6-apache

# Set working directory inside container
WORKDIR /var/www/html

# Copy your PHP project files into the container
COPY src/ /var/www/html/

# Enable common PHP extensions (adjust as needed)
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN pecl install xdebug-2.5.5 \
    && docker-php-ext-enable xdebug

RUN echo "xdebug.coverage_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=0" >> /usr/local/etc/php/conf.d/xdebug.ini

COPY coverage.ini /usr/local/etc/php/conf.d/coverage.ini
RUN mkdir -p /var/www/coverage
RUN chmod 777 /var/www/coverage

# Expose port 80 (HTTP)
EXPOSE 80

# Default command is Apache, already configured in base image
