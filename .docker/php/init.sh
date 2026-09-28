#!/bin/sh
chown -R www-data:www-data /var/www
chmod -R 755 /var/www/storage
composer install
yarn install


if [ "${OCTANE}" ] && [ "${OCTANE}" -eq 0 ]; then
    php-fpm -F
else
   cd /var/www && php artisan octane:start --watch --server=swoole --host=0.0.0.0 --port=9000
fi
