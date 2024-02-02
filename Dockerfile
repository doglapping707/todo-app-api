FROM php:8.3-apache

RUN apt update && apt install -y \
    git \
    zip \
    unzip

COPY ./src /var/www/html
COPY ./conf/apache/000-default.conf /etc/apache2/sites-enabled

WORKDIR /var/www/html

# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install
RUN composer update

CMD php artisan serve --host=0.0.0.0 --port=8080

EXPOSE 8080