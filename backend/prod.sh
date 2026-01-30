#!/usr/bin/env bash
set -e

# Wait for MySQL
echo "Waiting for database..."
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
  sleep 2
done

# Run migrations
php artisan migrate --seed --force

# Start main process
exec php-fpm
