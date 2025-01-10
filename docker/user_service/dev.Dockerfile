FROM php:8.3.15

RUN apt-get update && apt-get install -y \
    git curl nano zip unzip \
    libpng-dev libonig-dev libxml2-dev libsodium-dev \
    supervisor build-essential autoconf libtool pkg-config \
    libgrpc-dev protobuf-compiler net-tools iputils-ping \
    librabbitmq-dev libpq-dev\
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN git clone --depth 1 -b v1.56.0 https://github.com/grpc/grpc /tmp/grpc && \
    cd /tmp/grpc && \
    git submodule update --init && \
    cd src/php/ext/grpc && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    echo "extension=grpc.so" > /usr/local/etc/php/conf.d/grpc.ini && \
    rm -rf /tmp/grpc

RUN docker-php-ext-enable --ini-name 05-opcache.ini opcache
RUN docker-php-ext-install sockets pdo pdo_pgsql pgsql && docker-php-ext-enable sockets

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data \
    && usermod -s /bin/bash www-data

COPY ./docker/user_service/entrypoint.sh /var/www/entrypoint.sh
COPY ./docker/user_service/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/app
RUN mkdir -p /var/log/supervisor /var/www/app \
    && chown -R www-data:www-data /var/log/supervisor /var/www/app

COPY ./user_service/ ./

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.8.2 /usr/bin/composer /usr/bin/composer
RUN composer install
RUN ./vendor/bin/rr get-binary --location /usr/local/bin

USER www-data

EXPOSE 8000

ENTRYPOINT ["/bin/sh", "/var/www/entrypoint.sh"]
