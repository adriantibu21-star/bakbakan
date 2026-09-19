FROM php:8.1-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the document root to your public_html folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public_html|' /etc/apache2/sites-available/000-default.conf

# Copy your application files
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]