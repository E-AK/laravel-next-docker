cd /var/www/app

php bin/console doctrine:migrations:migrate &

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
