# Use official PHP + Apache image
FROM php:8.2-apache

# Copy all repo files into Apache web root
COPY . /var/www/html/

# Enable Apache rewrite module
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
