FROM php:7.3-fpm

RUN apt-get update

RUN apt-get install -y libpq-dev zip git \
    &&  docker-php-ext-install pdo pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer
