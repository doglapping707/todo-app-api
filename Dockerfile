FROM php:8.3-apache

# Install Package
RUN apt update && apt install -y \
    git \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

# Rewrite URL
RUN a2enmod rewrite

# Place Directory
COPY ./src /var/www/html
COPY ./conf/apache/000-default.conf /etc/apache2/sites-enabled

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
RUN composer install

RUN chown -Rf www-data:www-data ./