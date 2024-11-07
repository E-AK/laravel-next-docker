FROM php:8.2.25-fpm-bullseye

RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    libpq-dev\
    unzip \
    supervisor \
    cron \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip pdo pdo_pgsql pgsql opcache \
    && pecl install redis \
    && docker-php-ext-enable redis


COPY ./backend/docker/local.ini /usr/local/etc/php/conf.d/local.ini

COPY ./backend/docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN touch /var/log/supervisor/system.log && \
    touch /var/log/supervisor/php-fpm-out.log && \
    touch /var/log/supervisor/php-fpm-err.log && \
    touch /var/log/supervisor/cron-out.log && \
    touch /var/log/supervisor/cron-err.log && \
    touch /var/log/supervisor/queue-out.log && \
    touch /var/log/supervisor/queue-err.log && \
    touch /var/run/supervisord.pid && \
    chown www-data:www-data /var/run/supervisord.pid && \
    chown www-data:www-data -R /var/log/supervisor/* && \
    chmod -R 777 /var/log/supervisor/*


COPY ./backend /var/www/app
WORKDIR /var/www/app

RUN chown -R www-data:www-data /var/www/app \
    && mkdir -p /var/www/app/storage/framework/{sessions,views,cache} \
    && chmod -R 775 /var/www/app/storage

COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader

ADD ./backend/docker/crontab /etc/cron.d/laravel-crontab
RUN crontab -u www-data /etc/cron.d/laravel-crontab
RUN chmod u+s /usr/sbin/cron

RUN usermod -s /bin/bash www-data
USER www-data

EXPOSE 9000

ENTRYPOINT ["/bin/sh", "/var/www/app/docker/entrypoint.sh"]
