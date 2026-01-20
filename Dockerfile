# Use official PHP + Apache image
FROM php:8.2-apache

# Copy all files from the repo root into Apache web root
COPY . /var/www/html/

# Enable Apache rewrite module
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Set working directory
WORKDIR /var/www/html

# Start Apache server
CMD ["apache2-foreground"]
