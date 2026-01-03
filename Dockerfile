FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y git unzip zip

COPY apache/000-default.conf /etc/apache2/sites-available

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql mysqli && docker-php-ext-enable pdo_mysql mysqli

COPY ./composer.json /var/www/html

COPY ./composer.lock /var/www/html

RUN composer install

COPY . /var/www/html

RUN composer dump-autoload

EXPOSE 80