# Use official PHP 5.6 with Apache
FROM php:5.6-apache

# Set working directory inside container
WORKDIR /var/www/html

# Copy your PHP project files into the container
COPY src/ /var/www/html/

# Enable common PHP extensions (adjust as needed)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Expose port 80 (HTTP)
EXPOSE 80

# Default command is Apache, already configured in base image
