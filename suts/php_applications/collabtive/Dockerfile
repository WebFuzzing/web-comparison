FROM php:5.6-apache

# Install the mysqli and pdo_mysql extensions
RUN docker-php-ext-install mysqli pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application source code to the webroot (handled by docker-compose volume mount)
# COPY . /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

RUN pecl install xdebug-2.5.5 \
    && docker-php-ext-enable xdebug

RUN echo "xdebug.coverage_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=0" >> /usr/local/etc/php/conf.d/xdebug.ini

COPY coverage.ini /usr/local/etc/php/conf.d/coverage.ini
RUN mkdir -p /var/www/coverage
RUN chmod 777 /var/www/coverage

# Expose port 80
EXPOSE 80 