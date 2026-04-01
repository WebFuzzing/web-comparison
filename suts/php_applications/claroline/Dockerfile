FROM plutonianbe/php54-apache:latest

COPY src/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Install Xdebug 2.4.1
RUN pecl install xdebug-2.4.1

RUN mkdir -p /var/www/coverage && chown www-data:www-data /var/www/coverage

# Find xdebug.so and write the ini — all in one safe shell script
RUN mkdir -p /etc/php5/apache2/conf.d && \
    XDEBUG_SO=$(find /usr -name 'xdebug.so' 2>/dev/null | head -1) && \
    if [ -z "$XDEBUG_SO" ]; then \
        XDEBUG_SO=$(find / -name 'xdebug.so' 2>/dev/null | head -1); \
    fi && \
    echo "Found xdebug at: $XDEBUG_SO" && \
    printf "zend_extension=%s\n" "$XDEBUG_SO" > /etc/php5/apache2/conf.d/xdebug.ini && \
    echo "xdebug.remote_host=host.docker.internal" >> /etc/php5/apache2/conf.d/xdebug.ini && \
    cat /etc/php5/apache2/conf.d/xdebug.ini

COPY coverage.ini /etc/php5/apache2/conf.d/coverage.ini

EXPOSE 80
CMD ["apache2-foreground"]