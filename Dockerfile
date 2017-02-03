FROM php:7.0-apache

MAINTAINER Charlie Jackson <contact@charliejackson.com>

RUN docker-php-source extract
RUN docker-php-ext-install mysqli
RUN docker-php-source delete
RUN a2enmod rewrite
COPY php.ini /usr/local/etc/php/
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/content
