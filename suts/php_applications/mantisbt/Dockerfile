FROM php:7.4-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install \
        mysqli \
        pdo \
        pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

#xdebug stuff
RUN pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug

COPY coverage.ini /usr/local/etc/php/conf.d/coverage.ini
RUN mkdir -p /var/www/coverage
RUN chmod 777 /var/www/coverage
#end xdebug stuff

# Set timezone (avoid PHP warnings)
RUN echo "date.timezone = UTC" > /usr/local/etc/php/conf.d/timezone.ini

WORKDIR /var/www/html
EXPOSE 80
