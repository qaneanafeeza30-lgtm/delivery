FROM php:8.2-apache

# Install mysqli and pdo extensions for PHP database connection
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy all project files to Apache root directory
COPY . /var/www/html/

# Set folder permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80