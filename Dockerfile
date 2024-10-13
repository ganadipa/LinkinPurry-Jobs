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

# Set the working directory
WORKDIR /var/www/html

# Copy application source code
COPY . /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
