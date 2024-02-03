FROM php:8.3-apache

RUN apt update && apt install -y \
    git \
    zip \
    unzip

ADD ./src /var/www/html
ADD ./conf/apache/000-default.conf /etc/apache2/sites-enabled

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
RUN composer install