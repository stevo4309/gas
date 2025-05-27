# Dockerfile for PHP + Apache on Railway
FROM php:8.2-apache

# Copy all files into the container
COPY . /var/www/html/

# Install PHP extensions (like MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose the port
EXPOSE 80
