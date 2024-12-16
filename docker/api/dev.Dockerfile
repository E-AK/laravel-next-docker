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
    librabbitmq-dev \
    libssl-dev \
    unzip \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install ctype iconv simplexml pdo pdo_pgsql pgsql opcache \
    && pecl install amqp && docker-php-ext-enable amqp

COPY ./api/docker/local.ini /usr/local/etc/php/conf.d/local.ini
COPY ./api/docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN touch /var/log/supervisor/system.log && \
    touch /var/log/supervisor/fpm-out.log && \
    touch /var/log/supervisor/fpm-err.log && \
    touch /var/log/supervisor/queue-out.log && \
    touch /var/log/supervisor/queue-err.log && \
    touch /var/run/supervisord.pid && \
    chown www-data:www-data /var/run/supervisord.pid && \
    chown www-data:www-data -R /var/log/supervisor/* && \
    chmod -R 777 /var/log/supervisor/*

COPY ./api /var/www/app
WORKDIR /var/www/app

COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install

RUN usermod -s /bin/bash www-data
USER www-data

EXPOSE 8000

ENTRYPOINT ["/bin/sh", "/var/www/app/docker/entrypoint.sh"]
