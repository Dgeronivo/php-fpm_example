FROM php:8.2-fpm

RUN apt-get update;\
    apt-get install -y \
    zip

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer
