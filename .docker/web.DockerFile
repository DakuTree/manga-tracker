FROM php:7.2-apache

WORKDIR /app

# Note: We don't need upgrade here.
RUN apt-get update --quiet
RUN apt-get install --quiet --yes --no-install-recommends \
 git \
 libzip-dev \
 zip \
 unzip \
 libxml2-dev

RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install \
 pdo \
 pdo_mysql \
 mysqli \
 sockets \
 zip \
 dom

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer global require hirak/prestissimo

COPY composer.json /app
COPY composer.lock /app
RUN composer install --no-interaction --no-scripts --no-suggest

COPY . /app

# Dev-only hack.
RUN chmod 777 /app/application/logs

RUN composer run-script post-install-cmd

COPY ./.docker/vhost.conf        /etc/apache2/sites-available/001-localhost.conf
COPY ./.docker/vhost-static.conf /etc/apache2/sites-available/001-static.localhost.conf
RUN a2dissite 000-* && a2ensite 001-*

RUN a2enmod \
 rewrite \
 expires \
 headers

# Required for init
RUN chmod +x .docker/wait-for-it.sh
