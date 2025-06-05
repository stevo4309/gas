FROM php:8.2-apache

WORKDIR /var/www/html

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install composer binary from official composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy only composer files first for better caching
COPY composer.json composer.lock ./

# Run composer install to install dependencies in vendor/
RUN composer install --no-dev --optimize-autoloader

# Now copy the rest of your app files
COPY . .

EXPOSE 80

CMD ["apache2-foreground"]
