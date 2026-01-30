#!/usr/bin/env bash
set -e
mkdir -p /var/www/html/storage/logs /var/www/html/bootstrap/cache
chmod -R ug+rwX /var/www/html/storage /var/www/html/bootstrap/cache


php artisan migrate --seed --force

# start main process
exec "$@"
