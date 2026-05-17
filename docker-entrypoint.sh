#!/bin/sh

sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/nginx.conf

echo "=== Starting PHP-FPM ==="
php-fpm -D
echo "=== FPM done ==="
sleep 2

echo "=== PORT: ${PORT} ==="
ps aux | grep php

echo "=== Starting Nginx ==="
exec nginx -g "daemon off;"