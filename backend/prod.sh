#!/usr/bin/env bash
set -e

find /var/www/html/storage /var/www/html/bootstrap/cache -type d -exec chmod 775 {} +
find /var/www/html/storage /var/www/html/bootstrap/cache -type f -exec chmod 664 {} +

echo "Waiting for database..."
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
  sleep 2
done

php artisan migrate --seed --force

# Start main process
exec "$@"
