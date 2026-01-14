FROM php:5.6-apache

# Install the mysqli and pdo_mysql extensions
RUN docker-php-ext-install mysqli pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application source code to the webroot (handled by docker-compose volume mount)
# COPY . /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80 