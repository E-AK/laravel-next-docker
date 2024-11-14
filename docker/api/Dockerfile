FROM php:8.2.25-fpm-bullseye

RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    libpq-dev\
    libxml2-dev \
    unzip \
    libpcre3 libpcre3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install ctype iconv simplexml tokenizer pdo pdo_pgsql pgsql opcache

COPY ./backend/docker/local.ini /usr/local/etc/php/conf.d/local.ini

COPY ./backend/docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN touch /var/log/supervisor/system.log && \
    touch /var/log/supervisor/php-fpm-out.log && \
    touch /var/log/supervisor/php-fpm-err.log && \
    touch /var/run/supervisord.pid && \
    chown www-data:www-data /var/run/supervisord.pid && \
    chown www-data:www-data -R /var/log/supervisor/* && \
    chmod -R 777 /var/log/supervisor/*


COPY ./api /var/www/app
WORKDIR /var/www/app

COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader

RUN usermod -s /bin/bash www-data
USER www-data

EXPOSE 9000

ENTRYPOINT ["/bin/sh", "/var/www/app/docker/entrypoint.sh"]
