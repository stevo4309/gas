FROM php:8.2-apache

WORKDIR /var/www/html

# Install dependencies for PHP extensions (if needed)
RUN apt-get update && apt-get install -y libzip-dev zip unzip && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install composer from the official composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files to container
COPY . /var/www/html/

# Install PHP dependencies using composer (production mode)
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["apache2-foreground"]
