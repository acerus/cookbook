FROM php:7.0-apache

MAINTAINER Charlie Jackson <contact@charliejackson.com>

RUN apt-get update && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev
RUN docker-php-source extract
RUN docker-php-ext-install mysqli
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-source delete
RUN a2enmod rewrite
COPY php.ini /usr/local/etc/php/
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/content
