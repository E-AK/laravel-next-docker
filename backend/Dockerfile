FROM php:8.2.25-fpm-bullseye

RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    libpq-dev\
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip pdo pdo_pgsql pgsql opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY ./backend /var/www/app
WORKDIR /var/www/app

RUN chown -R www-data:www-data /var/www/app \
    && mkdir -p /var/www/app/storage/framework/{sessions,views,cache} \
    && chmod -R 775 /var/www/app/storage

COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]