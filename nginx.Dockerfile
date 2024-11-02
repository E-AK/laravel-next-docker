FROM nginx:1.27.2-alpine3.20

COPY ./backend /var/www/app
COPY ./nginx/etc/conf.d/default.conf /etc/nginx/conf.d/default.conf