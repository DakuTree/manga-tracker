FROM php:7.2-apache

COPY . /app
WORKDIR /app

COPY ./.docker/vhost.conf        /etc/apache2/sites-available/001-localhost.conf
COPY ./.docker/vhost-static.conf /etc/apache2/sites-available/001-static.localhost.conf
RUN a2dissite 000-*
RUN a2ensite 001-*

# Note: We don't need upgrade here.
RUN apt-get update --quiet \
 && apt-get install --quiet --yes --no-install-recommends \
  git \
  zlib1g-dev

RUN docker-php-ext-install \
 pdo \
 pdo_mysql \
 mysqli \
 sockets \
 zip

RUN a2enmod \
 rewrite \
 expires \
 headers

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer global require hirak/prestissimo
RUN composer install --no-interaction

# Required for init
RUN chmod +x .docker/wait-for-it.sh
