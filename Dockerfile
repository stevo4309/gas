# Dockerfile for PHP + Apache + Composer + PHPMailer
FROM php:8.2-apache

# Install required packages for Composer and PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    zip \
    libzip-dev \
    && docker-php-ext-install zip mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy app source
COPY . .

# Run Composer install to load dependencies (like PHPMailer)
RUN composer install --no-dev --optimize-autoloader

# Expose the port Apache will run on
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
