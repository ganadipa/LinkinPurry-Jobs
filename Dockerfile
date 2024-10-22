# Use the official PHP image with Apache
FROM php:8.3-apache

# Docker php extension
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy custom php.ini to the PHP configuration directory
COPY php.ini /usr/local/etc/php/

# Set the working directory
WORKDIR /var/www/html

# Copy application source code
COPY . /var/www/html

# Create the uploads directory with write permissions for Apache
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 777 /var/www/html/uploads

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
