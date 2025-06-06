# Use the official PHP image
FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git zip unzip curl libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all files
COPY . .

# Run composer install
RUN composer install --no-dev --optimize-autoloader

# Expose port (if needed)
EXPOSE 8000

# Default command
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
