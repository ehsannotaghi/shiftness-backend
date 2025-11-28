# Official PHP image with Apache
FROM php:8.2-apache

# Copy application files to Apache document root
COPY . /var/www/html/

# Ensure correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Install system dependencies for Composer and PostgreSQL before installing PHP extension
RUN apt-get update && \
    apt-get install -y --no-install-recommends git unzip ca-certificates libpq-dev && \
    rm -rf /var/lib/apt/lists/*

# If a composer.json is present, install composer dependencies (no vendor checked into repo)
RUN if [ -f /var/www/html/composer.json ]; then \
      php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
      php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
      composer install --no-dev --optimize-autoloader --working-dir=/var/www/html; \
    fi

# Install PHP PostgreSQL extension
RUN docker-php-ext-install pdo_pgsql

EXPOSE 80

# Default Apache foreground command provided by the base image
CMD ["apache2-foreground"]
