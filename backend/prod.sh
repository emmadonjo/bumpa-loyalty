#!/usr/bin/env bash
set -e

find /var/www/html/storage /var/www/html/bootstrap/cache -type d -exec chmod 775 {} +
find /var/www/html/storage /var/www/html/bootstrap/cache -type f -exec chmod 664 {} +

# Start main process
exec "$@"
