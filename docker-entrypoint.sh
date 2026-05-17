#!/bin/sh

sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/nginx.conf

php artisan migrate --force || true

echo "=== Starting PHP-FPM ==="
php-fpm -D || true
sleep 1

echo "=== Starting Nginx ==="
exec nginx -g "daemon off;"