#!/usr/bin/env bash
set -e

# Ensure writable directories
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for DB
echo "Waiting for database..."
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
  sleep 2
done

# Run migrations
php artisan migrate --seed --force

# Start original CMD (php-fpm or supervisord)
exec "$@"
