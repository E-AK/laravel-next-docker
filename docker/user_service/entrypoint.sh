cd /var/www/app

php bin/console doctrine:migrations:migrate &
php bin/console lexik:jwt:generate-keypair --overwrite &

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
