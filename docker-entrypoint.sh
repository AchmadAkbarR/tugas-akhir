#!/bin/sh
set -e

sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/nginx.conf

php artisan package:discover --ansi || true
php artisan migrate --force || true

# Test config nginx dulu
echo "=== Nginx config test ==="
nginx -t

# Start FPM
php-fpm -D
sleep 2

echo "=== PORT is: ${PORT} ==="
echo "=== PHP-FPM processes ==="
ps aux | grep php

exec nginx -g "daemon off;"