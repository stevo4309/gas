FROM php:8.2-apache

WORKDIR /var/www/html

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first to leverage cache
COPY composer.json composer.lock ./

# Run composer install
RUN composer install --no-dev --optimize-autoloader

# Now copy the rest of your application
COPY . .

EXPOSE 80

CMD ["apache2-foreground"]
