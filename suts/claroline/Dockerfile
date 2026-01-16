# Use a legacy image with older PHP and Apache
FROM plutonianbe/php54-apache:latest

# Copy claroline code into the web root
COPY src/ /var/www/html/

# Make sure Apache can write where necessary
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80

# Default command = run Apache in foreground
CMD ["apache2-foreground"]
