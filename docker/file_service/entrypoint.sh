cd /var/www/app

composer install
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate &

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
