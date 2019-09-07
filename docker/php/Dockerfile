FROM php:7.3-fpm

RUN apt-get update

RUN apt-get install -y libpq-dev zlib1g-dev libicu-dev zip git \
    && docker-php-ext-configure intl && docker-php-ext-install pdo pdo_mysql intl mysqli

COPY --from=composer /usr/bin/composer /usr/bin/composer

USER 1000
