FROM php:8.3-apache

# パッケージをインストール
RUN apt update && apt install -y \
    git \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

# mod_rewriteを有効化
RUN a2enmod rewrite

# ディレクトリをマウント
COPY ./src /var/www/html
COPY ./conf/apache/000-default.conf /etc/apache2/sites-enabled

# Composerをインストール
COPY --from=composer /usr/bin/composer /usr/bin/composer

# ライブラリをインストール
WORKDIR /var/www/html
RUN composer install

RUN chown -Rf www-data:www-data ./