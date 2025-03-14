ARG PHP_VERSION=8.3

FROM php:${PHP_VERSION}

RUN apt-get update && apt-get install -y \
    git curl nano zip unzip \
    libpng-dev libonig-dev libxml2-dev libsodium-dev \
    supervisor build-essential autoconf libtool pkg-config \
    net-tools iputils-ping \
    librabbitmq-dev libpq-dev\
    libfreetype-dev \
    libjpeg62-turbo-dev \
    zlib1g-dev \
    libzip-dev \
    libpcre3 libpcre3-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install amqp
RUN docker-php-ext-enable --ini-name 05-opcache.ini opcache
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install sockets pdo pdo_pgsql pgsql
RUN docker-php-ext-enable sockets amqp

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data \
    && usermod -s /bin/bash www-data

ARG SERVICE_NAME=user_service

COPY ./docker/jit.ini /etc/php/${PHP_VERSION}/cli/conf.d/jin.ini
COPY ./docker/${SERVICE_NAME}/entrypoint.sh /var/www/entrypoint.sh
COPY ./docker/${SERVICE_NAME}/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/app
RUN mkdir -p /var/log/supervisor /var/www/app \
    && chown -R www-data:www-data /var/log/supervisor /var/www/app

COPY ./${SERVICE_NAME}/ ./

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.8.2 /usr/bin/composer /usr/bin/composer
RUN composer install
RUN ./vendor/bin/rr get-binary --location /usr/local/bin

USER www-data

EXPOSE 8000

ENTRYPOINT ["/bin/sh", "/var/www/entrypoint.sh"]