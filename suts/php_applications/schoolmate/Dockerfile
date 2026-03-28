# Use a legacy image with older PHP (5.6) and Apache
FROM plutonianbe/php54-apache:latest

# Copy SchoolMate code into the web root
COPY src/ /var/www/html/

# Make sure Apache can write where necessary
RUN chown -R www-data:www-data /var/www/html

# Install and enable Xdebug
RUN pecl install xdebug-2.4.1
#this is not an official php image, so it doesn;t have the helper script docker-php-ext-enable
#RUN docker-php-ext-enable xdebug
#fix it manually
# Manually enable Xdebug (since docker-php-ext-enable is not available)
RUN mkdir -p /usr/local/etc/php/conf.d \
    && echo "zend_extension=$(find / -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.coverage_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=0" >> /usr/local/etc/php/conf.d/xdebug.ini

# Configure Xdebug
#RUN echo "xdebug.coverage_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini
#RUN echo "xdebug.remote_enable=0" >> /usr/local/etc/php/conf.d/xdebug.ini

# Copy custom coverage config
COPY coverage.ini /usr/local/etc/php/conf.d/coverage.ini

# Prepare coverage directory
RUN mkdir -p /var/www/coverage
RUN chmod 777 /var/www/coverage

# Copy coverage scripts
#COPY coverage-scripts/coverage_start.php /coverage-scripts/coverage_start.php
#COPY coverage-scripts/coverage_stop.php  /coverage-scripts/coverage_stop.php

# Expose port
EXPOSE 80

# Default command = run Apache in foreground
CMD ["apache2-foreground"]