#!/bin/sh

# Hapus set -e dulu untuk debug
sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/nginx.conf

php artisan package:discover --ansi
php artisan migrate --force

echo "=== Starting PHP-FPM ==="
php-fpm -D
echo "=== PHP-FPM exit code: $? ==="
sleep 2

echo "=== PORT: ${PORT} ==="
ps aux | grep php

echo "=== Starting Nginx ==="
exec nginx -g "daemon off;"