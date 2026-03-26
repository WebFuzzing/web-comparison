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

# Set timezone (avoid PHP warnings)
RUN echo "date.timezone = UTC" > /usr/local/etc/php/conf.d/timezone.ini

WORKDIR /var/www/html
EXPOSE 80
