#!/bin/sh

sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/nginx.conf

php artisan migrate --force || true

echo "=== which php-fpm ==="
which php-fpm
ls /usr/local/sbin/
ls /usr/local/bin/ | grep php

echo "=== starting fpm ==="
php-fpm --nodaemonize &
FPM_PID=$!
echo "=== FPM PID: $FPM_PID ==="
sleep 2

echo "=== starting nginx ==="
exec nginx -g "daemon off;"