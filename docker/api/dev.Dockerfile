FROM php:8.2.25-fpm-bullseye

RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    libpq-dev\
    libxml2-dev \
    libpcre3 \
    libpcre3-dev \
    unzip \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install ctype iconv simplexml pdo pdo_pgsql pgsql opcache

COPY ./api/docker/local.ini /usr/local/etc/php/conf.d/local.ini

COPY ./api /var/www/app
WORKDIR /var/www/app

COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install

