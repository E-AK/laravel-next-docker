cd /var/www/app

php artisan config:cache
php artisan storage:link
php artisan migrate --force

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
