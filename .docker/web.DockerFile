FROM php:7.2-apache

COPY . /app
WORKDIR /app

COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY ./.docker/vhost.conf /etc/apache2/sites-available/001-localhost.conf
COPY ./.docker/vhost-static.conf /etc/apache2/sites-available/001-static.localhost.conf
RUN a2dissite 000-*
RUN a2ensite 001-*

RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install pdo pdo_mysql mysqli sockets
RUN a2enmod rewrite expires headers

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction
