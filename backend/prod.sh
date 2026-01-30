#!/usr/bin/env bash
set -e

chmod -R ug+rwX /var/www/html/storage /var/www/html/bootstrap/cache

echo "Waiting for database..."
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
  sleep 2
done

php artisan migrate --seed --force

exec "$@"
